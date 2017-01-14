<?php

namespace FormTypes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MatchesCaptcha extends Constraint {
    public $message = 'Input text did not match CAPTCHA.';
}

class MatchesCaptchaValidator extends ConstraintValidator {
    public function validate($value, Constraint $constraint) {
        if(defined('RUNNING_UNIT_TESTS')) {
            return;
        }

        $img = new \Securimage();

        $failed = $img->check($value) == false;
        if ($failed) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}

class CaptchaType extends AbstractType {
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'constraints' => array(
                new MatchesCaptcha(), 
            )
        ));
    }

    public function getParent() {
        return TextType::class;
    }
}

