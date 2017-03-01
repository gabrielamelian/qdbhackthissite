<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Silex\Application;

/**
 * Utility class for converting a quote id into a quote.
 * @ see http://silex.sensiolabs.org/doc/2.0/usage.html#route-variable-converters
 */
class QuoteConverter {

    private $app = NULL;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function convert($id) {
        $quoteId = (int) $id;
        $quote = $this->app['db']->fetchAssoc("SELECT * FROM qdb_quotes where id = ?", 
            array($quoteId));

        if($quote['status'] !== 1) {
            throw new UnauthorizedHttpException("Unauthorized.");
        }
        
        if(!$quote) {
            throw new NotFoundHttpException(sprintf('Quote %d does not exist.', $id));
        } else {
            return $quote;
        }
    }

}
