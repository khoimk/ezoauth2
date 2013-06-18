ezoauth2
========

Oauth2 for Laravel 4

This is very easy to use.

Step 1: Create config file

Add a config file to app/config/ezoauth2.php with content:

return array(

  		'google'=>array(
  				'name'=>'google',				
				'key'=>array(				
					'id'=>'client_id',					
					'secret'=>'client_secret'					
					),					
				'returnUrl'=>'http://localhost:8080/laravel/public/social/google'				
				),
			'facebook'=>array(
				'name'=>'facebook',
				'key'=>array(
					'id'=>'client_id',
					'secret'=>'client_secret'
					),
				'returnUrl'=>'http://localhost:8080/laravel/public/social/facebook'
				)
		);
		
Step 2: Add the last value to providers array in app/config/app.php

  'Khoimk\Ezoauth2\Ezoauth2ServiceProvider'
