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
   */
  protected function addField()
  {
    // @todo Add field settings to it.
    $this->fieldName = Unicode::strtolower($this->randomMachineName() . '_field_name');

    FieldStorageConfig::create(array(
      'field_name' => $this->fieldName,
      'entity_type' => 'entity_test',
      'type' => 'text'
    ))->save();
    FieldConfig::create([
      'field_name' => $this->fieldName,
      'entity_type' => 'entity_test',
      'bundle' => 'entity_test_bundle',
    ])->save();
    return $this->fieldName;
  }

  /**
   * Just for testing purpose.
   * @todo Instead of this add other functions here.
   */
  public function testChecking() {
    $fieldName = $this->addField();
    $field_storage = FieldStorageConfig::loadByName('entity_test', $fieldName);
    $this->assertTrue($field_storage, 'Node body field storage exists.');
    $this->assertEqual('1', '1');
  }

}
