let modStatsHtml = {

    wpAbStatsPageHtml: ( result )=> {

        if( result && result.labels && result.data && result.backgroundColor && result.borderColor ){

            let labels          = result.labels;
            let data            = result.data;
            let backgroundColor = result.backgroundColor;
            let borderColor     = result.borderColor;

            let modChart        = require('./modChart.js');
            
            modChart.ChartBar( labels, data, backgroundColor, borderColor );
        }
    },

    wpAbStatsVisitorHtml: ( result )=> {

        let $ = jQuery;

        if( result ){

            $('.wpabstats_visitor_container').html('');

            $.each( result.datas, function( index, item ){

                let data = 
                '<li data-id="'+item.id+'" class="wpabstats_visitor_items">'+
                    '<div class="item item_browser"><img src="/wp-content/plugins/wp-ab-stats/assets/src/images/browser/16/'+item.browser+'.png" alt="'+item.browser+'"/></div>'+
                    '<div class="item item_flag"><span class="flag-icon flag-icon-'+item.country_code.toLowerCase()+'"></span></div>'+
                    '<div class="item item_ip">'+item.ip+'</div>'+
                    '<div class="item item_country">'+item.country+'</div>'+
                    '<div class="item item_continent">'+item.continent+'</div>'+
                    '<div class="item item_city">'+item.city+'</div>'+
                    '<div class="item item_date">'+item.date_log+'</div>'+
                    '<div class="item item_post"> " '+item.post_title+' "</div>'+
                '</li>';

                $('.wpabstats_visitor_container').append( data );
            });

            if( result.offset === "0" ){

                $('.wpabstats_visitor_paginate').html('');

                for( var i=1; i < result.pagination; i++ ){

                    let index = i, offset = 0, limit = result.limit;

                    if ( index > 1 ) {
                        limit   = result.limit*index;
                        offset  = limit - result.limit;
                    }

                    let paginateHtml = 
                    '<li class="wpabstats_paginate_item">'+
                        '<a class="paginate_link" data-page="'+index+'" data-offset="'+offset+'" data-limit="'+limit+'" href="#">'+index+'</a>'+
                    '</li>';

                    $('.wpabstats_visitor_paginate').append( paginateHtml );
                }
            }

            $('.paginate_link').removeClass('paginate_active');

            $('.paginate_link[data-page="'+result.page+'"]').addClass('paginate_active');

            let modStatsGet = require('./modStatsGet.js');

            modStatsGet.wpAbStatsVisitorPag();
        }
    }
}
module.exports = modStatsHtml;