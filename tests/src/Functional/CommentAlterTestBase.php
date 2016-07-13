<?php

namespace Drupal\Tests\comment_alter\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Base class for Comment Alter test cases.
 *
 * @group comment_alter
 */
class CommentAlterTestBase extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
    public static $modules = ['comment_alter'];

  /**
   * The admin user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser(['access administration pages']);
  }

  /**
   * Just for testing purpose.
   * @todo Instead of this add other functions here.
   */
  public function testChecking() {
    $this->assertEqual('1', '1');
  }

}
