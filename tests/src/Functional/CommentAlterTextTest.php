<?php

namespace Drupal\Tests\comment_alter\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\comment_alter\Functional\CommentAlterTestBase;
use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Form\FormState;

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
    ], TRUE);
    // Invalidate cache after selecting comment_alter option for our field.
    \Drupal::cache()->delete('comment_alter_fields:' . $this->entityType . ':' . $this->bundle);
    $old_value = $this->randomMachineName(5);
    $new_value = $this->randomMachineName(6);

    $this->createEntityObject([$field_name => ['value' => $old_value]]);
    $this->assertAlterableField($field_name, TRUE);
    $this->postComment([$field_name => $new_value]);
  }
}
