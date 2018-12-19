let modStatsGet = {

    wpAbStatsPageGet: ( callback )=> {

        jQuery.post(
            ajaxurl,
            {
                'action': 'wpAbStatsPageGet'
            },
            function( result ){

                return callback( result );
            }
        );
    },

    wpAbStatsBrowserCount: ( callback )=> {

        jQuery.post(
            ajaxurl,
            {
                'action': 'wpAbStatsBrowserCountGet'
            },
            function( result ){

                return callback( result );
            }
        );
    },

    wpAbStatsPostGet: ( callback )=> {

        jQuery.post(
            ajaxurl,
            {
                'action': 'wpAbStatsPostGet'
            },
            function( result ){

                return callback( result );
            }
        );
    },

    wpAbStatsVisitorGet: ( page=1, offset=1, limit=20, callback )=> {

        jQuery.post(
            ajaxurl,
            {
                'action': 'wpAbStatsVisitorGet',
                'params': {
                    page: page,
                    offset: offset,
                    limit: limit
                }
            },
            function( result ){

                console.log( result );

                return callback( result );
            }
        );

    },

    wpAbStatsVisitorPag: ()=> {

        let $ = jQuery;

        $d.off('click', 'a.paginate_link').on('click', 'a.paginate_link', function( e ){

            e.preventDefault();

            let $this = $(this);

            if( $this.hasClass('.paginate_active') ) return;

            let page = $this.attr('data-page');
            let offset = $this.attr('data-offset');
            let limit = $this.attr('data-limit');

            let modStatsHtml = require('./modStatsHtml.js');

            modStatsGet.wpAbStatsVisitorGet( page, offset, limit, function( result ){

                modStatsHtml.wpAbStatsVisitorHtml( result );
            });
        })

    }
}
module.exports = modStatsGet;