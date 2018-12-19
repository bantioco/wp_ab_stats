<?php

class wpabstatsCtp
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
        //add_action( 'admin_init', [ 'wpabstatsCtp', 'cptpGet' ] );
    }


    public static function cptpGet()
    {
        $args = [
            'public'    => true,
            '_builtin'  => false
        ];
         
        $output     = 'objects'; // names or objects, note names is the default
        $operator   = 'and'; // 'and' or 'or'
        $post_types = get_post_types( $args, $output, $operator );

        /*
        var_dump( $post_types );
        die();
        */

        if( $post_types ) 
        {

        }
        
    }
}