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
		    'client_id'     => '535166830160-pijqjvse9alt582n9vecegcagqsccp9o.apps.googleusercontent.com',
		    'client_secret' => 'AIzaSyDKS3HS4MBSPaBEW_pTUZQa3T2f7LZGsw0',
		    'device_name' => 'lientop',
		    'device_id' => '12345-lientop',
		    'scope'         => array('userinfo_email', 'userinfo_profile'),
		),
	)

);