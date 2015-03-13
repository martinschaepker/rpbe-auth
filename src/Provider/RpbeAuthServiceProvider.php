<?php namespace RpbeAuth\Provider;
 
 use Illuminate\Support\ServiceProvider;
 use RpbeAuth\Http\Middelware\VerifyCsrfToken;
 use Symfony\Component\HttpKernel\Kernel;

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
		 include( __DIR__ . "/../Http/routes.php" );
		 $kernel = $this->app->offsetGet( 'Illuminate\Contracts\Http\Kernel' );
		 $kernel->pushMiddleware( 'RpbeAuth\Http\Middleware\Token' );
	 }
 
 }