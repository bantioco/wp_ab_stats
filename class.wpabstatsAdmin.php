<?php

class wpabstatsAdmin
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
        add_action( 'admin_menu', [ 'wpabstatsAdmin', 'wpabstats_add_menu_page'] );
    }

    public static function wpabstats_add_menu_page()
    {
        add_menu_page( 
            'wpabstats', 
            'wpabstats', 
            'manage_options', 
            'wpabstats-setting', 
            ['wpabstatsAdmin', 'wpabstats_add_menu_page_html'], 
            'dashicons-chart-bar', 
            60 
        );
    }

    public static function wpabstats_add_menu_page_html()
    {

        self::wpabstats_admin_html_save();

        $getIsActive    = get_option('_wpabstats_active');
        $isActive       = "";

        if( $getIsActive === "on" ) $isActive = 'checked="checked"';

        ?>
            <h1>WP AB Stats</h1>
            <form action="" method="POST">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                Activation
                            </th>
                            <td>
                                <label for="wpabstats_actvive"><input type="checkbox" <?php echo $isActive;?> id="wpabstats_active" name="wpabstats_active">Activer les statistiques sur le dashboard</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="wpabstats_setting_post" value="1">
                <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications" type="submit"></p>
            </form>

            <div id="custom-id" class="welcome-panel wpabstats_stats" style="display: none;">
                <div class="welcome-panel-content">
                    <h2>WP AB STATS</h2>

                    <div class="wpabstats_charts">

                        <div class="wpabstats_flex_container">
                            <div><label for="wpAbStatsChartPage_widget"><input id="wpAbStatsChartPage_widget" type="checkbox">Activer le widget</label></div>
                        </div>
                        <div class="wpabstats_flex_container">
                            <div><label for="wpabstats_visitor_widget"><input id="wpabstats_visitor_widget" type="checkbox">Activer le widget</label></div>
                        </div>

                        <div class="wpabstats_flex_container">

                            <canvas id="wpAbStatsChartPage"></canvas>

                        </div>

                        <div class="wpabstats_flex_container">
                            
                            <ul class="wpabstats_visitor_container"></ul>
                            <ul class="wpabstats_visitor_paginate"></ul>
                            
                        </div>

                    </div>

                </div>
            </div>

        <?php
    }

    public static function wpabstats_admin_html_save()
    {
        if( isset( $_POST['wpabstats_setting_post'] ) )
        {
            delete_option('_wpabstats_active');

            $isActive = "off";

            if( isset( $_POST['wpabstats_active'] ) ) 
            {
                $isActive = $_POST['wpabstats_active'];
            }

            self::wpabstatsIsActive( $isActive );
        }
    }

    public static function wpabstatsIsActive( $value )
    {
        if( $value === "on" ) add_option( '_wpabstats_active', 'on' );

        return;
    }
}