<?php

namespace Drupal\content_export_and_import\Controller;

use Drupal\content_export_and_import\Export\NodeExporter;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Export controller.
 */
class ExportController extends ControllerBase {
  /**
   * The node exporter.
   *
   * @var \Drupal\content_export_and_import\Export\NodeExporter
   */
  private $nodeExporter;

  /**
   * Contructor.
   */
  public function __construct(NodeExporter $nodeExporter) {
    $this->nodeExporter = $nodeExporter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('content_export_and_import.node_exporter')
    );
  }

  /**
   * Export a node.
   */
  public function exportNode(NodeInterface $node) {
    return [
      '#type' => 'markup',
      '#markup' => json_encode($this->nodeExporter->exportNode($node), JSON_PRETTY_PRINT),
      '#prefix' => '<pre>',
      '#suffix' => '</pre>',
    ];
  }

}
