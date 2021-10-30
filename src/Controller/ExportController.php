<?php

namespace Drupal\entity_export_and_import\Controller;

use Drupal\entity_export_and_import\EntityExporterInterface;
use Drupal\entity_export_and_import\EntityImporterInterface;
use Drupal\entity_export_and_import\Form\ExportForm;
use Drupal\entity_export_and_import\Form\ImportForm;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Export controller.
 */
class ExportController extends ControllerBase {
  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  private $requestStack;

  /**
   * The entity exporter.
   *
   * @var \Drupal\entity_export_and_import\EntityExporterInterface
   */
  private $entityExporter;

  /**
   * The entity importer.
   *
   * @var \Drupal\entity_export_and_import\EntityImporterInterface
   */
  private $entityImporter;

  /**
   * {@inheritdoc}
   */
  public function __construct(RequestStack $requestStack, EntityExporterInterface $entityExporter, EntityImporterInterface $entityImporter) {
    $this->requestStack = $requestStack;
    $this->entityExporter = $entityExporter;
    $this->entityImporter = $entityImporter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack'),
      $container->get('entity_export_and_import.entity_exporter'),
      $container->get('entity_export_and_import.entity_importer'),
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

  /**
   * Export action.
   */
  public function export($entity_type, $entity_id) {
    $entity = $this->getEntity($entity_type, $entity_id);
    $request = $this->requestStack->getCurrentRequest();

    if ('POST' === $request->getMethod()) {
      $status = $this->entityExporter->exportEntity($entity, FALSE);

      return $this->redirect('entity_export_and_import.export', [
        'entity_type' => $entity->getEntityTypeId(),
        'entity_id' => $entity->id(),
        'status' => json_encode($status),
      ]);
    }
    elseif (NULL !== ($status = $request->get('status'))) {
      return TRUE === json_decode($status)
        ?
        [
          '#theme' => 'status_messages',
          '#message_list' => [
            'status' => [
              $this->t('%label successfully exported', ['%label' => $entity->label()]),
            ],
          ],
        ]
        :
        [
          '#theme' => 'status_messages',
          '#message_list' => [
            'error' => [
              $this->t('Error exporting %label', ['%label' => $entity->label()]),
            ],
          ],
        ];
    }
    else {
      return $this->formBuilder()->getForm((new ExportForm($this->entityExporter))->setEntity($entity));
    }
  }

  /**
   * Export title.
   */
  public function exportTitle($entity_type, $entity_id) {
    $entity = $this->getEntity($entity_type, $entity_id);

    return $this->t('Export %label (#%id)?', [
      '%label' => $entity->label(),
      '%id' => $entity->id(),
    ]);
  }

  /**
   * Export access.
   */
  public function exportAccess($entity_type, $entity_id) {
    $entity = $this->getEntity($entity_type, $entity_id);

    return $entity->access('edit') ? AccessResult::allowed() : AccessResult::forbidden();
  }

  /**
   * Import action.
   */
  public function import($entity_type, $entity_id) {
    $entity = $this->entityExporter->getEntity($entity_type, $entity_id);
    $request = $this->requestStack->getCurrentRequest();

    if ('POST' === $request->getMethod()) {
      $status = $this->entityImporter->importEntity($entity);

      if (TRUE === $status) {
        $this->messenger()->addStatus($this->t('%label successfully imported', ['%label' => $entity->label()]));

        return $this->redirect('entity.' . $entity->getEntityTypeId() . '.edit_form', [$entity->getEntityTypeId() => $entity->id()]);
      }
      else {
        $this->messenger()->addError($this->t('Error importing %label', ['%label' => $entity->label()]));

        return $this->redirect('entity_export_and_import.import', [
          'entity_type' => $entity->getEntityTypeId(),
          'entity_id' => $entity->id(),
          'status' => json_encode($status),
        ]);

      }
    }
    else {
      return $this->formBuilder()->getForm((new ImportForm($this->entityExporter))->setEntity($entity));
    }
  }

  /**
   * Import title.
   */
  public function importTitle($entity_type, $entity_id) {
    $entity = $this->entityExporter->getEntity($entity_type, $entity_id);

    return $this->t('Import %label (#%id)?', [
      '%label' => $entity->label(),
      '%id' => $entity->id(),
    ]);
  }

  /**
   * Import access.
   */
  public function importAccess($entity_type, $entity_id) {
    $entity = $this->entityExporter->getEntity($entity_type, $entity_id);

    if (NULL === $entity) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }

  /**
   * Export node.
   */
  public function exportNode($node) {
    return $this->export('node', $node);
  }

  /**
   * Export node access.
   */
  public function exportNodeAccess($node) {
    return $this->exportAccess('node', $node);
  }

  /**
   * Export node title.
   */
  public function exportNodeTitle($node) {
    return $this->exportTitle('node', $node);
  }

  /**
   * Get entity.
   */
  private function getEntity($entity_type, $entity_id) {
    return $this->entityTypeManager()->getStorage($entity_type)->load($entity_id);
  }

}
