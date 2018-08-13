<?php

namespace Drupal\philasales\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\philasales\Entity\Division;
use Drupal\philasales\Entity\Property;
use Drupal\philasales\Entity\Ward;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class PhilasalesCommands extends DrushCommands {

  /**
   * Import ward and division data.
   *
   * @command philasales:import-wards-divs
   */
  public function importWardsDivisions() {
    $this->cleanDatabase();
    $this->importWards();
    $this->importDivisions();
  }

  /**
   * Import ward and division data.
   *
   * @command philasales:clean-wards-divs
   */
  public function cleanDatabase() {
    $wardStorage = \Drupal::entityTypeManager()->getStorage('ward');
    $wardStorage->delete($wardStorage->loadMultiple());

    $divStorage = \Drupal::entityTypeManager()->getStorage('division');
    $divStorage->delete($divStorage->loadMultiple());
  }

  /**
   * Import ward and division data.
   *
   * @command philasales:import-wards
   */
  public function importWards() {
    $file = 'modules/custom/philasales/Political_Wards.geojson';
    $contents = json_decode(file_get_contents($file));
    foreach ($contents->features as $feature) {
      Ward::create([
        'id' => $feature->properties->WARD_NUM,
        'ward' => $feature->properties->WARD_NUM,
        'geodata' => json_encode($feature->geometry)
      ])->save();
    }
  }

  /**
   * Import ward and division data.
   *
   * @command philasales:import-divisions
   */
  public function importDivisions() {
    $file = 'modules/custom/philasales/Political_Divisions.geojson';
    $content = json_decode(file_get_contents($file));
    foreach ($content->features as $feature) {
      Division::create([
        'id' => $feature->properties->DIVISION_NUM,
        'division' => $feature->properties->SHORT_DIV_NUM,
        'ward' => substr($feature->properties->DIVISION_NUM, 0, 2),
        'geodata' => json_encode($feature->geometry)
      ])->save();
    }
  }

  /**
   * @command philasales:import-properties
   */
  public function importProperties() {
    $storage = \Drupal::entityTypeManager()->getStorage('property');
    $storage->delete($storage->loadMultiple());

    $file = 'modules/custom/philasales/sales-2018-08-13.json';
    $content = json_decode(file_get_contents($file));
    $i = 0;
    foreach ($content->rows as $row) {
      $values = get_object_vars($row);
      foreach ($values as &$value) {
        $value = trim($value);
      }
      $values += [
        'id' => $row->parcel_number,
        'geofield' => \Drupal::service('geofield.wkt_generator')->wktBuildPoint([$values['lon'], $values['lat']]),
      ];
      Property::create($values)->save();
      if ($i++ > 20) {
        break;
      }
    }
  }

}
