<?php

namespace Drupal\philasales\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\philasales\WardInterface;

/**
 * Defines the ward entity class.
 *
 * @ContentEntityType(
 *   id = "ward",
 *   label = @Translation("Ward"),
 *   label_collection = @Translation("Wards"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\philasales\WardListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\philasales\Form\WardForm",
 *       "edit" = "Drupal\philasales\Form\WardForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "ward",
 *   data_table = "ward_field_data",
 *   admin_permission = "administer ward",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "ward",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/ward/add",
 *     "canonical" = "/ward/{ward}",
 *     "edit-form" = "/admin/content/ward/{ward}/edit",
 *     "delete-form" = "/admin/content/ward/{ward}/delete",
 *     "collection" = "/admin/content/ward"
 *   },
 *   field_ui_base_route = "entity.ward.settings"
 * )
 */
class Ward extends ContentEntityBase implements WardInterface {

  /**
   * {@inheritdoc}
   *
   * When a new ward entity is created, set the uid entity reference to
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
    return $this->get('ward')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('ward', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['ward'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Ward'))
      ->setDescription(t('The ward number.'))
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

    $fields['geodata'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Geodata'))
      ->setRequired(TRUE);

    return $fields;
  }

}
