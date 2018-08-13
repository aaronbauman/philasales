<?php

namespace Drupal\philasales;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a division entity type.
 */
interface DivisionInterface extends ContentEntityInterface {

  /**
   * Gets the division title.
   *
   * @return string
   *   Title of the division.
   */
  public function getTitle();

  /**
   * Sets the division title.
   *
   * @param string $title
   *   The division title.
   *
   * @return \Drupal\philasales\DivisionInterface
   *   The called division entity.
   */
  public function setTitle($title);

}
