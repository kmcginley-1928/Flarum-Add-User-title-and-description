<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return [
    'up' => function () {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'title')) {
                $table->string('title', 200)->nullable();
            }
            if (!Schema::hasColumn('users', 'short_description')) {
                $table->text('short_description')->nullable();
            }
        });
    },
    'down' => function () {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('users', 'short_description')) {
                $table->dropColumn('short_description');
            }
        });
    }
];