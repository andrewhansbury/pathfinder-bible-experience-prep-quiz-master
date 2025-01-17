# Pathfinder Bible Experience Prep and Quiz Master

**If you are looking for the PBE Quiz Engine by Tony Phillips, please visit http://pbequizengine.com/.**

NOTICE: If you are looking for the website at http://pbe.uccsda.org (PBE Quiz Master), it is no longer available. A replacement is in the works and is available at https://pbeprep.com. Thanks for your patience while we work on getting things operational again!

The [Pathfinder Bible Experience](http://www.pathfindersonline.org/pathfinder-bible-experience) (PBE) Prep and Quiz Master is a Bible quiz website specifically tailored for the [Pathfinders](http://www.pathfindersonline.org/) in the North American Division of Seventh-day Adventists. It can be used for a general purpose Bible quiz website, though! It supports the following nifty features:

* Robust and full-featured quiz setup
    * Q&A style or fill in the blank style questions
    * Can create questions based on the Bible or on different SDA Bible Commentaries
    * Fill in the blank questions can have a configurable whitelist for questions that should not be blanked out, and users can set a custom amount of words to be blanked
    * Weighted question distribution for quiz taking or print outs
    * Save questions that you answered correctly so you aren't asked those questions again in the future
    * Load questions sequentially or randomly and output them sequentially or randomly
* Awesome quiz taking
    * Quizzes that work offline (except for quiz generation and flagging questions) -- generate a 500 question quiz, jump in the car, and take the quiz while on the go! 
    * While taking a quiz, view statistics on how well you're doing overall or per Bible Chapter/Commentary
    * View previously answered questions at any time while taking a quiz
* PDF print outs of quizzes that can be front/back print outs or show both questions and answers (or fill in the blank answers) on the same page
* Create PDF print outs that only contain recently added questions so you don't have to sort through old questions 
* Admins can upload study materials for other users to download
* Website administrators can edit pretty much everything from an Admin panel
* Responsive website -- use on your tablet or phone!
* Add questions in English, Spanish, or French
* Matching quizzes! Drag and drop matching quizzes that let you memorize information in a matching format!

## Demo

You can view a read-only demo of the website at [https://pbeprep.com](https://pbeprep.com) with the password '4guest'.

## Contributing

Please contribute as much as you like! Any improvements and suggestions are welcome.

## Setup

Minimum requirements: PHP 7.1+ and a MariaDB database.

1. `curl -s https://getcomposer.org/installer | php`
2. `php composer.phar install --no-dev`
3. `vendor/bin/phinx init .`
5. Edit phinx.yml with your db details (note that default_database is really the default environment -_- -- see https://github.com/cakephp/phinx/issues/984)
6. `vendor/bin/phinx migrate -e development` (or other environment name)
7. Seed database using `vendor/bin/phinx seed:run`
8. Copy the `config-private.sample.php` to `config-private.php`. You don't necessarily have to adjust anything here other than the DB details unless you want to change the session name (for instance, if you're hosting multiple versions of the site on one server instance. If so, these should be unique between sites on the same host).

If you have issues with the database migrations, https://stackoverflow.com/a/25782795/3938401 might be helpful, and you might want to use 127.0.0.1 instead of `localhost`

You should now be able to login via `pbedb7`. I highly suggest changing that password via your preferred database modification method.
