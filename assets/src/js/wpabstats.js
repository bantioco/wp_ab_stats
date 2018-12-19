import "../scss/wpabstats.scss";

"use-strict";

jQuery(document).ready(function($) {

    window.$d = $(document);

    if( $('a.current[href="index.php"]').is(':visible') || $('a.current[href="admin.php?page=wpabstats-setting').is(':visible') ){

        $('#welcome-panel').after( $('#custom-id').show());

        let modStatsGet = require('./modules/modStatsGet.js');

        let modStatsHtml = require('./modules/modStatsHtml.js');

        modStatsGet.wpAbStatsPageGet( function( result ){

            modStatsHtml.wpAbStatsPageHtml( result );
        });

        modStatsGet.wpAbStatsVisitorGet( 1, 0, 20,  function( result ){

            modStatsHtml.wpAbStatsVisitorHtml( result );
        });

        modStatsGet.wpAbStatsPostGet( function( result ){

            modStatsHtml.wpAbStatsPostHtml( result );
        });

        modStatsGet.wpAbStatsBrowserCount( function( result ){

            modStatsHtml.wpAbStatsVisitorCountHtml( result );
        })
    }
});