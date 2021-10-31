<?php

namespace Drupal\entity_export_and_import;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity importer interface.
 */
interface EntityImporterInterface {

  /**
   * Import entity.
   */
  public function importEntity(EntityInterface $entity): EntityInterface;

}
