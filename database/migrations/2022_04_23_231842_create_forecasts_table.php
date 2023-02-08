<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForecastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->char('forecast_id',4)->primary();
            $table->tinyinteger('weather_id')->unsigned();
            $table->char('fk_JIS_ac',2);
            $table->string('weather',10);
            $table->double('temp_max',4,2);
            $table->double('temp_min',4,2);
            $table->double('rain_per',3,2);
            $table->date('weather_date');
            $table->timestamps();
            
            $table->foreign('fk_JIS_ac')
            ->references('JIS_ac')
            ->on('prefectures')
            ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forecasts');
    }
}
