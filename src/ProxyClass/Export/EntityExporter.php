<?php
// phpcs:ignoreFile

/**
 * This file was generated via php core/scripts/generate-proxy-class.php 'Drupal\content_export_and_import\Export\EntityExporter' "modules/custom/content_export_and_import/src".
 */

namespace Drupal\content_export_and_import\ProxyClass\Export {

    /**
     * Provides a proxy class for \Drupal\content_export_and_import\Export\EntityExporter.
     *
     * @see \Drupal\Component\ProxyBuilder
     */
    class EntityExporter implements \Drupal\content_export_and_import\Export\EntityExporterInterface
    {

        use \Drupal\Core\DependencyInjection\DependencySerializationTrait;

        /**
         * The id of the original proxied service.
         *
         * @var string
         */
        protected $drupalProxyOriginalServiceId;

        /**
         * The real proxied service, after it was lazy loaded.
         *
         * @var \Drupal\content_export_and_import\Export\EntityExporter
         */
        protected $service;

        /**
         * The service container.
         *
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        protected $container;

        /**
         * Constructs a ProxyClass Drupal proxy object.
         *
         * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
         *   The container.
         * @param string $drupal_proxy_original_service_id
         *   The service ID of the original service.
         */
        public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container, $drupal_proxy_original_service_id)
        {
            $this->container = $container;
            $this->drupalProxyOriginalServiceId = $drupal_proxy_original_service_id;
        }

        /**
         * Lazy loads the real service from the container.
         *
         * @return object
         *   Returns the constructed real service.
         */
        protected function lazyLoadItself()
        {
            if (!isset($this->service)) {
                $this->service = $this->container->get($this->drupalProxyOriginalServiceId);
            }

            return $this->service;
        }

        /**
         * {@inheritdoc}
         */
        public function exportEntity(\Drupal\Core\Entity\EntityInterface $entity, $preview = true)
        {
            return $this->lazyLoadItself()->exportEntity($entity, $preview);
        }

        /**
         * {@inheritdoc}
         */
        public function getExports(): array
        {
            return $this->lazyLoadItself()->getExports();
        }

        /**
         * {@inheritdoc}
         */
        public function getEntity(string $entityType, string $entityId, string $format = 'json'): ?\Drupal\Core\Entity\EntityInterface
        {
            return $this->lazyLoadItself()->getEntity($entityType, $entityId, $format);
        }

    }

}
