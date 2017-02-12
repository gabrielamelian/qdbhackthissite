<?php

namespace Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VoteIsValid extends Constraint {
    public $message = 'Vote invalid. Please upvote or downvote only once.';
    public $prevVote = NULL;

    /**
     * @param $prevVote the previous vote for this IP address and quote ID, or
     * NULL if this is the first vote.
     */
    public function __construct($prevVote) {
        $this->prevVote = $prevVote;
    }
}

class VoteIsValidValidator extends ConstraintValidator {
    public function validate($value, Constraint $constraint) {
        $prevVote = $constraint->prevVote;
        if($prevVote) {
            $prevValue = $prevVote['value'];
            $doubleUpvote = $prevValue == 0 && $value == 'upvote';
            $doubleDownvote = $prevValue == 1 && $value == 'downvote';

            if($doubleUpvote || $doubleDownvote) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
