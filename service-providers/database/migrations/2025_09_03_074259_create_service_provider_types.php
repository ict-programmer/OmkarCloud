<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::connection('mongodb')->create('service_provider_types', function (Blueprint $collection) {
            $collection->index('service_provider_id');
            $collection->index('service_type_id');
            $collection->index('seed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->drop('service_provider_types');
    }
};
