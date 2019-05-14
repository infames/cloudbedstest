<?php

require "config.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->drop('price_intervals');

Capsule::schema()->create('price_intervals', function ($table) {
    $table->increments('id');
    $table->date('start_date');
    $table->date('end_date');
    $table->double('price');
    $table->timestamps();
});
