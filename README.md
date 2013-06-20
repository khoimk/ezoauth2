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

Step 3: Add this route to route.php
	
	if($provider!=''){
		$o = App::make('ezoauth2');
		$o->setConfig($provider);
		$url = $o->authenticate();
		if($url){
			return Redirect::to($url);
		}
		if(isset($_GET['logout'])){
			$o->logout();
		}
		else{
			if($user = $o->getUserInfo()){
				var_dump($user);
			}
		}
	}

Step 4: Add this line to composer.json file in the root of laravel

	"khoimk/ezoauth2": "*"

This version just support Google and Facebook authentication.
I will include Twitter and MS in next version.
Happy coding :)