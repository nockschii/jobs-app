<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EmploymentType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $employmentTypes = array_map(fn($type) => $type->value, EmploymentType::cases());
            $table->id();
            $table->string('title', 120);
            $table->longText('description')->nullable();
            $table->string('department', 50)->nullable();
            $table->string('city', 60)->nullable();
            $table->string('country', 60)->nullable();
            $table->string('application_email', 255);
            $table->string('application_url', 200)->nullable();
            $table->enum('employment_type', $employmentTypes, 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
