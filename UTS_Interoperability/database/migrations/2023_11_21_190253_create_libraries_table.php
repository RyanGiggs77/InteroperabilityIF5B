<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('publish');
            $table->text('description');
            $table->integer('users_id')->unsigned();

            $table->timestamps();
        });
        
        Schema::table(
            'libraries', function ($table) {
                $table
                    ->foreign('users_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
    
};
