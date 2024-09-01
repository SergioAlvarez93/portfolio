<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://kaizencoders.com
 * @since      1.2
 *
 * @package    Update_Urls
 * @subpackage Update_Urls/includes
 */

namespace Kaizencoders\Update_Urls;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.2
 * @package    Update_Urls
 * @subpackage Update_Urls/includes
 * @author     KaizenCoders <hello@kaizencoders.com>
 */
class I18n {

	/**
	 * The domain specified for this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string    $domain    The domain identifier for this plugin.
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.2
	 */
	public function load_plugin_textdomain() {

		\load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( \plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @since    1.2
	 * @param    string    $domain    The domain that represents the locale of this plugin.
	 */
	public function set_domain( $domain ) {
		$this->domain = $domain;
	}

}
