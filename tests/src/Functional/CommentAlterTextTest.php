<?php

namespace Drupal\Tests\comment_alter\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\comment_alter\Functional\CommentAlterTestBase;

/**
 * Tests the comment alter module functions for text fields.
 *
 * @group comment_alter
 */
class CommentAlterTextTest extends CommentAlterTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['text'];

  /**
   * Tests for single valued text field comment altering.
   */
  public function testTextFieldSingle() {
    $field_name = $this->addField('text', 'text_textfield', [
      'cardinality' => 1,
    ]);

    $old_value = $this->randomMachineName();
    $new_value = $this->randomMachineName();
    $this->assertNotEqual($old_value, $new_value);

    $this->createEntityObject([$field_name => ['value' => 'foo']]);
    $this->assertAlterableField($field_name, TRUE);
    $this->postComment();
  }
}
