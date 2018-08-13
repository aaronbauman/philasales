<?php

namespace Drupal\philasales\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\philasales\DivisionInterface;

/**
 * Defines the division entity class.
 *
 * @ContentEntityType(
 *   id = "division",
 *   label = @Translation("Division"),
 *   label_collection = @Translation("Divisions"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\philasales\DivisionListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\philasales\Form\DivisionForm",
 *       "edit" = "Drupal\philasales\Form\DivisionForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "division",
 *   data_table = "division_field_data",
 *   admin_permission = "administer division",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/division/add",
 *     "canonical" = "/division/{division}",
 *     "edit-form" = "/admin/content/division/{division}/edit",
 *     "delete-form" = "/admin/content/division/{division}/delete",
 *     "collection" = "/admin/content/division"
 *   },
 *   field_ui_base_route = "entity.division.settings"
 * )
 */
class Division extends ContentEntityBase implements DivisionInterface {

  /**
   * {@inheritdoc}
   *
   * When a new division entity is created, set the uid entity reference to
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
    return $this->get('division')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('division', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['division'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Division'))
      ->setDescription(t('Division number.'))
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

    $fields['ward'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Ward'))
      ->setDescription('Ward number')
      ->setRequired(TRUE)
      ->setSettings([
        'target_type' => 'ward'
      ]);

    $fields['geodata'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Geodata'))
      ->setRequired(TRUE);

    return $fields;
  }

}
