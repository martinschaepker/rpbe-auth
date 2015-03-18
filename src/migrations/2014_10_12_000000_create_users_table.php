<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration class to create the users table
 *
 */
class CreateUsersTable extends Migration
{

    // Tabellennamen festlegen
    const TABLENAME = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Eloquent::unguard();
        $actDB = ( Config::get( 'database.default' ) === 'mysql' ) ? true : false;
        if( $actDB )
            DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );

        Schema::dropIfExists( self::TABLENAME );

        Schema::create( self::TABLENAME, function( $table )
        {
            $table->engine = 'InnoDB';

            $table->increments( 'id' )->unsigned();
            $table->string( 'username', 32 )->unique();
            $table->string( 'email', 255 )->unique();
            $table->string( 'password', 160 );
            $table->tinyInteger( 'active' )->default( 0 );
            $table->timestamps();  // Adds created_at and updated_at columns
            $table->softDeletes(); // Add a deleted_at column to your table // Set in Model: protected $softDelete = true;
            //$table->smallInteger( 'attempts' )->default( 0 );
            $table->string( 'remember_token' )->nullable();
            $table->string( 'country_code', 2 )->default( 'de' );
            $table->string( 'channel' )->nullable();
            $table->string( 'product_language', 5 )->nullable();
        } );

        if( $actDB )
            DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Eloquent::unguard();
        $actDB = ( Config::get( 'database.default' ) === 'mysql' ) ? true : false;

        if( $actDB )
            DB::statement( 'SET FOREIGN_KEY_CHECKS=0;' );

        Schema::dropIfExists( self::TABLENAME );

        if( $actDB )
            DB::statement( 'SET FOREIGN_KEY_CHECKS=1;' );
    }

}
