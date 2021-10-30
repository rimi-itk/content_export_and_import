<?php

namespace Drupal\content_export_and_import\Form;

use Drupal\content_export_and_import\Export\EntityExporter;
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
   * @var \Drupal\content_export_and_import\Export\EntityExporter
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
  public function __construct(EntityExporter $entityExporter) {
    $this->entityExporter = $entityExporter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get(EntityExporter::class)
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
    return 'content_export_and_import_import';
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
