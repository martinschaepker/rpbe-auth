<?php namespace RpbeAuth\Provider;
 
 use Illuminate\Support\ServiceProvider;
 use RpbeAuth\Http\Middelware\VerifyCsrfToken;

 class RpbeAuthServiceProvider extends ServiceProvider {
 
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

 	}
 
 	/**
 	* Get the services provided by the provider.
 	*
 	* @return array
 	*/
 	public function provides()
 	{
     	return [];
 	}


	 public function boot()
	 {
		include(__DIR__."/../Http/routes.php");
	 }
 
 }