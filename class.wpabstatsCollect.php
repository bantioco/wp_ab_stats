<?php

class wpabstatsCollect
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
        
        add_action( 'template_redirect', [ 'wpabstatsCollect', 'wpAbStatsCurrentGet' ] );
    }

    public static function wpAbStatsCurrentGet()
	{
        global $wp_query;

		if( $wp_query )
		{
            $post = $wp_query->get_queried_object();
            
            self::wpAbStatsAddPostMeta( $post );			
		}
		return;
	}


	public static function wpAbStatsAddPostMeta( $post )
	{
        $now 	    = new Datetime();
        $dateNow = $now->format('Y-m-d H:i:s');
        $visitor    = self::ipInfo();
        $browser    = self::getBrowser();

        if( $visitor )
        {
            global $wpdb;

            $datas = [ 
                'post_id' => $post->ID, 
                'meta_value' => '_wpabstats_post_view', 
                'ip' => $visitor['ip'], 
                'country' => $visitor['country'], 
                'country_code' => $visitor['country_code'], 
                'city' => $visitor['city'], 
                'continent' => $visitor['continent'], 
                'continent_code' => $visitor['continent_code'], 
                'browser' => $browser,
                'date_log' => $dateNow, 
                'created_at' => $dateNow
            ];

            $table_name = $wpdb->prefix . 'abstats';

            $wpdb->insert( 
                $table_name, 
                $datas,
                [
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ]
            );
        }
		return;
    }

    public static function getBrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $name = 'NA';
        
        if (preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent)) {
            $name = 'internet_explorer';
        } elseif (preg_match('/Firefox/i', $agent)) {
            $name = 'firefox';
        } elseif (preg_match('/Chrome/i', $agent)) {
            $name = 'chrome';
        } elseif (preg_match('/Safari/i', $agent)) {
            $name = 'safari';
        } elseif (preg_match('/Opera/i', $agent)) {
            $name = 'opera';
        } elseif (preg_match('/Netscape/i', $agent)) {
            $name = 'netscape';
        }
        return $name;
    }
    
    /*

	public function wpAbStatsAddPostMetaDate( $post )
	{

		$now 		= new Datetime();
		$dateView 	= $now->format('Y-m-d H:i:s');

        add_post_meta( $post->ID, '_wpabstats_post_view_date', $dateView );
        
        self::wpAbStatsAddVisitorData( $post );

		return;
    }

    */
    
    /*
    public function wpAbStatsAddVisitorData( $post )
	{

        $visitorData = self::ipInfo();

        if( $visitorData )
        { 
    
            add_post_meta( $post->ID, '_wpabstats_visitor_city', $visitorData['city'] );
            add_post_meta( $post->ID, '_wpabstats_visitor_state', $visitorData['state'] );
            add_post_meta( $post->ID, '_wpabstats_visitor_country', $visitorData['country'] );
            add_post_meta( $post->ID, '_wpabstats_visitor_continent', $visitorData['continent'] );
            add_post_meta( $post->ID, '_wpabstats_visitor_code', $visitorData['code'] );   
        
        }
        


		return;
    }
    */

    public static function ipInfo($ip = NULL, $purpose = "location", $deep_detect = TRUE) 
    {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) 
        {
            $ip = $_SERVER["REMOTE_ADDR"];

            if ($deep_detect) 
            {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }

        //DEV
        //$ip = "90.37.38.76";
        $ip = "168.169.146.12";

        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = [ "country", "countrycode", "state", "region", "city", "location", "address" ];
        
        $continents = [
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        ];

        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) 
        {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) 
            {
                switch ($purpose) {
                    case "location":
                        $output = [
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        ];
                        break;
                    case "address":
                        $address = [ $ipdat->geoplugin_countryName ];
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }

        $output['ip'] = $ip;

        return $output;
    }


}