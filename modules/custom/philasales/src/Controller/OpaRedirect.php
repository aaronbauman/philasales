<?php

namespace Drupal\philasales\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;

class OpaRedirect extends ControllerBase {

  public function doRedirect($parcel_num) {
    $parcel_num = str_pad($parcel_num, 9, '0', STR_PAD_LEFT);
    return new TrustedRedirectResponse('https://property.phila.gov/?p=' . $parcel_num);
  }

}