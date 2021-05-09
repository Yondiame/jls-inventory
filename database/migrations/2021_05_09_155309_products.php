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
            $table->string('internal_title');
            $table->string('restockable');
            $table->string('moq_pieces');
            $table->integer('buffer_days');
            $table->integer('minimum_level');
            $table->text('product_url');
            $table->text('note_for_next_order');
            $table->string('case_pack_pieces');
            $table->string('pieces_per_internal_box');
            $table->string('boxes_per_case');
            $table->string('tags_and_info');
            $table->string('hazmat');
            $table->string('active');
            $table->string('ignore_until');
            $table->text('notes');
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
