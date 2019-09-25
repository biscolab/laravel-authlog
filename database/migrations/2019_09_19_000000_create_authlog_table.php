<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('blame_on_user_id')->nullable();
            $table->string('ip', 255)->nullable()->default('');
            $table->string('session_id', 255)->nullable()->default('');
            $table->text('user_agent')->nullable();
            $table->boolean('killed_from_console')->default(false);
            $table->dateTime('logged_out_at')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->index('session_id', 'session_id');
            $table->index('user_id', 'user_id');
            $table->index('blame_on_user_id', 'blame_on_user_id');
            $table->index(['logged_out_at', 'blame_on_user_id', 'killed_from_console'], 'logged_out_at');

            $table->foreign('blame_on_user_id', 'fk_authlog_blame_on_user')->references('id')->on('users')->onDelete('RESTRICT
')->onUpdate('RESTRICT');
            $table->foreign('user_id', 'fk_authlog_user')->references('id')->on('users')->onDelete('RESTRICT
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
