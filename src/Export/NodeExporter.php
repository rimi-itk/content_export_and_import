<?php

namespace Drupal\content_export_and_import\Export;

use Drupal\node\NodeInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Node exporter.
 */
class NodeExporter {
  /**
   * The serializer.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  private $serializer;

  /**
   * Constructor.
   */
  public function __construct(SerializerInterface $serializer) {
    $this->serializer = $serializer;
  }

  /**
   * Export a node.
   */
  public function exportNode(NodeInterface $node, $preview = TRUE): array {
    return json_decode($this->serializer->serialize($node, 'json'), TRUE);
  }

}
