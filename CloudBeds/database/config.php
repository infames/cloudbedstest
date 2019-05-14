<?php

namespace Database\Config;
//If you want the errors to be shown
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    "driver" => "mysql",
    "host" => "mysql",
    "database" => "cloud-beds",
    "username" => "root",
    "password" => "root"
]);


//Make this Capsule instance available globally.
$capsule->setAsGlobal();

// Setup the Eloquent ORM.
$capsule->bootEloquent();