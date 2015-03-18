# rpbe-auth


#composer.json
 ```
 "repositories": [
    {
		  "type": "vcs",
		  "url": "https://github.com/martinschaepker/rpbeauth.git"
	   }
   ],
   
   "require": {
   	  RpbeAuth": "dev-master"
   
   	},
 ```
 
 After composer install 
 
 run
  ```
  php artisan vendor:publish
  
  composer dump-autoload
  
  php artisan migrate
  ```
  
#config/app.php
```
'providers' => [
 ...
'RpbeAuth\Provider\RpbeAuthServiceProvider',
    ]
 ```    
#routes.php
 ```
Route::group(['middleware' => '\RpbeAuth\Http\Middleware\Services\TokenUserService'] ,function() {
	
});
 ```
