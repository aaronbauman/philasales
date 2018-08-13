<?php

namespace Drupal\philasales;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a property entity type.
 */
interface PropertyInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the property title.
   *
   * @return string
   *   Title of the property.
   */
  public function getTitle();

  /**
   * Sets the property title.
   *
   * @param string $title
   *   The property title.
   *
   * @return \Drupal\philasales\PropertyInterface
   *   The called property entity.
   */
  public function setTitle($title);

  /**
   * Gets the property creation timestamp.
   *
   * @return int
   *   Creation timestamp of the property.
   */
  public function getCreatedTime();

  /**
   * Sets the property creation timestamp.
   *
   * @param int $timestamp
   *   The property creation timestamp.
   *
   * @return \Drupal\philasales\PropertyInterface
   *   The called property entity.
   */
  public function setCreatedTime($timestamp);

}
