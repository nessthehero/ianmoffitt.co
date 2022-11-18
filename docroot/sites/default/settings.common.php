<?php

// @codingStandardsIgnoreFile

	/**
	 * Salt for one-time login links, cancel links, form tokens, etc.
	 *
	 * This variable will be set to a random value by the installer. All one-time
	 * login links will be invalidated if the value is changed. Note that if your
	 * site is deployed on a cluster of web servers, you must ensure that this
	 * variable has the same value on each server.
	 *
	 * For enhanced security, you may set this variable to the contents of a file
	 * outside your document root; you should also ensure that this file is not
	 * stored with backups of your database.
	 *
	 * Example:
	 * @code
	 *   $settings['hash_salt'] = file_get_contents('/home/example/salt.txt');
	 * @endcode
	 */
	$settings['hash_salt'] = '1AjTlNorc4-5WUnqeU3com_-H4-A9BSJxC4d67qVJeEjfq9-Zstb1R_kG7fxOfkCglB7JnMp7g';

	/**
	 * Access control for update.php script.
	 *
	 * If you are updating your Drupal installation using the update.php script but
	 * are not logged in using either an account with the "Administer software
	 * updates" permission or the site maintenance account (the account that was
	 * created during installation), you will need to modify the access check
	 * statement below. Change the FALSE to a TRUE to disable the access check.
	 * After finishing the upgrade, be sure to open this file again and change the
	 * TRUE back to a FALSE!
	 */
	$settings['update_free_access'] = false;

	/**
	 * Fast 404 pages:
	 *
	 * Drupal can generate fully themed 404 pages. However, some of these responses
	 * are for images or other resource files that are not displayed to the user.
	 * This can waste bandwidth, and also generate server load.
	 *
	 * The options below return a simple, fast 404 page for URLs matching a
	 * specific pattern:
	 * - $config['system.performance']['fast_404']['exclude_paths']: A regular
	 *   expression to match paths to exclude, such as images generated by image
	 *   styles, or dynamically-resized images. The default pattern provided below
	 *   also excludes the private file system. If you need to add more paths, you
	 *   can add '|path' to the expression.
	 * - $config['system.performance']['fast_404']['paths']: A regular expression to
	 *   match paths that should return a simple 404 page, rather than the fully
	 *   themed 404 page. If you don't have any aliases ending in htm or html you
	 *   can add '|s?html?' to the expression.
	 * - $config['system.performance']['fast_404']['html']: The html to return for
	 *   simple 404 pages.
	 *
	 * Remove the leading hash signs if you would like to alter this functionality.
	 */
	$config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
	$config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
	$config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

	/**
	 * Load services definition file.
	 */
	$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

	/**
	 * Trusted host configuration.
	 *
	 * Drupal core can use the Symfony trusted host mechanism to prevent HTTP Host
	 * header spoofing.
	 *
	 * To enable the trusted host mechanism, you enable your allowable hosts
	 * in $settings['trusted_host_patterns']. This should be an array of regular
	 * expression patterns, without delimiters, representing the hosts you would
	 * like to allow.
	 *
	 * For example:
	 * @code
	 * $settings['trusted_host_patterns'] = [
	 *   '^www\.example\.com$',
	 * ];
	 * @endcode
	 * will allow the site to only run from www.example.com.
	 *
	 * If you are running multisite, or if you are running your site from
	 * different domain names (eg, you don't redirect http://www.example.com to
	 * http://example.com), you should specify all of the host patterns that are
	 * allowed by your site.
	 *
	 * For example:
	 * @code
	 * $settings['trusted_host_patterns'] = [
	 *   '^example\.com$',
	 *   '^.+\.example\.com$',
	 *   '^example\.org$',
	 *   '^.+\.example\.org$',
	 * ];
	 * @endcode
	 * will allow the site to run off of all variants of example.com and
	 * example.org, with all subdomains included.
	 */

	$settings['trusted_host_patterns'] = array(
		'^ianmoffitt\.dev$',
		'^.+\.ianmoffitt\.dev$',
		'^www\.ianmoffitt\.dev$',
	);

	/**
	 * The default list of directories that will be ignored by Drupal's file API.
	 *
	 * By default ignore node_modules and bower_components folders to avoid issues
	 * with common frontend tools and recursive scanning of directories looking for
	 * extensions.
	 *
	 * @see \Drupal\Core\File\FileSystemInterface::scanDirectory()
	 * @see \Drupal\Core\Extension\ExtensionDiscovery::scanDirectory()
	 */
	$settings['file_scan_ignore_directories'] = [
		'node_modules',
		'bower_components',
	];

	/**
	 * The default number of entities to update in a batch process.
	 *
	 * This is used by update and post-update functions that need to go through and
	 * change all the entities on a site, so it is useful to increase this number
	 * if your hosting configuration (i.e. RAM allocation, CPU speed) allows for a
	 * larger number of entities to be processed in a single batch run.
	 */
	$settings['entity_update_batch_size'] = 50;

	/**
	 * Entity update backup.
	 *
	 * This is used to inform the entity storage handler that the backup tables as
	 * well as the original entity type and field storage definitions should be
	 * retained after a successful entity update process.
	 */
	$settings['entity_update_backup'] = true;

	/**
	 * Node migration type.
	 *
	 * This is used to force the migration system to use the classic node migrations
	 * instead of the default complete node migrations. The migration system will
	 * use the classic node migration only if there are existing migrate_map tables
	 * for the classic node migrations and they contain data. These tables may not
	 * exist if you are developing custom migrations and do not want to use the
	 * complete node migrations. Set this to TRUE to force the use of the classic
	 * node migrations.
	 */
	$settings['migrate_node_migrate_type_classic'] = false;

	/**
	 * Load local development override configuration, if available.
	 *
	 * Create a settings.local.php file to override variables on secondary (staging,
	 * development, etc.) installations of this site.
	 *
	 * Typical uses of settings.local.php include:
	 * - Disabling caching.
	 * - Disabling JavaScript/CSS compression.
	 * - Rerouting outgoing emails.
	 *
	 * Keep this code block at the end of this file to take full effect.
	 */

	$settings['config_sync_directory'] = '../config';

	// Settings specific to Lando local development environments.
	if (getenv('LANDO')) {
		include __DIR__ . "/settings.lando.php";
	}

	if (isset($_ENV['IANMOFFITT_ENV']) && $_ENV['IANMOFFITT_ENV'] === 'production') {
		include __DIR__ . "/settings.remote.php";
	}

