<?php

namespace Drupal\content_export_and_import\Export;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageException;

/**
 * Entity importer.
 */
class EntityImporter {

  /**
   * Import an entity.
   */
  public function importEntity(EntityInterface $entity) {
    try {
      $entity
        ->enforceIsNew()
        ->save();

      return TRUE;
    }
    catch (EntityStorageException $exception) {
      return FALSE;
    }
  }

}
