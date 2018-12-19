<?php

class wpabstatsAjax
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

		add_action( 'wp_ajax_wpAbStatsVisitorGet', [ 'wpabstatsAjax', 'wpAbStatsVisitorGet' ] );
		add_action( 'wp_ajax_nopriv_wpAbStatsVisitorGet', [ 'wpabstatsAjax', 'wpAbStatsVisitorGet' ] );

		add_action( 'wp_ajax_wpAbStatsPageGet', [ 'wpabstatsAjax', 'wpAbStatsPageGet' ] );
		add_action( 'wp_ajax_nopriv_wpAbStatsPageGet', [ 'wpabstatsAjax', 'wpAbStatsPageGet' ] );

		add_action( 'wp_ajax_wpAbStatsPostGet', [ 'wpabstatsAjax', 'wpAbStatsPostGet' ] );
		add_action( 'wp_ajax_nopriv_wpAbStatsPostGet', [ 'wpabstatsAjax', 'wpAbStatsPostGet' ] );

		
		add_action( 'wp_ajax_wpAbStatsBrowserCountGet', [ 'wpabstatsAjax', 'wpAbStatsBrowserCountGet' ] );
		add_action( 'wp_ajax_nopriv_wpAbStatsBrowserCountGet', [ 'wpabstatsAjax', 'wpAbStatsBrowserCountGet' ] );
	}

	/**
	 * @todo: a faire
	 */
	public function wpAbStatsBrowserCountGet()
	{
		global $wpdb;

		$wpabdatas = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}abstats", OBJECT );

		$data = [];

		if( $wpabdatas )
		{
			foreach( $wpabdatas as $wpabdata )
			{
				if( !array_key_exists( $wpabdata->browser, $data ) )
				{
					$data[$wpabdata->browser] = 1;
				}
				else
				{
					$data[$wpabdata->browser] = $data[$wpabdata->browser] + 1;
				}
			}
		}
		wp_send_json( $data );
	}


	public function wpAbStatsVisitorGet() 
	{
		$offset 	= 0;
		$limit 		= 20;
		$page 		= 1;

		if( isset( $_POST['params'] ) )
		{
			$params = $_POST['params'];

			if( isset( $params['page'] ) && isset( $params['limit'] ) && isset( $params['offset'] ) )
			{
				$offset 	= $params['offset'];
				$limit 		= $params['limit'];
				$page 		= $params['page'];
			}
		}
		
		global $wpdb;

		$wpabTotals 	= $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}abstats", OBJECT );
		$wpabdatas 		= $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}abstats ORDER BY id DESC LIMIT $offset,$limit;", OBJECT );
		$data 			= [];
		$total_items 	= count( $wpabTotals );

		$data['total_items'] 	= $total_items;
		$data['page'] 			= $page;
		$data['offset'] 		= $offset;
		$data['limit'] 			= $limit;
		$data['pagination']		= round( $total_items / ($limit-$offset)+1 );
		
		if( $wpabdatas )
		{
			foreach( $wpabdatas as $key => $wpabdata )
			{
				$post = get_post( $wpabdata->post_id );

				$dateLog = new Datetime( $wpabdata->date_log );
				$dateLog = $dateLog->format('d/m/Y')." - ".$dateLog->format('H:i');

				$data['datas'][$key] =[
					'id' => $wpabdata->id,
					'post_id' => $wpabdata->post_id,
					'post_title' => $post->post_title,
					'meta_value' => $wpabdata->meta_value,
					'ip' => $wpabdata->ip,
					'country' => $wpabdata->country,
					'country_code' => $wpabdata->country_code,
					'city' => $wpabdata->city,
					'continent' => $wpabdata->continent,
					'continent_code' => $wpabdata->continent_code,
					'browser' => $wpabdata->browser,
					'os' => $wpabdata->os,
					'date_log' => $dateLog,
					'created_at' => $wpabdata->created_at
				];
			}
		}
		wp_send_json( $data );
    }

    public function wpAbStatsPageGet() 
	{
		$args = [
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post_type' => 'page'
		];

		$posts = get_posts( $args );

		global $wpdb;

		$data 	= [];
		
		if( $posts )
		{
			$number = 0;

			foreach( $posts as $key => $post )
			{
				$wpabdatas = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}abstats WHERE post_id=$post->ID", OBJECT );

				$number = count( $wpabdatas );

				$color = self::randomColor();

				$data['labels'][$key] 			= $post->post_title;
				$data['data'][$key] 			= $number;
				$data['backgroundColor'][$key] 	= $color;
				$data['borderColor'][$key] 		= $color;

				$number = 0;

			}
		}
		wp_send_json( $data );
	}
	

	public function wpAbStatsPostGet() 
	{
		$args = [
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post_type' => 'post'
		];

		$posts = get_posts( $args );

		global $wpdb;

		$data 	= [];
		
		if( $posts )
		{
			$number = 0;

			foreach( $posts as $key => $post )
			{
				$wpabdatas = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}abstats WHERE post_id=$post->ID", OBJECT );

				$number = count( $wpabdatas );

				$color = self::randomColor();

				$data['labels'][$key] 			= $post->post_title;
				$data['data'][$key] 			= $number;
				$data['backgroundColor'][$key] 	= $color;
				$data['borderColor'][$key] 		= $color;

				$number = 0;
			}
		}
		wp_send_json( $data );
    }
    

    public function randomColor()
	{
		$colorArray = [ 
			"rgba(46, 204, 113, 0.5)", 
			"rgba(52, 152, 219, 0.5)", 
			"rgba(155, 89, 182, 0.5)", 
			"rgba(231, 76, 60, 0.5)",
			"rgba(149, 165, 166, 0.5)",
			"rgba(241, 196, 15, 0.5)",
			"rgba(230, 126, 34, 0.5)",
			"rgba(243, 156, 18, 0.5)",
			"rgba(192, 57, 43, 0.5)",
			"rgba(52, 73, 94, 0.5)",
			"rgba(189, 195, 199, 0.5)"
		];

		return $colorArray[array_rand($colorArray)];
	}
}