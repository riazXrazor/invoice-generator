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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('has_different_shipping_address')->default(false);
            $table->string('shipping_name')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_gstin')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_state_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'has_different_shipping_address',
                'shipping_name',
                'shipping_address',
                'shipping_gstin',
                'shipping_state',
                'shipping_state_code',
            ]);
        });
    }
};
