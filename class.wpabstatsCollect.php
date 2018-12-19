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

    /**
     * Get current post on front office, visitor view
     */
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

    /**
     * Add data visitor and post view, in database
     */
	public static function wpAbStatsAddPostMeta( $post )
	{
        $now 	        = new Datetime();
        $dateNow        = $now->format('Y-m-d H:i:s');
        $visitor        = self::ipInfo();
        $browser        = self::getBrowser();
        $os_platform    = self::getOS();

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
                'os' => $os_platform,
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
                    '%s',
                    '%s'
                ]
            );
        }
		return;
    }

    /**
     * Get visitor os
     */
    public static function getOS() 
    { 
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform  = "Unknown OS Platform";

        $os_array     = [
            '/windows nt 10/i'      =>  'windows-10',
            '/windows nt 6.3/i'     =>  'windows-8',
            '/windows nt 6.2/i'     =>  'windows-8',
            '/windows nt 6.1/i'     =>  'windows-7',
            '/windows nt 6.0/i'     =>  'windows-vista',
            '/windows nt 5.2/i'     =>  'windows-server',
            '/windows nt 5.1/i'     =>  'windows-xp',
            '/windows xp/i'         =>  'windows-xp',
            '/windows nt 5.0/i'     =>  'windows-2000',
            '/windows me/i'         =>  'windows-me',
            '/win98/i'              =>  'windows-98',
            '/win95/i'              =>  'windows-95',
            '/win16/i'              =>  'windows-3.11',
            '/macintosh|mac os x/i' =>  'mac-osx',
            '/mac_powerpc/i'        =>  'mac-os9',
            '/linux/i'              =>  'linux',
            '/ubuntu/i'             =>  'ubuntu',
            '/iphone/i'             =>  'iphone',
            '/ipod/i'               =>  'ipod',
            '/ipad/i'               =>  'ipad',
            '/android/i'            =>  'android',
            '/blackberry/i'         =>  'blackberry',
            '/webos/i'              =>  'mobile'
        ];

        foreach ($os_array as $regex => $value)
        {
            if ( preg_match( $regex, strtolower( $user_agent ) ) ) $os_platform = $value;
        }
        return $os_platform;
    }

    /**
     * Get visitor browser
     */
    public static function getBrowser() 
    {
        $user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $browser        = "Unknown Browser";
    
        $browser_array = [
            '/msie/i'      => 'internet_explorer',
            '/firefox/i'   => 'firefox',
            '/safari/i'    => 'safari',
            '/chrome/i'    => 'chrome',
            '/edge/i'      => 'edge',
            '/opera/i'     => 'opera',
            '/netscape/i'  => 'netscape',
            '/maxthon/i'   => 'maxthon',
            '/konqueror/i' => 'jonqueror',
            '/mobile/i'    => 'handheld_browser'
        ];
    
        foreach ($browser_array as $regex => $value)
        {
            if ( preg_match( $regex, strtolower( $user_agent ) ) ) $browser = $value;
        }
    
        return $browser;
    }

    /**
     * Random country IP for local devlopment test
     */
    public static function ipRand()
    {
        $ips = [ 
            "90.37.38.76", 
            "168.169.146.12", 
            "31.22.48.0",
            "41.220.144.0",
            "24.51.64.0",
            "5.23.128.0",
            "64.37.32.0",
            "23.16.0.0",
            "2.104.0.0",
            "2.160.0.0"
        ];

        return $ips[array_rand($ips)];
    }

    

    /**
     * Get all info visitor, with this IP
     */
    public static function ipInfo( $ip = NULL, $purpose = "location", $deep_detect = TRUE ) 
    {
        $output = null;

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

        /**
         * For local devlopment test
         */
        if ( home_url() === "http://wordpress5.dom" )
        {
            $ip = self::ipRand();
        }

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