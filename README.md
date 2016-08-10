# Comment Alter for Drupal 8

The Comment Alter module allows to alter any fields of an entity through comments attached to it.

By using only standard Drupal components like Fields and Views, it can be used to construct any variety of full-featured bug trackers, customer support, sales or project management tools.

## Dependencies

[Diff module](https://www.drupal.org/project/diff) is the only dependency which is used to display the changes made on a particular comment.

## Installation and Usage

Comment Alter module can be easily installed by

1. Copying the comment_alter and diff folders to the modules folder of your Drupal installation, and
2. Then enabling them from the Modules administration page (/admin/modules).

## Executing the automated tests

The module has PHPUnit tests and it is recommended to execute the unit tests using command line instead of the web interface provided by the SimpleTest module. More instructions can be found [on the related drupal.org documentation page](https://www.drupal.org/node/2116263).

#### Executing these web tests:

    cd /path/to/drupal-8/core
    export SIMPLETEST_DB=mysql://username:password@localhost/databasename
    export SIMPLETEST_BASE_URL=http://localhost/drupal-8
    ../vendor/bin/phpunit --group comment_alter


When the user executing these functional tests is different than the one running the web server (apache for example), an exception is thrown and it’s not very clear what the problem is. Running these tests as root or changing permissions of files doesn’t have any effect on it.

The recommended approach here is to change the user running the web server to the system user.
Other way is to run these tests with the same user as the one running the web server, this can be achieved by using this command instead (see [#2760905](https://www.drupal.org/node/2760905)):

    sudo -u apache ../vendor/bin/phpunit --group comment_alter

To execute any single test or a class the `--filter` tag can be used. Example: if we just want to run the `testOptionsSelectSingle` test then following command will do so:

    ../vendor/bin/phpunit --group comment_alter --filter testOptionsSelectSingle
