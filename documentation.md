#Technology used for this project

PHP
Composer
doctrine/dbal
silex
MariaDB
Securimage
PHPUnit for testing

* Tests have been created using PHPUnit and they can be run from the tests folder after cloning the project. The DB needs to be seeded before this (dummy data)

#Endpoints

...Restful routes...

##Submit

###Url /submit.php

* GET Renders form page. Needs to include Captcha. Submit button and Reset button.

* POST Sends contents to DB on body such as follows:

``json
{
  "quote": "This is the quote content"
}
``

If the request is unsuccesfull it will redirect to the submit page again

##Latest

* GET render page with newest 50 posts from DB. Add a refresh button.
