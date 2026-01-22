<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('currency_rate', static function (Blueprint $table) {
            $table->smallInteger('currency_id');
            $table->decimal('rate', 18, 8);
            $table->timestamp('updated_at')->useCurrent();
            $table->primary('currency_id');
        });
    }
};
