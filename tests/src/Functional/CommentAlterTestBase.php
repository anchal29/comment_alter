<?php

namespace Drupal\Tests\comment_alter\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\comment\Entity\CommentType;
use Drupal\comment\Tests\CommentTestTrait;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Component\Utility\Unicode;

/**
 * Base class for Comment Alter test cases.
 *
 * @group comment_alter
 */
class CommentAlterTestBase extends BrowserTestBase {

  use CommentTestTrait;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['comment_alter', 'entity_test', 'comment'];

  /**
   * The admin user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The entity to use within tests.
   *
   * @var \Drupal\entity_test\Entity\EntityTest
   */
  protected $entity;

  /**
   * {@inheritoc}
   */
  protected function setUp() {
    parent::setUp();
    // Create a bundle for entity_test.
    entity_test_create_bundle('entity_test_bundle', 'Entity Test Bundle');
    CommentType::create([
      'id' => 'comment',
      'label' => 'Comment',
      'description' => 'Comment type for Comment Alter',
      'target_entity_type_id' => 'entity_test',
    ])->save();
    // Add a comment field on entity_test_bundle.
    $this->addDefaultCommentField('entity_test', 'entity_test_bundle');
    // Provide necessary permissions to the adminUser.
    $this->adminUser = $this->drupalCreateUser([
      'administer comments',
      'post comments',
      'access comments',
      'view test entity',
      'view test entity field',
      'administer entity_test content',
      'access administration pages',
    ]);
    $this->drupalLogin($this->adminUser);

  }

  /**
   * Adds a field to the entity_test entity type.
   *
   * @param string $field_type
   *   The field type name (Eg. text).
   * @param string $widget_type
   *   The widget name (Eg. text_textfield).
   * @param array $field_settings
   *   (optional) An array that gets added to the array passed to
   *   FieldConfig::create().
   * @param boolean $comment_alter
   *   (optional) Option to enable/disable comment_alter for this field.
   *
   * @return string
   *   The name of the field that was created.
   */
  protected function addField($field_type, $widget_type, $field_settings = array(), $comment_alter = TRUE) {
    $this->fieldName = Unicode::strtolower($this->randomMachineName() . '_field_name');

    FieldStorageConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => 'entity_test',
      'type' => $field_type,
    ] + $field_settings)->save();
    FieldConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => 'entity_test',
      'bundle' => 'entity_test_bundle',
      'widget' => [
        'type' => $widget_type,
      ],
      'third_party_settings' => [
        'comment_alter' => [
          'comment_alter_enabled' => $comment_alter,
        ],
      ],
    ])->save();

    return $this->fieldName;
  }

  /**
   * Creates an entity object with the provided values.
   *
   * @param array $values
   *   (optional) An array of values to set, keyed by property name.
   */
  protected function createEntityObject($values = []) {
    // Create a test entity object for the entity_test_bundle.
    $random_label = $this->randomMachineName();
    $data = ['type' => 'entity_test_bundle', 'name' => $random_label] + $values;
    $this->entity = EntityTest::create($data);
    $this->entity->save();
  }

  /**
   * Asserts that comment alterable field is present on the comment form.
   *
   * @param string $field_name
   *   Field added to the entity_test_bundle.
   * @param boolean $enabled_alterable_field
   *   Boolean indicating whether comment alter option is enabled for the field.
   */
  protected function assertAlterableField($field_name, $enabled_alterable_field) {
    $this->assertEqual(entity_get_form_display('comment', 'comment', 'default')->getComponent($field_name), $enabled_alterable_field);
  }

  /**
   * Posts a comment using the psuedo browser.
   *
   * @param array $comment_edit
   *   (optional) An array that gets added to the $edit array passed to
   *   $this->drupalPostForm().
   */
  protected function postComment($comment_edit = []) {
    // Populate the subject and body fields.
    $edit['comment_body[0][value]'] = $this->randomMachineName(20);
    $edit['subject[0][value]'] = $this->randomMachineName(5);
    $edit = array_merge($edit, $comment_edit);
    $this->drupalGet('comment/reply/entity_test/' . $this->entity->id() . '/comment');
    $this->drupalPostForm(NULL, $edit, t('Save'));
  }

}
