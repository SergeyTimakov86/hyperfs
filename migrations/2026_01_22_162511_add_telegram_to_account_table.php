<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('_account', static function (Blueprint $table) {
            $table->string('telegram')->nullable()->after('discord');
        });
    }
};
