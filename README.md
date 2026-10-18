# PageBuilder

WordPress project with the BCTHEME theme and its integrated visual page builder.

## Development

- Theme: `wp-content/themes/BCTHEME`
- Builder: `wp-content/themes/BCTHEME/public/xt-pagebuilder`
- Builder documentation: `wp-content/themes/BCTHEME/public/xt-pagebuilder/Page-Builder-Docs/README.md`
- Theme modules: `wp-content/themes/BCTHEME/xtpb-template/modules`

## Installation and recovery

Use a PHP/MySQL WordPress environment compatible with the installed theme and plugins. Create a local `wp-config.php` from `wp-config-sample.php` and configure the database and unique authentication salts.

This repository contains code, not a full site backup. Restore the WordPress database and `wp-content/uploads` separately to recover pages, builder layouts, settings and uploaded media. Confirm active plugins and update site URLs when moving to another domain.

Machine-specific configuration, uploads, caches, database dumps and migration archives are excluded from Git. Keep private backups of these separately. Third-party code retains its original license.
