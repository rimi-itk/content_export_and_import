<?php

namespace Drupal\content_export_and_import\Form;

use Drupal\content_export_and_import\Export\EntityExporterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Export form.
 */
class ExportForm extends FormBase {
  /**
   * The entity exporter.
   *
   * @var \Drupal\content_export_and_import\Export\EntityExporterInterface
   */
  private $entityExporter;

  /**
   * The entity.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  private $entity;

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
      $container->get('content_export_and_import.entity_exporter')
    );
  }

  /**
   * Set entity.
   */
  public function setEntity(EntityInterface $entity) {
    $this->entity = $entity;

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'content_export_and_import_export';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['export_preview'] = [
      '#markup' => json_encode($this->entityExporter->exportEntity($this->entity, TRUE), JSON_PRETTY_PRINT),
      '#prefix' => '<pre class="export-preview"><code>',
      '#suffix' => '</code></pre>',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export'),
      '#button_type' => 'primary',
    ];

    $form['#attached']['library'][] = 'content_export_and_import/admin';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @todo Implement submitForm() method.
  }

}
