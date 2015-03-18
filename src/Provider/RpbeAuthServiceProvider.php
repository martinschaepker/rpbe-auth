<?php namespace RpbeAuth\Provider;
 
 use Illuminate\Encryption\Encrypter;
 use Illuminate\Support\ServiceProvider;

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
         $this->app['hash'] = $this->app->share( function () {
            return new \RpbeAuth\Http\Middleware\Auth\Sha512Hasher();
        });

        $this->app['config']['auth.model'] = 'RpbeAuth\Http\Middleware\Model\User';
        
        $this->app->bind( '\RpbeAuth\Http\Middleware\Services\TokenUserService', function ( $app ) {
            $service = new \RpbeAuth\Http\Middleware\Services\TokenUserService();
            $service->setHasher( new \RpbeAuth\Http\Middleware\Auth\Sha512Hasher() );
            $service->setUser( new \RpbeAuth\Http\Middleware\Model\User() );
            $service->setAuthToken(new \RpbeAuth\Http\Middleware\Model\AuthToken );
            $service->setEncrypter( new Encrypter($this->app['config']['app']['key'] ) );
            return $service;
            }
        );
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
     }
 
 }