<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Google
		 */
        'Google' => array(
		    'client_id'     => '535166830160-4cstgsaa155tkjreksnis6mdkuiad40b.apps.googleusercontent.com',
		    'client_secret' => '5bx3msKcHGTJzjLbU_nfgKFt',
		    'scope'         => array('userinfo_email', 'userinfo_profile'),
		),
	)

);