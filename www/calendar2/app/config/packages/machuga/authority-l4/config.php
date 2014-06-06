<?php

return array(

	'initialize' => function($authority) {
		$user = $authority->getCurrentUser();

		if(!$user) {
			return;
		}


		$authority->addAlias('manage', array('create', 'read', 'update', 'delete'));

		if($user->hasRole('Admin')){
			$authority->allow('manage', 'all');
		}

		if($user->hasRole('UserAdmin')){
			$authority->allow('manage', 'User');
		}

		if($user->hasRole('CalendarAdmin')){
			$authority->allow('manage', 'Calendar');
		}

		// loop through each of the users permissions, and create rules
        foreach($user->permissions as $perm) {
            if($perm->type == 'allow') {
                $authority->allow($perm->action, $perm->resource);
            } else {
                $authority->deny($perm->action, $perm->resource);
            }
        }

	}
);
