<?php

namespace Controllers;

use FormTypes\CaptchaType;
use Constraints\VoteIsValid;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints as Assert;

class Quotes {

    private $valid_actions = array(
        0 => 'upvote',
        1 => 'downvote'
    );

    private function getForm(Request $request, Application $app) {
        $form = $app['form.factory']->createBuilder(FormType::class)
            ->add('quote', TextareaType::class, array(
                'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Length(array('min' => 10))
                )))
            ->add('captcha', CaptchaType::class)
        ->getForm();

        $form->handleRequest($request);

        return $form;
    }

    public function submit(Request $request, Application $app) {
        $form = $this->getForm($request, $app);

        if($form->isValid()) {
            $data = $form->getData();

            $db = $app['db'];
            $db->insert('qdb_quotes', array(
                'quote' => $data['quote']
            ));

            $newRowId = $db->lastInsertId();

            return $app->redirect("/quotes/latest?submitted=true");
        }

        return $app['twig']->render('quote_submit.html', array(
            'form' => $form->createView()
        ));
    }

    public function captcha(Request $request, Application $app) {
        $img = new \Securimage();
        return $img->show();
    }

    public function random(Request $request, Application $app) {
        $db = $app['db'];
        $quotes = $db->fetchAll('SELECT * FROM qdb_quotes WHERE status = 1 ORDER BY RAND() LIMIT 50');
        return $app['twig']->render('display_quotes.html', [ "quotes" => $quotes ]);
    }

    public function top50(Request $request, Application $app) {
        $db = $app['db'];
        $quotes = $db->fetchAll('SELECT * FROM qdb_quotes WHERE status = 1 ORDER BY score DESC LIMIT 50');
        return $app['twig']->render('display_quotes.html', [ "quotes" => $quotes ]);
    }

    public function bottom50(Request $request, Application $app) {
        $db = $app['db'];
        $quotes = $db->fetchAll('SELECT * FROM qdb_quotes WHERE status = 1 ORDER BY score LIMIT 50');
        return $app['twig']->render('display_quotes.html', [ "quotes" => $quotes ]);
    }

    public function latest(Request $request, Application $app) {
        $db = $app['db'];
        $quotes = $db->fetchAll('SELECT * FROM qdb_quotes WHERE status = 1 ORDER BY id desc LIMIT 50');

        $submittedQuote = $request->get('submitted');

        return $app['twig']->render('display_quotes.html', [ 
            "quotes" => $quotes,
            "submittedQuote" => $submittedQuote
        ]);
    }

    private function validateVote($app, $action, $prevVote) {
        $validator = $app['validator'];

        $errors = $validator->validate($action, [
            new Assert\Choice($this->valid_actions),
            new VoteIsValid($prevVote)
        ]);

        if(sizeof($errors) > 0) {
            throw new BadRequestHttpException($errors[0]->getMessage());
        }
    }

    private function storeVote($app, $ipAddress, $action, $prevVote, $quoteId) {
        $vote = [
            'qid' => $quoteId,
            'ip' => $ipAddress,
            'value' => $action == 'upvote' ? 0 : 1
        ];

        if($prevVote) {
            $app['db']->update('qdb_votes', $vote, ['id' => $prevVote['id']]);
        } else {
            $app['db']->insert('qdb_votes', $vote);
        }
    }

    public function vote(Request $request, Application $app, $quote) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $prevVote = $app['db']->fetchAssoc('SELECT * FROM qdb_votes WHERE ip = ? and qid = ?', [
            $ipAddress,
            $quote['id']
        ]);

        $action = $request->get('value');
        $result = $this->validateVote($app, $action, $prevVote);


        $undoLastVote = $prevVote !== false;
        if($undoLastVote) {
            $newVoteCount = $quote['votes'];
            $newScore = $action == "upvote" ? $quote['score'] + 2 : $quote['score'] - 2;
        } else {
            $newVoteCount = $quote['votes'] + 1;
            $newScore = $action == "upvote" ? $quote['score'] + 1 : $quote['score'] - 1;
        }

        $app['db']->update('qdb_quotes',
            array('votes' => $newVoteCount, 'score' => $newScore),
            array('id' => $quote['id']));

        $this->storeVote($app, $ipAddress, $action, $prevVote, $quote['id']);

        return "OK";
    }

    public function viewQuote(Request $request, Application $app, $quote) {
        return $app['twig']->render('display_quote.html', array(
            'quote' => $quote
        ));
    }
}
