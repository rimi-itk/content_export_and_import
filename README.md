# Entity export and import

## Installation

```sh
composer require drupal/entity_export_and_import
vendor/bin/drush pm:enable entity_export_and_import
```

## Development

We use a lazy services which requires generating prozy classes (cf.
<https://www.webomelette.com/lazy-loaded-services-drupal-8>).

Run the following commands to update the proxy classes:

```sh
php «DRUPAL_ROOT»/web/core/scripts/generate-proxy-class.php 'Drupal\entity_export_and_import\EntityExporter' web/modules/custom/entity_export_and_import/src
```

## Coding standards

```sh
composer install
composer coding-standards-check
composer coding-standards-apply
```
