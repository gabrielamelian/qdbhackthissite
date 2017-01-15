<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use FormTypes\CaptchaType;

class Quotes {

    private $valid_actions = array(
        'upvote',
        'downvote'
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

            return $app->redirect("/quotes/$newRowId");
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
        return "<html>lol</html>";  
    }

    private function validateVote(Request $request, Application $app, $quoteId) {
        $action = $request->get('value');
        if(!in_array($action, $this->valid_actions)) {
            return new Response('Invalid action.', 500);
        }

        $quoteId = (int) $quoteId;
        $quote = $app['db']->fetchAssoc("SELECT * FROM qdb_quotes where id = ?", 
            array($quoteId));
        
        if(!$quote) {
            return new Response('Invalid quote id.', 500);
        } else {
            return $quote;
        }
    }

    public function vote(Request $request, Application $app, $quoteId) {
        $result = $this->validateVote($request, $app, $quoteId);
        $resultIsError = !is_array($result);
        if($resultIsError) {
            return $result;
        } else {
            $quote = $result;
        }

        $newVoteCount = $quote['votes'] + 1;
        $newScore = $action == "upvote" ? $quote['score'] + 1 : $quote['score'] - 1;

        $app['db']->update('qdb_quotes', 
            array('votes' => $newVoteCount, 'score' => $newScore), 
            array('id' => $quoteId));
        
        return "lol";
    }
}
