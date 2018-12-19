let modStatsHtml = {

    wpAbStatsPageHtml: ( result )=> {

        if( result && result.labels && result.data && result.backgroundColor && result.borderColor ){

            let labels          = result.labels;
            let data            = result.data;
            let backgroundColor = result.backgroundColor;
            let borderColor     = result.borderColor;

            let modChart        = require('./modChart.js');
            
            modChart.ChartBar( labels, data, backgroundColor, borderColor, "wpAbStatsChartPage", "Pages stats" );
        }
    },

    wpAbStatsPostHtml: ( result )=> {

        if( result && result.labels && result.data && result.backgroundColor && result.borderColor ){

            let labels          = result.labels;
            let data            = result.data;
            let backgroundColor = result.backgroundColor;
            let borderColor     = result.borderColor;

            let modChart        = require('./modChart.js');
            
            modChart.ChartBar( labels, data, backgroundColor, borderColor, "wpAbStatsChartArticle", "Articles stats" );
        }
    },

    wpAbStatsVisitorCountHtml: ( result )=> {

        let $ = jQuery;

        if( result ){

            $.each( result, function( browser, number ){

                let html =
                '<div class="browser_flex_item">'+
                    '<div><img src="/wp-content/plugins/wp-ab-stats/assets/src/images/browser/60/'+browser+'.png" alt="firefox"/></div>'+
                    '<div>'+number+'</div>'+
                '</div>';

                $('.browser_flex_items').append( html );

            });
        }

    },

    wpAbStatsVisitorHtmlHover: ()=> {

        let $ = jQuery;

        $d.off('mouseenter', '.wpabstats_visitor_items').on('mouseenter', '.wpabstats_visitor_items', function(){

            $('.wpabstats_visitor_item_description').hide();

            let itemId = $(this).attr('data-id');

            $('.wpabstats_visitor_item_description[data-id="'+itemId+'"]').show();
        });

        $d.off('mouseleave', '.wpabstats_visitor_items').on('mouseleave', '.wpabstats_visitor_items', function(){

            $('.wpabstats_visitor_item_description').hide();
        });

    },

    wpAbStatsVisitorHtml: ( result )=> {

        let $ = jQuery;

        console.log( result )

        if( result ){

            $('.wpabstats_visitor_container').html('');

            $.each( result.datas, function( index, item ){

                let city = '', country = '', country_code = '', continent = '', continent_code = '', city_short = '';

                if( item.city ){

                    city = item.city;

                    if( city.length > 10 ) city_short = city.substring( 0, 10 )+'...';
                }

                if( item.country ) country = item.country;
                if( item.country_code ) country_code = item.country_code;
                if( item.continent ) continent = item.continent;
                if( item.continent_code ) continent_code = item.continent_code;
                
                let data = 
                '<li data-id="'+item.id+'" class="wpabstats_visitor_items">'+
                    '<div class="item item_browser"><img src="/wp-content/plugins/wp-ab-stats/assets/src/images/browser/16/'+item.browser+'.png" alt="'+item.browser+'"/></div>'+
                    '<div class="item item_flag"><span class="flag-icon flag-icon-'+country_code.toLowerCase()+'"></span></div>'+
                    '<div class="item item_ip">'+item.ip+'</div>'+
                    '<div class="item item_country">'+country+'</div>'+
                    '<div class="item item_continent">'+continent+'</div>'+
                    '<div class="item item_city">'+city_short+'</div>'+
                    '<div class="item item_date">'+item.date_log+'</div>'+
                    '<div class="item item_post"> "'+item.post_title+' "</div>'+
                '</li>';

                let item_description = 
                '<div data-id="'+item.id+'" class="wpabstats_visitor_item_description">'+
                    '<div class="in_item_description">'+
                        '<div><span class="flag-icon flag-icon-'+country_code.toLowerCase()+'"></span> '+item.ip+'</div>'+
                        '<div class="in_item_flag">'+
                            '<img src="/wp-content/plugins/wp-ab-stats/assets/src/images/browser/30/'+item.browser+'.png" alt="'+item.browser+'"/>'+
                        '</div>'+
                        '<div>OS PLATFORM : '+item.os+'</div>'+
                        '<div>PAGE : '+item.post_title+'</div>'+
                        '<div>PAYS :'+country+' - '+country_code+'</div>'+
                        '<div>CONTINENT : '+continent+' - '+continent_code+'</div>'+
                        '<div>VILLE : '+city+'</div>'+
                        '<div>JOUR : '+item.date_log+'</div>'+
                    '</div>'+
                '</div>';

                $('.wpabstats_visitor_container').append( data );
                $('.wpabstats_container_visitor').append( item_description );
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

            modStatsHtml.wpAbStatsVisitorHtmlHover();
        }
    }
}
module.exports = modStatsHtml;