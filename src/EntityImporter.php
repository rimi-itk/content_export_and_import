<?php

namespace Drupal\entity_export_and_import;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageException;

/**
 * Entity importer.
 */
class EntityImporter implements EntityImporterInterface {

  /**
   * {@inheritdoc}
   */
  public function importEntity(EntityInterface $entity): bool {
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
