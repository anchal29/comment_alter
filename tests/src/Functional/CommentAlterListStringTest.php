<?php

namespace Drupal\Tests\comment_alter\Functional;

use Drupal\Tests\comment_alter\Functional\CommentAlterTestBase;

/**
 * Tests the comment alter module functions for List (string) fields.
 *
 * @group comment_alter
 */
class CommentAlterListStringTest extends CommentAlterTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['options'];

  /**
   * Tests for single valued List (string) fields comment altering.
   */
  public function testOptionsSelectSingle() {
    $field_name = $this->addField('list_string', 'options_select', [
      'settings' => [
        'allowed_values' => [1 => 'One', 2 => 'Two', 3 => 'Three']
      ],
      'cardinality' => 1,
    ]);
    // Invalidate cache after selecting comment_alter option for our field.
    \Drupal::cache()->delete('comment_alter_fields:' . $this->entityType . ':' . $this->bundle);

    $this->createEntityObject([
      $field_name => [
        'value' => 1
      ]
    ]);
    $content = $this->drupalGet('comment/reply/entity_test_rev/' . $this->entity->id() . '/comment');
    file_put_contents('/tmp/comment_form3.html', $content);

    $this->assertAlterableField($field_name, TRUE);
    $this->postComment([$field_name => 2]);
    $content = $this->drupalGet('entity_test_rev/manage/' . $this->entity->id());
    file_put_contents('/tmp/final_page3.html', $content);

    $this->assertCommentDiff([
      $field_name => [
        [1, 2]
      ],
    ]);
  }

  /**
   * Tests for multi-valued List (string) fields comment altering.
   */
  public function testOptionsSelectMultiple() {
    $field_name = $this->addField('list_string', 'options_select', [
      'settings' => [
        'allowed_values' => [1 => 'One', 2 => 'Two', 3 => 'Three']
      ],
      'cardinality' => -1,
    ]);
    // Invalidate cache after selecting comment_alter option for our field.
    \Drupal::cache()->delete('comment_alter_fields:' . $this->entityType . ':' . $this->bundle);

    $this->createEntityObject([
      $field_name => [
        0 => ['value' => 1]
      ]
    ]);
    $content = $this->drupalGet('comment/reply/entity_test_rev/' . $this->entity->id() . '/comment');
    file_put_contents('/tmp/comment_form4.html', $content);
    $this->assertAlterableField($field_name, TRUE);
    // The alterable fields on comment form have a wrapper of alterable_fields
    // over them because of the #parent property specified in the
    // comment_form_alter.
    $this->postComment([
      "alterable_fields[{$field_name}][]" => [1, 2]
    ]);
    $content = $this->drupalGet('entity_test_rev/manage/' . $this->entity->id());
    file_put_contents('/tmp/final_page4.html', $content);
    $this->assertCommentDiff([
      $field_name => [
        [1, 1],
        [NULL, 2],
      ],
    ]);
  }

  /**
   * Tests for single valued List (string) fields comment altering.
   */
  public function testOptionsButtonSingle() {
    $field_name = $this->addField('list_string', 'options_buttons', [
      'settings' => [
        'allowed_values' => [1 => 'One', 2 => 'Two', 3 => 'Three']
      ],
      'cardinality' => 1,
    ]);
    // Invalidate cache after selecting comment_alter option for our field.
    \Drupal::cache()->delete('comment_alter_fields:' . $this->entityType . ':' . $this->bundle);

    $this->createEntityObject([
      $field_name => [
        'value' => 1
      ]
    ]);
    $content = $this->drupalGet('comment/reply/entity_test_rev/' . $this->entity->id() . '/comment');
    file_put_contents('/tmp/comment_form5.html', $content);
    $this->postComment([
      "alterable_fields[{$field_name}]" => 2
    ]);
    $content = $this->drupalGet('entity_test_rev/manage/' . $this->entity->id());
    file_put_contents('/tmp/final_page5.html', $content);
    $this->assertCommentDiff([
      $field_name => [
        [1, 2]
      ],
    ]);
  }

  /**
   * Tests for multi-valued List (string) fields comment altering.
   */
  public function testOptionsButtonMultiple() {
    $field_name = $this->addField('list_string', 'options_buttons', [
      'settings' => [
        'allowed_values' => [1 => 'One', 2 => 'Two', 3 => 'Three']
      ],
      'cardinality' => -1,
    ]);
    // Invalidate cache after selecting comment_alter option for our field.
    \Drupal::cache()->delete('comment_alter_fields:' . $this->entityType . ':' . $this->bundle);

    $this->createEntityObject([
      $field_name => [
        0 => ['value' => 1]
      ]
    ]);
    $content = $this->drupalGet('comment/reply/entity_test_rev/' . $this->entity->id() . '/comment');
    file_put_contents('/tmp/comment_form6.html', $content);
    $this->postComment([
      "alterable_fields[{$field_name}][2]" => TRUE
    ]);
    $content = $this->drupalGet('entity_test_rev/manage/' . $this->entity->id());
    file_put_contents('/tmp/final_page6.html', $content);
    $this->assertCommentDiff([
      $field_name => [
        [1, 1],
        [NULL, 2],
      ],
    ]);
  }

}
