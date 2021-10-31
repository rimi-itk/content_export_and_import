<?php

namespace Drupal\entity_export_and_import\Form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\entity_export_and_import\EntityExporterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_export_and_import\EntityImporterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Import form.
 */
class ImportForm extends FormBase {
  use DependencySerializationTrait;

  /**
   * The entity exporter.
   *
   * @var \Drupal\entity_export_and_import\EntityExporterInterface
   */
  private EntityExporterInterface $entityExporter;

  /**
   * The entity importer.
   *
   * @var \Drupal\entity_export_and_import\EntityImporterInterface
   */
  private EntityImporterInterface $entityImporter;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityExporterInterface $entityExporter, EntityImporterInterface $entityImporter) {
    $this->entityExporter = $entityExporter;
    $this->entityImporter = $entityImporter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_export_and_import.entity_exporter'),
      $container->get('entity_export_and_import.entity_importer')
    );
  }

  /**
   * Route access.
   */
  public function access() {
    $entity = $this->loadEntity();

    return NULL !== $entity ? AccessResult::allowedIfHasPermission($this->currentUser(), 'entity_export_and_import import entity') : AccessResult::forbidden();
  }

  /**
   * Route title.
   */
  public function title() {
    $entity = $this->loadEntity();

    return $this->t('Import %label (#%id)?', [
      '%label' => $entity->label(),
      '%id' => $entity->id(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entity_export_and_import_import';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $entity = $this->loadEntity();
    if ($exception = $form_state->getTemporaryValue('exception')) {
      $form['message'] = [
        '#theme' => 'status_messages',
        '#message_list' => [
          'error' => [
            $this->t('Error importing %label', ['%label' => $entity->label()]),
            $exception->getMessage(),
          ],
        ],
      ];
    }

    $form['warning'] = [
      '#theme' => 'status_messages',
      '#message_list' => [
        'warning' => [
          $this->t('Importing %label will will destroy all existing entities with ids matching imported ids.', ['%label' => $entity->label()]),
        ],
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->loadEntity();
    try {
      $entity = $this->entityImporter->importEntity($entity);

      $this->messenger()->addStatus($this->t('%label successfully imported', ['%label' => $entity->label()]));

      $form_state->setRedirect(
        'entity.' . $entity->getEntityTypeId() . '.edit_form',
        [
          $entity->getEntityTypeId() => $entity->id(),
        ]
      );
    }
    catch (EntityStorageException $exception) {
      $form_state
        ->setTemporaryValue('exception', $exception)
        ->setRebuild();
    }
  }

  /**
   * Get entity.
   */
  private function loadEntity(): ?EntityInterface {
    $parameters = $this->getRouteMatch()->getParameters();
    $entity_type = $parameters->get('entity_type');
    $entity_id = $parameters->get('entity_id');

    return $this->entityExporter->loadEntity($entity_type, $entity_id);
  }

}
