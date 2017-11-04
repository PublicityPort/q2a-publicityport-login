<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
*  (c) 2009-2015 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
*/

/**
 * Hybrid_Providers_PublicityPort
 */
class Hybrid_Providers_PublicityPort extends Hybrid_Provider_Model_OAuth2
{ 
	// Permissions
	public $scope = "basic";

	/**
	* IDp wrappers initializer 
	*/
	function initialize() 
	{
		parent::initialize();
		
		// Provider api end-points
		$this->api->api_base_url  = "https://myaccount.publicityport.com/oauth/";
		$this->api->authorize_url = "https://myaccount.publicityport.com/oauth/authorize";	   
		$this->api->token_url     = "https://myaccount.publicityport.com/oauth/token";
	}

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{
	
		$ch = curl_init($this->api->api_base_url . "me");
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_ENCODING, "" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer '. $this->api->access_token
				));
				
		$content = curl_exec($ch);

		$data = json_decode( $content );
		
		if ( ! isset( $data->ID ) ) {
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

		$this->user->profile->identifier  = @ $data->ID; 
		$this->user->profile->username    = @ $data->username;
		$this->user->profile->displayName = @ $data->display_name;
		$this->user->profile->photoURL    = @ $data->avatar_URL;
		$this->user->profile->profileURL  = @ $data->profile_URL; 
		$this->user->profile->email       = @ $data->email;
		$this->user->profile->language    = @ $data->language;

		if ( ! $this->user->profile->displayName ) {
			$this->user->profile->displayName = @ $data->username;
		}

		return $this->user->profile;
	}
}
