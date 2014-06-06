<?php namespace Auth\Providers;

use \Illuminate\Auth\UserProviderInterface,
	\Illuminate\Auth\EloquentUserProvider,
	\Illuminate\Auth\UserInterface;

//use Illuminate\Hashing\HasherInterface;

class WWUAuthUserProvider extends EloquentUserProvider implements UserProviderInterface{

	/**
	 * The Eloquent user model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Create a new database user provider.
	 *
	 * @param  string  $model
	 * @return void
	 */
	public function __construct($model)
	{
		$this->model = $model;		
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials)
	{
		if($user) return true;
	}

	public function getByUsername($username){
		$model = $this->createModel();
		return $model->getByUsername($username);
	}

	public function create(Array $data){
		$model = $this->createModel();
		return $model->create($data);
	}	

}
