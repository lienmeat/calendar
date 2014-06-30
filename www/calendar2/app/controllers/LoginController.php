<?php
class LoginController extends BaseController {

	public function getLogin() {

	}

	public function getLoginwithgoogle() {		
		if( !$this->_isLoggedIn() ) {
		    // get data from input
		    $code = Input::get( 'code' );

		    // get google service
		    $googleService = OAuth::consumer( 'Google' );

		    // check if code is valid

		    // if code is provided get user data and sign in
		    if ( !empty( $code ) ) {

		        // This was a callback request from google, get the token
		        $token = $googleService->requestAccessToken( $code );
		        		        // Send a request with it
		        $result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );
		        $result['token'] = $token;

		        
		        //logged in successfully, now what?
		        Session::put('user_info', $result);
		        
		    }
		    // if not ask for permission first
		    else {
		        // get googleService authorization
		        $url = $googleService->getAuthorizationUri();

		        // return to google login url
		        return Redirect::to( (string) $url );
		    }
		}	
		else{
			//user is already logged in.  Why are they here?
			$result = Session::get('user_info');
			$message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		    echo $message. "<br/>";
			dd(Session::get('user_info'));
		}


	}

	/**
	 * Check if user logged in
	 * @return bool
	 */
	protected function _isLoggedIn() {
		return Session::has('user_info');
	}

}