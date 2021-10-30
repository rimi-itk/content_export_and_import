<?php

namespace Drupal\content_export_and_import\Export;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity importer interface.
 */
interface EntityImporterInterface {

  /**
   * Import an entity.
   */
  public function importEntity(EntityInterface $entity): bool;

}
