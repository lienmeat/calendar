<?php
class LoginController extends BaseController {

	public function getLogin() {
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

				$user = User::where('email', $result['email'])->get()->first();				
				
				if( $user ) {
					//user exists, log them in					
					Auth::loginUsingId($user->id);
				}
				else{
					//register new user	& log them in				
					$tmp_user = new User;
					$tmp_user->email = $result['email'];
					$tmp_user->username = $result['email'];
					$tmp_user->given_name = $result['given_name'];
					$tmp_user->family_name = $result['family_name'];
					$tmp_user->save();
					Auth::loginUsingId($tmp_user->id);
				}
			}
			// if not ask for permission first
			else {
				// get googleService authorization
				$url = $googleService->getAuthorizationUri();
				// return to google login url
				return Redirect::to( (string) $url );
			}
		}

		//go to home page
		return Redirect::to('/');		
	}

	/**
	 * Check if user logged in
	 * @return bool
	 */
	protected function _isLoggedIn() {
		return Auth::check();
	}

}