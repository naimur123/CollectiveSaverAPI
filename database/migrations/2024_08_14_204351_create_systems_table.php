<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('application_name')->nullable();
            $table->string('title_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('state')->nullable();
            $table->string('app_version')->default("1.0");
            $table->string('date_format')->default("Y-m-d");
            $table->string('time_zone')->default("UTC");
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};
