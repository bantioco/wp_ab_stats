<?php

class Wpabstats
{

	private static $initiated = false;

    public static function init() 
    {
        if ( ! self::$initiated ) 
        {
			self::initHooks();
		}
    }
    
    /**
	 * Initializes WordPress hooks
	 */
    private static function initHooks() 
    {
		self::$initiated = true;

		add_action( 'admin_enqueue_scripts', [ 'Wpabstats', 'wpAbStatsAddAdminAssets' ], 10, 2 );
	}

	public static function pluginActivation()
	{
		self::wpabstatsCreateDb();
		return;
	}

	public static function pluginDesactivation()
	{
		return;
	}

	/**
	 * Proper way to enqueue scripts and styles.
	 */
	public static function wpAbStatsAddAdminAssets() 
	{
		wp_enqueue_style( 'flags-wpabstats', plugin_dir_url( __FILE__ ).'assets/src/plugins/flag-icon-css-master/css/flag-icon.min.css' );

		wp_enqueue_style( 'style-wpabstats', plugin_dir_url( __FILE__ ).'dist/wpabstats.css' );
		wp_enqueue_script( 'script-wpabstats', plugin_dir_url( __FILE__ ).'dist/wpabstats.pack.js', [ 'jquery' ], '1.0.0', true );
		// pass Ajax Url to wpabstats.pack.js
		wp_localize_script('script-wpabstats', 'ajaxurl', admin_url( 'admin-ajax.php' ) );

		wp_enqueue_script( 'script-wpabstatschart', plugin_dir_url( __FILE__ ).'assets/src/plugins/Chart.min.js', [], '1.0.0', true );
	}


	public static function wpabstatsCreateDb()
	{
		global $wpdb;

		$charset_collate 	= $wpdb->get_charset_collate();
		$table_name 		= $wpdb->prefix . 'abstats';
	
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			post_id int(11) NOT NULL,
			meta_value varchar(255) NOT NULL,
			ip varchar(255) NULL,
			country varchar(255) NULL,
			country_code varchar(255) NULL,
			city varchar(255) NULL,
			continent varchar(255) NULL,
			continent_code varchar(255) NULL,
			browser varchar(255) NULL,
			date_log varchar(255) NULL,
			created_at varchar(255) NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}