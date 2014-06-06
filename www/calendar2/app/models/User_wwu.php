<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Userwwu extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wwuauthusers';

	protected $fillable = array('username');

	public $timestamps = false;

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return null;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return '';
	}

	/**
	* Gets a user by username
	* @param string $username
	* @return WWUAuthUser | null
	*/
	public function getByUsername($username){
		$users = $this->where('username', '=', $username);
		if($users) return $users->first();
		else return null;
	}

	#####  For working with Authority L4, Role based perms lib #####
	public function roles() {
        return $this->belongsToMany('Role');
    }

    public function permissions() {
        return $this->hasMany('Permission');
    }

    public function hasRole($key) {    	
        foreach($this->roles as $role){
            if($role->name === $key)
            {
                return true;
            }
        }
        return false;
    }
}