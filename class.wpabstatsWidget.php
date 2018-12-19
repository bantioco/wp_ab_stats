<?php

class wpabstatsWidget
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
        
        $isActive    = get_option('_wpabstats_active');

        if( ($isActive === "on") && (get_option('_wpabstats_chart_page_widget') === "on") ) 
        {
            add_action( 'wp_dashboard_setup', [ 'wpabstatsWidget', 'wpabstatsPageDashboardWidget' ], 10, 2 );
        }

        if( ($isActive === "on") && (get_option('_wpabstats_chart_article_widget') === "on") ) 
        {
            add_action( 'wp_dashboard_setup', [ 'wpabstatsWidget', 'wpabstatsArticleDashboardWidget' ], 10, 2 );
        }

        if( ($isActive === "on") && (get_option('_wpabstats_visitor_container_widget') === "on") ) 
        {
            add_action( 'wp_dashboard_setup', [ 'wpabstatsWidget', 'wpabstatsVisitorDashboardWidget' ], 10, 2 );
        }

        if( ($isActive === "on") && (get_option('_wpabstats_browser_widget') === "on") ) 
        {
            add_action( 'wp_dashboard_setup', [ 'wpabstatsWidget', 'wpabstatsBrowserDashboardWidget' ], 10, 2 );
        }
        
    }

    public static function wpabstatsPageDashboardWidget() 
    {
        wp_add_dashboard_widget(
            'wpabstats_page_dashboard_widget',
            'WPABSTATS - Pages views',
            [ 'wpabstatsWidget', 'wpabstatsPageDashboardWidgetHtml' ]
        );	
    }
    
    public static function wpabstatsPageDashboardWidgetHtml() 
    {
        ?>
        <div class="wpabstats_charts_widget">

            <div class="wpabstats_widget_container">
                <canvas id="wpAbStatsChartPage"></canvas>
            </div>

        </div>
        <?php
    }

    public static function wpabstatsArticleDashboardWidget() 
    {
        wp_add_dashboard_widget(
            'wpabstats_article_dashboard_widget',
            'WPABSTATS - Articles views',
            [ 'wpabstatsWidget', 'wpabstatsArticleDashboardWidgetHtml' ]
        );	
    }
    
    public static function wpabstatsArticleDashboardWidgetHtml() 
    {
        ?>
        <div class="wpabstats_charts_widget">

            <div class="wpabstats_widget_container">
                <canvas id="wpAbStatsChartArticle"></canvas>
            </div>

        </div>
        <?php
    }


    public static function wpabstatsVisitorDashboardWidget() 
    {
        wp_add_dashboard_widget(
            'wpabstats_visitor_dashboard_widget',
            'WPABSTATS - Visitor logs',
            [ 'wpabstatsWidget', 'wpabstatsVisitorDashboardWidgetHtml' ]
        );	
    }
    
    public static function wpabstatsVisitorDashboardWidgetHtml() 
    {
        ?>
        <div class="wpabstats_charts_widget">

            <div class="wpabstats_widget_container wpabstats_container_visitor">

                <ul class="wpabstats_visitor_container"></ul>
                <ul class="wpabstats_visitor_paginate"></ul>

            </div>

        </div>
        <?php
    }

    public static function wpabstatsBrowserDashboardWidget() 
    {
        wp_add_dashboard_widget(
            'wpabstats_browser_dashboard_widget',
            'WPABSTATS - Browsers views',
            [ 'wpabstatsWidget', 'wpabstatsBrowserDashboardWidgetHtml' ]
        );	
    }
    
    public static function wpabstatsBrowserDashboardWidgetHtml() 
    {
        ?>

  

        <div class="wpabstats_browser_container">

            <div class="wpabstats_widget_title">Browsers</div>

            <div class="browser_flex_items"></div>

        </div>

        

        <?php
    }

    
    public static function wpAbStatsAddDasboardWidget()
	{
		// Bail if not viewing the main dashboard page
		if ( get_current_screen()->base !== 'dashboard' ) return;
        ?>

        <div id="custom-id" class="welcome-panel wpabstats_stats" style="display: none;">
            <div class="welcome-panel-content">
                <h2>WP AB STATS</h2>

                <div class="wpabstats_charts">

                    <div class="wpabstats_flex_container">
                        <canvas id="wpAbStatsChartPage_"></canvas>
                    </div>

                    <div class="wpabstats_flex_container wpabstats_flex_container_visitor">

                        <ul class="wpabstats_visitor_container_"></ul>

                        <ul class="wpabstats_visitor_paginate_"></ul>
                        
                    </div>


                    <div class="wpabstats_flex_container">
                        <canvas id="wpAbStatsChartPost_"></canvas>
                    </div>

                    <div class="wpabstats_flex_container">
                        
                    </div>

                </div>

            </div>
        </div>

        <?php 
	}

}