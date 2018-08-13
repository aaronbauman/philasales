<?php

namespace Drupal\philasales;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a ward entity type.
 */
interface WardInterface extends ContentEntityInterface {

  /**
   * Gets the ward title.
   *
   * @return string
   *   Title of the ward.
   */
  public function getTitle();

  /**
   * Sets the ward title.
   *
   * @param string $title
   *   The ward title.
   *
   * @return \Drupal\philasales\WardInterface
   *   The called ward entity.
   */
  public function setTitle($title);

}
