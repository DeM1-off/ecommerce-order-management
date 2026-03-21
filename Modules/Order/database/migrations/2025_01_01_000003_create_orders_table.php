<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::drop('orders');
    }
};
