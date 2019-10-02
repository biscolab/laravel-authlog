<?php

use Biscolab\LaravelAuthLog\Facades\AuthLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAuthlogTable
 */
class CreateAuthlogTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(config('authlog.table_name'), function (Blueprint $table) {

            $user_id_type = AuthLog::getMigrateUserIdType();
            $table->bigIncrements('id');
            $table->$user_id_type('user_id');
            $table->$user_id_type('blame_on_user_id')->nullable();
            $table->string('ip', 255)->nullable()->default('');
            $table->string('session_id', 255)->nullable()->default('');
            $table->text('user_agent')->nullable();
            $table->boolean('killed_from_console')->default(false);
            $table->dateTime('logged_out_at')->nullable();
            $table->timestamps();

            $table->index('session_id', 'session_id');
            $table->index('user_id', 'user_id');
            $table->index('blame_on_user_id', 'blame_on_user_id');
            $table->index(['logged_out_at', 'blame_on_user_id', 'killed_from_console'], 'logged_out_at');

            $table->foreign('blame_on_user_id')->references('id')->on('users')->onDelete('RESTRICT
')->onUpdate('RESTRICT');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT
')->onUpdate('RESTRICT');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists(config('authlog.table_name'));
    }
}
