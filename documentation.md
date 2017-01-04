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

##Random

###Url /index

* GET renders page with 50 quotes in random order from the DB. Add a refresh button.

##Submit

###Url /submit

* GET Renders form page. Needs to include Captcha. Submit button and Reset button.

* POST Sends contents to DB on body such as follows:

```json
{
  "quote": "This is the quote content"
}
```

If the request is unsuccesfull it will redirect to the submit page again.

##Latest

###Url /latest

* GET render page with newest 50 posts from DB. Add a refresh button.


##Top 50

###Url /top50

* GET renders page with 50 posts with the most number of upvotes.

##Bottom 50

###Url /bottom50

* GET renders page with 50 posts with the least number of upvotes.

##Votes

###Url /vote

* POST to change the vote of a quote. Each IP address can upvote or downvote each quote only once. The server will check the ip address and the previous vote

```json
{
  "qid": "451",
  "value": "upvote"
}
```

##Admin

###Url /admin

* GET renders the admin login page

* POST username and password for authentication

###Url /admin/approve

* GET renders admin page that includes new post for approval by default. User to be able to filter by new, rejected, approved and add search function by quote id. Requires authentication.

###Url /admin/approve/{id}

* GET to view a single quote. Includes the buttons to approve or reject. After approval has been completed page shows message indicating succesful and the ability to return to the previous page.

###Url /admin/approve/{id}

* POST to approve or reject a quote. Requires authentication.

```json
{
  "qid": "451",
  "status": "approved"
}
```
