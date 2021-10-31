# Entity export and import

## Installation

```sh
composer require drupal/entity_export_and_import
vendor/bin/drush pm:enable entity_export_and_import
```

## Development

We use a lazy services which requires generating prozy classes (cf.
<https://www.webomelette.com/lazy-loaded-services-drupal-8>).

**Note**: A full Drupal installation with this module enabled is needed to
generate the proxy classes (see
[rimi-itk/drupal_entity_export_and_import-test](https://github.com/rimi-itk/drupal_entity_export_and_import-test)
for an example).

Run the following commands to update the proxy classes:

```sh
(cd web && php core/scripts/generate-proxy-class.php 'Drupal\entity_export_and_import\EntityExporter' $(../vendor/bin/drush php:eval "echo drupal_get_path('module', 'entity_export_and_import')")/src)
(cd web && php core/scripts/generate-proxy-class.php 'Drupal\entity_export_and_import\EntityImporter' $(../vendor/bin/drush php:eval "echo drupal_get_path('module', 'entity_export_and_import')")/src)
```

## Coding standards

```sh
composer install
composer coding-standards-check
composer coding-standards-apply
```
