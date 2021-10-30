<?php

namespace Drupal\entity_export_and_import\Form;

use Drupal\entity_export_and_import\EntityExporterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Import form.
 */
class ImportForm extends FormBase {
  /**
   * The entity exporter.
   *
   * @var \Drupal\entity_export_and_import\EntityExporterInterface
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
      $container->get('entity_export_and_import.entity_exporter')
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
    return 'entity_export_and_import_import';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
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
    // @todo Implement submitForm() method.
  }

}
