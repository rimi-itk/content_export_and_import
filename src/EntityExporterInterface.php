<?php

namespace Drupal\entity_export_and_import;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity exporter interface.
 */
interface EntityExporterInterface {

  /**
   * Export entity.
   */
  public function exportEntity(EntityInterface $entity, $preview = TRUE);

  /**
   * Get exports.
   */
  public function getExports(): array;

  /**
   * Get entity.
   */
  public function getEntity(string $entityType, string $entityId, string $format = 'json'): ?EntityInterface;

}
