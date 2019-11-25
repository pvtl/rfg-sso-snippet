<?php
/*
Plugin Name: RFG SSO Snippet
Plugin URI: https://www.pivotalagency.com.au/
Description: Inserts RFG Single-Sign-On (SSO) snippet into wp-login.php programmatically.
Version: 1.0.1
Author: Pivotal Agency
Author URI: https://www.pivotalagency.com.au/
Text Domain: rfg-sso-snippet
License: GPL3+
*/

// Only load class if it hasn't already been loaded
if ( !class_exists( 'RfgSsoSnippet' ) ) {

	// RFG SSO Code Snippet - All the magic happens here!
	class RfgSsoSnippet {

		static $didInit = false;

		public function __construct() {
			if (!self::$didInit) {
				$this->init();
				self::$didInit = true;
			}
		}

		private function init() {
			// Internationalization
			load_plugin_textdomain( 'rfg-sso-snippet', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			// Add Actions
			add_action( 'login_enqueue_scripts', array( $this, 'admin_enqueue_scripts_styles' ) ); // Enqueue styles on relevant page
			add_action( 'login_form', array( $this, 'output_snippet' ) ); // Code output
		}


		/**
		 * Adds JS/CSS to admin login screen
		 */
		public function admin_enqueue_scripts_styles() {
            wp_register_style( 'rfg_sso_snippets_css', plugins_url( '/css/style.css', __FILE__), array(), null );
            wp_enqueue_style( 'rfg_sso_snippets_css' );
		}


		/**
		 * Outputs the actual SSO snippet
		 */
        public function output_snippet() {
            ?>
                <div class="sso-container">
                    <?php if (!empty($_GET['saml-error'])) { ?>
                        <p class="sso-error"><?php echo $_GET['saml-error'];?></p>
                    <?php } ?>
                    <a href="?option=saml_user_login&redirect_to=/wp-admin" class="button button-primary button-large sso-button">
                        RFG Login
                    </a>
                </div>
            <?php
        }
	}
}

new RfgSsoSnippet();
