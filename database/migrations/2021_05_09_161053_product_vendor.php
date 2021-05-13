<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductVendor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_vendor', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_sku')->nullable();
            $table->string('vendors_title')->nullable();
            $table->string('vendor_order_unit')->nullable();
            $table->string('vendor_case_pack')->nullable();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->tinyInteger('backup')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_vendor');
    }
}
