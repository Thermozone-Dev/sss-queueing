<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('code')->nullable()->unique();
            $table->string('opening_hours')->nullable();
            $table->string('closing_hours')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'opening_hours',
                'closing_hours',
                'address_line_1',
                'address_line_2',
                'city',
                'province',
                'postal_code',
                'email',
                'contact_number',
                'is_active',
            ]);
        });
    }
};
