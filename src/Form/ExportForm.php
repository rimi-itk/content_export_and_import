<?php

namespace Drupal\entity_export_and_import\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Url;
use Drupal\entity_export_and_import\EntityExporterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Export form.
 */
class ExportForm extends FormBase {
  /**
   * The entity exporter.
   *
   * @var \Drupal\entity_export_and_import\EntityExporterInterface
   */
  private EntityExporterInterface $entityExporter;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, EntityExporterInterface $entityExporter) {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityExporter = $entityExporter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_export_and_import.entity_exporter')
    );
  }

  /**
   * Route access.
   */
  public function access() {
    switch ($this->getRouteMatch()->getRouteName()) {
      case 'entity.node.canonical':
        $entity = $this->getRouteMatch()->getParameter('node');
        break;

      case 'entity_export_and_import.export':
        $entity = $this->loadEntity();
        break;
    }

    return isset($entity) ? $entity->access('edit', NULL, TRUE) : AccessResult::forbidden();
  }

  /**
   * Route title.
   */
  public function title() {
    $entity = $this->loadEntity();

    return $this->t('Export %label (#%id)?', [
      '%label' => $entity->label(),
      '%id' => $entity->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entity_export_and_import_export';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $entity = $this->loadEntity();

    if ($form_state->getTemporaryValue('is_exported')) {
      $form['message'] = [
        '#theme' => 'status_messages',
        '#message_list' => [
          'status' => [
            $this->t('%label successfully exported.', ['%label' => $entity->label()]),
          ],
        ],
      ];
      $form['all_exports'] = [
        '#type' => 'link',
        '#title' => $this->t('See all exports'),
        '#url' => Url::fromRoute('entity_export_and_import.index'),
      ];
    }
    else {
      $form['export_preview'] = [
        '#markup' => json_encode($this->entityExporter->exportEntity($entity, TRUE), JSON_PRETTY_PRINT),
        '#prefix' => '<pre class="export-preview"><code>',
        '#suffix' => '</code></pre>',
      ];

      $form['actions']['#type'] = 'actions';
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Export'),
        '#button_type' => 'primary',
      ];
    }

    $form['#attached']['library'][] = 'entity_export_and_import/admin';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->loadEntity();
    $status = $this->entityExporter->exportEntity($entity, FALSE);

    if (TRUE === $status) {
      $form_state
        ->setTemporaryValue('is_exported', TRUE)
        ->setRebuild();
    }
    else {
      $this->messenger()->addError($this->t('Error exporting %label', ['%label' => $entity->label()]));
    }
  }

  /**
   * Get entity.
   */
  private function loadEntity(): EntityInterface {
    $parameters = $this->getRouteMatch()->getParameters();
    $entity_type = $parameters->get('entity_type');
    $entity_id = $parameters->get('entity_id');

    $entity = $this->entityTypeManager->getStorage($entity_type)->load($entity_id);

    if (NULL === $entity) {
      throw new BadRequestHttpException('Cannot get entity');
    }
    return $entity;
  }

}
