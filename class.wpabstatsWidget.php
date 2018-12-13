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

        if( $isActive === "on" ) add_action( 'admin_footer', [ 'wpabstatsWidget', 'wpAbStatsAddDasboardWidget' ], 10, 2 );
        
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

}