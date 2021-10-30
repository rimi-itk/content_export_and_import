<?php

namespace Drupal\content_export_and_import\Export;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Entity exporter.
 */
class EntityExporter implements EntityExporterInterface {
  /**
   * The serializer.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  private $serializer;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  private $fileSystem;

  /**
   * {@inheritdoc}
   */
  public function __construct(SerializerInterface $serializer, FileSystemInterface $fileSystem) {
    $this->serializer = $serializer;
    $this->fileSystem = $fileSystem;
  }

  /**
   * {@inheritdoc}
   */
  public function exportEntity(EntityInterface $entity, $preview = TRUE) {
    $format = 'json';
    $data = $this->serializer->serialize($entity, $format);
    if ($preview) {
      return json_decode($data, TRUE);
    }

    $filename = $this->getExportFilename($entity->getEntityTypeId(), $entity->id(), $format);
    $directory = dirname($filename);
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);
    $this->fileSystem->saveData($data, $filename, FileSystemInterface::EXISTS_REPLACE);

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getExports(): array {
    $files = $this->fileSystem->scanDirectory($this->getExportDirectory(), '/\.json$/');

    return array_map(static function (object $info) {
      return [
        'entity_type' => basename(dirname($info->uri)),
        'entity_id' => $info->name,
      ];
    }, $files);
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity(string $entityType, string $entityId, string $format = 'json'): ?EntityInterface {
    $filename = $this->getExportFilename($entityType, $entityId, $format);

    if (!file_exists($filename)) {
      return NULL;
    }

    $data = file_get_contents($filename);

    return $this->serializer->deserialize($data, Node::class, $format);
  }

  /**
   * Get export filename.
   */
  private function getExportFilename(string $entityType, string $entityId, string $format): string {
    return $this->getExportDirectory() . '/' . $entityType . '/' . $entityId . '.' . $format;
  }

  /**
   * Get export directory.
   */
  private function getExportDirectory(): string {
    return 'public://content_export_and_import';
  }

}
