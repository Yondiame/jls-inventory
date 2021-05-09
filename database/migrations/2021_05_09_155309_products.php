<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('core_number');
            $table->string('internal_title')->nullable();
            $table->string('restockable')->nullable();
            $table->string('moq_pieces')->nullable();
            $table->integer('buffer_days')->nullable();
            $table->string('minimum_level')->nullable();
            $table->text('product_url')->nullable();
            $table->text('note_for_next_order')->nullable();
            $table->string('case_pack_pieces')->nullable();
            $table->string('pieces_per_internal_box')->nullable();
            $table->string('boxes_per_case')->nullable();
            $table->string('tags_info')->nullable();
            $table->string('hazmat')->nullable();
            $table->string('active')->nullable();
            $table->string('ignore_until')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('products');
    }
}
