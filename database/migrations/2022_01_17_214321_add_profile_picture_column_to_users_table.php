<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfilePictureColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('profile_picture_id')->after('remember_token')->unsigned()->nullable();

            $table->foreign('profile_picture_id')->references('id')->on('user_profile_pictures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign('users_profile_picture_id_foreign');
            $table->dropIfExists('profile_picture_id');
            Schema::enableForeignKeyConstraints();
        });
    }
}
