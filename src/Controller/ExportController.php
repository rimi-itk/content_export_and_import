<?php

namespace Drupal\entity_export_and_import\Controller;

use Drupal\entity_export_and_import\EntityExporterInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Export controller.
 */
class ExportController extends ControllerBase {

  /**
   * The entity exporter.
   *
   * @var \Drupal\entity_export_and_import\EntityExporterInterface
   */
  private $entityExporter;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityExporterInterface $entityExporter) {
    $this->entityExporter = $entityExporter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_export_and_import.entity_exporter'),
    );
  }

  /**
   * Index action.
   */
  public function index() {
    return [
      '#theme' => 'entity_export_and_import_index',
      '#exports' => $this->entityExporter->getExports(),
    ];
  }

}
