<?php

namespace Drupal\philasales\Entity;

use Drupal\Console\Utils\Create\Base;
use Drupal\Core\Access\AccessResultAllowed;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\philasales\PropertyInterface;

/**
 * Defines the property entity class.
 *
 * @ContentEntityType(
 *   id = "property",
 *   label = @Translation("Property"),
 *   label_collection = @Translation("Properties"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\philasales\PropertyListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\philasales\Form\PropertyForm",
 *       "edit" = "Drupal\philasales\Form\PropertyForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "property",
 *   data_table = "property_field_data",
 *   revision_table = "property_revision",
 *   revision_data_table = "property_field_revision",
 *   show_revision_ui = TRUE,
 *   admin_permission = "administer property",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "revision_id",
 *     "label" = "location",
 *     "uuid" = "uuid"
 *   },
 *   revision_metadata_keys = {
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/property/add",
 *     "canonical" = "/property/{property}",
 *     "edit-form" = "/admin/content/property/{property}/edit",
 *     "delete-form" = "/admin/content/property/{property}/delete",
 *     "collection" = "/admin/content/property"
 *   },
 *   field_ui_base_route = "entity.property.settings"
 * )
 */
class Property extends RevisionableContentEntityBase implements PropertyInterface {

  use EntityChangedTrait;

  public function access($operation, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($operation == 'view label') {
      return AccessResultAllowed::allowed();
    }
    return parent::access($operation, $account, $return_as_object);
  }

  /**
   * {@inheritdoc}
   *
   * When a new property entity is created, set the uid entity reference to
   * the current user as the creator of the entity.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += ['uid' => \Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    $title = $this->get('location')->value;
    if (!empty($this->get('unit')->value)) {
      $title .= ' ' . ltrim($this->get('unit')->value, '0');
    }
    return $title;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getTitle();
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('location', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['location'] = BaseFieldDefinition::create('string')
      ->setRevisionable(TRUE)
      ->setLabel(t('Location'))
      ->setDescription(t('The location of the property entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['unit'] = BaseFieldDefinition::create('string')
      ->setLabel('Unit');

    $fields['force_refresh'] = BaseFieldDefinition::create('boolean')
      ->setLabel('Force Refresh')
      ->setDescription('Property record will be refreshed if sale date is within the past year.')
      ->setDefaultValue(0);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the property was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the property was last edited.'));


    $fields['ward'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Ward'))
      ->setDescription('Ward number')
      ->setRequired(TRUE)
      ->setSettings([
        'target_type' => 'ward'
      ]);

    $fields['division'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Division Lookup'))
      ->setDescription('Division Lookup')
      ->setRequired(TRUE)
      ->setSettings([
        'target_type' => 'division'
      ]);

    $fields['div_num'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Division Number'));

    $fields['geofield'] = BaseFieldDefinition::create('geofield')
      ->setLabel(t('Geofield'))
      ->setRequired(TRUE);

    $fields['owner_1'] = BaseFieldDefinition::create('string')
      ->setLabel('Owner 1')
      ->setRevisionable(TRUE);

    $fields['owner_2'] = BaseFieldDefinition::create('string')
      ->setLabel('Owner 2')
      ->setRevisionable(TRUE);

    $fields['market_value'] = BaseFieldDefinition::create('integer')
      ->setLabel('Market Value')
      ->setRevisionable(TRUE);

    $fields['sale_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel('Sale date - datetime')
      ->setRevisionable(TRUE);

    $fields['sale_timestamp'] = BaseFieldDefinition::create('integer')
      ->setLabel('Sale date - DEPRECATED')
      ->setRevisionable(TRUE);

    $fields['sale_date_unix'] = BaseFieldDefinition::create('timestamp')
      ->setLabel('Sale date - timestamp')
      ->setRevisionable(TRUE);

    $fields['sale_price'] = BaseFieldDefinition::create('integer')
      ->setLabel('Sale price')
      ->setRevisionable(TRUE);

    $fields['zip_code'] = BaseFieldDefinition::create('string')
      ->setLabel('Zip');

    $fields['recording_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel('Recording Date');

    $fields['total_area'] = BaseFieldDefinition::create('integer')
      ->setLabel('Total Area')
      ->setRevisionable(TRUE);

    $fields['total_livable_area'] = BaseFieldDefinition::create('integer')
      ->setLabel('Total Livable Area')
      ->setRevisionable(TRUE);

    $fields['geoFail'] = BaseFieldDefinition::create('boolean')
      ->setLabel('Geocoding for Ward/Division failed');

    return $fields;
  }

}
