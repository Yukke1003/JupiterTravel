<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefectures', function (Blueprint $table) {
            $table->bigIncrements('prefecture_id');
            $table->string('prefectJP',4);
            $table->string('prefectEN',15);
            $table->char('JIS_ac',2)->unique();
            $table->double('lat',10,8);
            $table->double('lon',11,8);
            $table->boolean('is_weather');
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
        Schema::dropIfExists('prefectures');
    }
}
