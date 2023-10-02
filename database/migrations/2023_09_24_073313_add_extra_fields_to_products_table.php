<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->after('shipping_price', function (Blueprint $table){
                $table->boolean('best')->default(false);
                $table->boolean('trending')->default(false);
                $table->boolean('latest')->default(false);
                $table->boolean('is_discount')->default(false);
                $table->bigInteger('discount_amount')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['best','trending','latest','is_discount','discount_amount']);
        });
    }
}