<?php namespace Khoimk\Ezoauth2;

use Illuminate\Support\ServiceProvider;

class Ezoauth2ServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		require_once __DIR__.'/../../../vendor/google/src/Google_Client.php';
		require_once __DIR__.'/../../../vendor/google/src/contrib/Google_Oauth2Service.php';
		require_once __DIR__.'/../../../vendor/facebook/src/facebook.php';
		//Session::get('key');
		//session_start();
        $this->app['ezoauth2'] = $this->app->share(function($app)
        {
            return new Ezoauth2;
        });
	}

	public function boot()
    {
        $this->package('Khoimk/Ezoauth2');
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Ezoauth2');
	}

}