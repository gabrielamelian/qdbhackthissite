<?php

namespace Controllers;

use FormTypes\CaptchaType;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints as Assert;

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
        $db = $app['db'];
        $quotes = $db->fetchAll('SELECT * FROM qdb_quotes WHERE status = 1 ORDER BY RAND() LIMIT 50');
        return $app['twig']->render('display_quotes.html', [ "quotes" => $quotes ]);
    }

    private function validateVote($app, $action) {
        $validator = $app['validator'];

        $errors = $validator->validate($action, new
            Assert\Choice($this->valid_actions));

        if(sizeof($errors) > 0) {
            throw new BadRequestHttpException($errors[0]->getMessage());
        }
    }

    public function vote(Request $request, Application $app, $quote) {
        $action = $request->get('value');
        $result = $this->validateVote($app, $action);

        $newVoteCount = $quote['votes'] + 1;
        $newScore = $action == "upvote" ? $quote['score'] + 1 : $quote['score'] - 1;

        $app['db']->update('qdb_quotes', 
            array('votes' => $newVoteCount, 'score' => $newScore), 
            array('id' => $quote['id']));
        
        return "OK";
    }
}
