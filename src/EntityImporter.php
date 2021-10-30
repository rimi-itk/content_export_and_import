<?php

namespace Drupal\entity_export_and_import;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity importer.
 */
class EntityImporter implements EntityImporterInterface {

  /**
   * {@inheritdoc}
   */
  public function importEntity(EntityInterface $entity): EntityInterface {
    $entity
      ->enforceIsNew()
      ->save();

    return $entity;
  }

}
