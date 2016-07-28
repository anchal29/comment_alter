<?php

namespace Drupal\Tests\comment_alter\Functional;

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
   * Adds an Option Field to the parent enity.
   *
   * @param int $cardinality
   *   Cardinality of the field.
   *
   * @return string
   *   The name of the field which was created.
   */
  protected function addTextField($cardinality) {
    return $this->addField('text', 'text_textfield', [
      'cardinality' => $cardinality,
    ]);
  }

  /**
   * Tests for single valued text field comment altering.
   */
  public function testTextFieldSingle() {
    $field_name = $this->addTextField(1);
    // Create two random values of different length so that they may never be
    // equal.
    $old_value = $this->randomMachineName(5);
    $new_value = $this->randomMachineName(6);

    $this->createEntityObject([$field_name => ['value' => $old_value]]);
    // @todo For debugging/development only to be removed.
    $content = $this->drupalGet('comment/reply/entity_test_rev/' . $this->entity->id() . '/comment');
    file_put_contents('/tmp/comment_form1.html', $content);

    $this->assertAlterableField($field_name);
    $this->postComment(["alterable_fields[{$field_name}][0][value]" => $new_value]);
    // @todo For debugging/development only to be removed.
    $content = $this->drupalGet('entity_test_rev/manage/' . $this->entity->id());
    file_put_contents('/tmp/final_page1.html', $content);

    $this->assertCommentDiff([
      $field_name => [
        [$old_value, $new_value]
      ],
    ]);
    $this->assertRevisionDelete();

  }

  /**
   * Tests for multi-valued text field comment altering.
   */
  public function testTextFieldMultiple() {
    $field_name = $this->addTextField(-1);
    // Create two random values of different length so that they may never be
    // equal.
    $old_value = $this->randomMachineName(5);
    $new_value = $this->randomMachineName(6);

    $this->createEntityObject([
      $field_name => [
        0 => ['value' => $old_value]
      ]
    ]);
    // @todo For debugging/development only to be removed.
    $content = $this->drupalGet('comment/reply/entity_test_rev/' . $this->entity->id() . '/comment');
    file_put_contents('/tmp/comment_form2.html', $content);

    $this->assertAlterableField($field_name);
    // The alterable fields on comment form have a wrapper of alterable_fields
    // over them because of the #parent property specified in the
    // comment_form_alter.
    $this->postComment([
      "alterable_fields[{$field_name}][1][value]" => $new_value
    ]);
    // @todo For debugging/development only to be removed.
    $content = $this->drupalGet('entity_test_rev/manage/' . $this->entity->id());
    file_put_contents('/tmp/final_page2.html', $content);

    $this->assertCommentDiff([
      $field_name => [
        [$old_value, $old_value],
        [NULL, $new_value]
      ],
    ]);
    $this->assertRevisionDelete();
  }

}
