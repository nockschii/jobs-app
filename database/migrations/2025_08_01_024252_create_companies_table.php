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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255);
            $table->text('description');
            $table->string('address', 200);
            $table->string('city', 60);
            $table->string('country', 60);
            $table->string('postal_code', 20);
            $table->string('phone', 20);
            $table->string('website', 255);
            $table->string('linkedin_url', 255);
            $table->string('industry', 50);
            $table->string('logo', 255);
            $table->timestamps();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
