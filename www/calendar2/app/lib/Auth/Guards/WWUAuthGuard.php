<?php namespace Auth\Guards;

use \Illuminate\Auth\Guard,
	\Illuminate\Auth\UserProviderInterface,
	\Illuminate\Session\Store,
	\Illuminate\Auth\UserInterface,
	\DA_Client,
	\User;

class WWUAuthGuard extends Guard{

	/**
	 * Create a new authentication guard.
	 *
	 * @param  \Illuminate\Auth\UserProviderInterface  $provider
	 * @param  \Illuminate\Session\Store  $session
	 * @return void
	 */
	public function __construct(UserProviderInterface $provider, Store $session)
	{
		$this->provider = $provider;
		require_once('DA_Client.php');
	}

	/**
	 * Determine if the current user is authenticated.
	 *
	 * @return bool
	 */
	public function check()
	{
		if($this->user && $this->user->username){
			return true;
		}
		return false;
	}

	/**
	 * Determine if the current user is a guest.
	 *
	 * @return bool
	 */
	public function guest()
	{
		if($this->user && $this->user->username){
			return false;
		}
		return true;
	}

	/**
	 * Get the currently authenticated user.
	 *
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function user()
	{
		if ($this->loggedOut) return;		

		// If we have already retrieved the user for the current request we can just
		// return it back immediately. We do not want to pull the user data every
		// request into the method becaue that would tremendously slow the app.
		if ( ! is_null($this->user))
		{
			return $this->user;
		}
		

		$tmp = DA_Client::getUser();
		if($tmp && $tmp['username']){
			$user = $this->provider->getByUsername($tmp['username']);
			
			if(!$user){
				$user = $this->provider->create(array('username'=>$tmp['username']));
			}

			foreach ($tmp as $key => $value) {
				if($key != 'id') {
					$user->$key = $value;
				}
			}
		}

		if (!$user or !$user->username) {
			//we HAVE to return a user object to be compatible with Authority!
			$user = new User;
		}

		return $this->user = $user;
	}	

	/**
	 * Log a user into the application without sessions or cookies.
	 *
	 * @param  array  $credentials
	 * @return bool
	 */
	public function once(array $credentials = array())
	{
		if ($this->validate($credentials))
		{
			$this->setUser($this->provider->retrieveByCredentials($credentials));

			return true;
		}

		return false;
	}

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param  array  $credentials
	 * @param  bool   $remember
	 * @param  bool   $login
	 * @return bool
	 */
	public function attempt(array $credentials = array(), $remember = false, $login = true)
	{
		$this->fireAttemptEvent($credentials, $remember, $login);
		if($this->check()) 
			return true;
		else
			DA_Client::doLogin('dirlogin,nonwwulogin');
	}

	/**
	 * Log a user into the application.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  bool  $remember
	 * @return void
	 */
	public function login(UserInterface $user, $remember = false)
	{
		$id = $user->getAuthIdentifier();

		//$this->session->put($this->getName(), $id);

		// If we have an event dispatcher instance set we will fire an event so that
		// any listeners will hook into the authentication events and run actions
		// based on the login and logout events fired from the guard instances.
		if (isset($this->events))
		{
			$this->events->fire('auth.login', array($user, $remember));
		}

		$this->setUser($user);
	}

	/**
	 * Log the given user ID into the application.
	 *
	 * @param  mixed  $id
	 * @param  bool   $remember
	 * @return \Illuminate\Auth\UserInterface
	 */
	public function loginUsingId($id, $remember = false)
	{
		//$this->session->put($this->getName(), $id);

		return $this->login($this->provider->retrieveById($id), $remember);
	}

	/**
	 * Log the given user ID into the application without sessions or cookies.
	 *
	 * @param  mixed  $id
	 * @return bool
	 */
	public function onceUsingId($id)
	{
		$this->setUser($this->provider->retrieveById($id));

		return $this->user instanceof UserInterface;
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return void
	 */
	public function logout()
	{
		$user = $this->user();

		// If we have an event dispatcher instance, we can fire off the logout event
		// so any further processing can be done. This allows the developer to be
		// listening for anytime a user signs out of this application manually.
		//$this->clearUserDataFromStorage();

		if (isset($this->events))
		{
			$this->events->fire('auth.logout', array($user));
		}

		// Once we have fired the logout event we will clear the users out of memory
		// so they are no longer available as the user is no longer considered as
		// being signed into this application and should not be available here.
		$this->user = null;

		$this->loggedOut = true;
	}
}
