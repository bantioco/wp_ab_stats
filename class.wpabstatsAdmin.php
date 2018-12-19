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
            [ 'wpabstatsAdmin', 'wpabstats_add_menu_page_html' ], 
            'dashicons-chart-bar', 
            60 
        );
    }

    /**
     * WPABSTATS - GLOBAL ACTIVE CHECK
     */
    public static function wpabstats_active_check()
    {
        $getIsActive    = get_option('_wpabstats_active');
        $isActive       = "";

        if( $getIsActive === "on" ) $isActive = 'checked="checked"';

        return $isActive;
    }


    /**
     * WPABSTATS - CHART PAGE ACTIVE CHECK
     */
    public static function wpabstats_chart_page_active_check()
    {
        $getChartPageIsActive   = get_option('_wpabstats_chart_page_widget');
        $isActive               = "";

        if( $getChartPageIsActive === "on" ) $isActive = 'checked="checked"';

        return $isActive;
    }

    /**
     * WPABSTATS - VISITOR ACTIVE CHECK
     */
    public static function wpabstats_visitor_view_active_check()
    {
        $getVisitorIsActive     = get_option('_wpabstats_visitor_container_widget');
        $isActive               = "";

        if( $getVisitorIsActive === "on" ) $isActive = 'checked="checked"';

        return $isActive;
    }

    /**
     * CHART ARTICLE ACTIVE CHECK
     */
    public static function wpabstats_chart_article_active_check()
    {
        $getChartArticleIsActive    = get_option('_wpabstats_chart_article_widget');
        $isActive                   = "";

        if( $getChartArticleIsActive === "on" ) $isActive = 'checked="checked"';

        return $isActive;
    }

    /**
     * CHART BROWSER ACTIVE CHECK
     */
    public static function wpabstats_browser_active_check()
    {
        $getChartArticleIsActive    = get_option('_wpabstats_browser_widget');
        $isActive                   = "";

        if( $getChartArticleIsActive === "on" ) $isActive = 'checked="checked"';

        return $isActive;
    }


    /**
     * WPABSTATS - Admin setting html
     */
    public static function wpabstats_add_menu_page_html()
    {
        self::wpabstats_admin_html_save();

        $isActive               = self::wpabstats_active_check();
        $ChartPageIsActive      = self::wpabstats_chart_page_active_check();
        $VisitorIsActive        = self::wpabstats_visitor_view_active_check();
        $ChartArticleIsActive   = self::wpabstats_chart_article_active_check();
        $BrowserIsActive        = self::wpabstats_browser_active_check();

        ?>
            <h1>WP AB Stats</h1>
            <form action="" method="POST">
                <!-- GLOBAL ACTIVATION -->
                <table class="form-table">
                    <tbody>
                        <tr>
                            <td>
                                <label class="container">Activer les statistiques sur le dashboard
                                    <input type="checkbox" <?php echo $isActive;?> id="wpabstats_active" name="wpabstats_active">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications" type="submit"></p>
            

                <div id="custom-id" class="welcome-panel wpabstats_stats" style="display: none;">

                    <div class="welcome-panel-content">

                        <div class="wpabstats_charts">

                            <!-- CHART PAGE ACTIVATION -->
                            <div class="wpabstats_checkbox_container">
                                <div>
                                    <label class="container">Activer le widget
                                        <input type="checkbox" <?php echo $ChartPageIsActive;?> id="wpabstats_chart_page_widget" name="wpabstats_chart_page_widget">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- VISITOR VIEW ACTIVATION -->
                            <div class="wpabstats_checkbox_container">
                                <div>
                                    <label class="container">Activer le widget
                                        <input type="checkbox" <?php echo $VisitorIsActive;?> id="wpabstats_visitor_container_widget" name="wpabstats_visitor_container_widget">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- CHART PAGE VIEW -->
                            <div class="wpabstats_flex_container"><canvas id="wpAbStatsChartPage"></canvas></div>

                            <!-- VISITOR  LOG VIEW -->
                            <div class="wpabstats_flex_container wpabstats_container_visitor">
                                
                                <ul class="wpabstats_visitor_container"></ul>
                                <ul class="wpabstats_visitor_paginate"></ul>
                                
                            </div>

                            <!-- CHART ARTICLE ACTIVATION -->
                            <div class="wpabstats_checkbox_container">
                                <div>
                                    <label class="container">Activer le widget
                                        <input type="checkbox" <?php echo $ChartArticleIsActive;?> id="wpabstats_chart_article_widget" name="wpabstats_chart_article_widget">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="wpabstats_checkbox_container">
                                <div>
                                    <label class="container">Activer le widget
                                        <input type="checkbox" id="wpabstats_browser_widget" <?php echo $BrowserIsActive;?> name="wpabstats_browser_widget">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- CHART ARTICLE VIEW -->
                            <div class="wpabstats_flex_container">
                                <canvas id="wpAbStatsChartArticle"></canvas>
                            </div>

                            <div class="wpabstats_flex_container">

                                <div class="wpabstats_browser_container">

                                    <div class="wpabstats_widget_title">Browsers</div>

                                    <div class="browser_flex_items"></div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

                <input type="hidden" name="wpabstats_setting_post" value="1">
            
            </form>

        <?php
    }


    /**
     * WPABSTATS - Admin setting save
     * @todo: refactor function
     */
    public static function wpabstats_admin_html_save()
    {
        if( isset( $_POST['wpabstats_setting_post'] ) )
        {
            /**
             * GLOBAL ACTIVATION
             */
            delete_option('_wpabstats_active');

            $isActive = "off";

            if( isset( $_POST['wpabstats_active'] ) ) $isActive = $_POST['wpabstats_active'];

            if( $isActive === "on" ) add_option( '_wpabstats_active', 'on' );

            /**
             * WIDGET CHART PAGE VIEW ON DASHBOARD
             */
            delete_option('_wpabstats_chart_page_widget');

            $isActive = "off";

            if( isset( $_POST['wpabstats_chart_page_widget'] ) ) $isActive = $_POST['wpabstats_chart_page_widget'];

            if( $isActive === "on" ) add_option( '_wpabstats_chart_page_widget', 'on' );


            /**
             * WIDGET VISITOR VIEW ON DASHBOARD
             */
            delete_option('_wpabstats_visitor_container_widget');

            $isActive = "off";

            if( isset( $_POST['wpabstats_visitor_container_widget'] ) ) $isActive = $_POST['wpabstats_visitor_container_widget'];

            if( $isActive === "on" ) add_option( '_wpabstats_visitor_container_widget', 'on' );

            /**
             * WIDGET CHART ARTICLE VIEW ON DASHBOARD
             */
            delete_option('_wpabstats_chart_article_widget');

            $isActive = "off";

            if( isset( $_POST['wpabstats_chart_article_widget'] ) ) $isActive = $_POST['wpabstats_chart_article_widget'];

            if( $isActive === "on" ) add_option( '_wpabstats_chart_article_widget', 'on' );


            /**
             * WIDGET BROWSER VIEW ON DASHBOARD
             */
            delete_option('_wpabstats_browser_widget');

            $isActive = "off";

            if( isset( $_POST['wpabstats_browser_widget'] ) ) $isActive = $_POST['wpabstats_browser_widget'];

            if( $isActive === "on" ) add_option( '_wpabstats_browser_widget', 'on' );
        }
    }
}