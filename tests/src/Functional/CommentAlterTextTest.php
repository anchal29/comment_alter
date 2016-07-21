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
    ], TRUE);

    $old_value = $this->randomMachineName();
    $new_value = $this->randomMachineName();
    $this->assertNotEqual($old_value, $new_value);

    $this->createEntityObject([$field_name => ['value' => 'foo']]);
    // @todo Remove the following unwanted lines. It is just for debugging.
    $this->content = $this->drupalGet('entity_test/structure/entity_test_bundle/form-display');
    file_put_contents('/home/anchal/debug/entity_page.html', $this->content);
    $this->content = $this->drupalGet('entity_test/structure/entity_test_bundle/fields/entity_test.entity_test_bundle.' . $field_name);
    file_put_contents('/home/anchal/debug/entity_page1.html', $this->content);
    $this->content = $this->drupalGet('comment/reply/entity_test/' . $this->entity->id() . '/comment');
    file_put_contents('/home/anchal/debug/comment_form.html', $this->content);
    // Till here.
    $this->assertAlterableField($field_name, TRUE);
    $this->postComment([$field_name => 'bar']);
    // @todo Remove these lines also.
    $this->content = $this->drupalGet('entity_test/' . $this->entity->id());
    file_put_contents('/home/anchal/debug/final_page.html', $this->content);

  }
}
