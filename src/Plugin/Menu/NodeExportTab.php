<?php

namespace Drupal\entity_export_and_import\Plugin\Menu;

use Drupal\Core\Menu\LocalTaskDefault;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Node export tab.
 */
class NodeExportTab extends LocalTaskDefault {

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    return [
      'entity_type' => 'node',
      'entity_id' => $route_match->getRawParameter('node'),
    ];
  }

}
