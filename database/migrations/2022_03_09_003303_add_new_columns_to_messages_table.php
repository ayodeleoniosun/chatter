<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('message_type')->default('text')->after('message');
            $table->bigInteger('attachment_id')->after('message_type')->unsigned()->nullable();
            $table->boolean('is_read')->default(false)->after('attachment_id');
            $table->dateTime('read_at')->nullable()->after('is_read');

            $table->foreign('attachment_id')->references('id')->on('files');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['message_type', 'attachment_id', 'is_read', 'read_at']);
        });
    }
}
