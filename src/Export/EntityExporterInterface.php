<?php

namespace Drupal\content_export_and_import\Export;

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
