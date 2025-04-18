<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('reset_token')->nullable()->after('password');
            $table->timestamp('reset_token_expires_at')->nullable()->after('reset_token');
            $table->string('oauth_provider')->nullable()->after('reset_token_expires_at');
            $table->string('oauth_id')->nullable()->after('oauth_provider');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reset_token', 'reset_token_expires_at', 'oauth_provider', 'oauth_id']);
        });
    }
};
