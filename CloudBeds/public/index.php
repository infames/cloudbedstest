<?php


// requires vendor for some libraries
require '../vendor/autoload.php';

// require database connection
require "../database/config.php";

// include persistences

include '../app/Persistences/PriceIntervalPersistence.php';

// include services
include '../app/Services/PriceIntervalService.php';

// include controllers
include '../app/Controllers/PriceIntervalsController.php';

require '../app/Providers/ioc.php';

// requires routes
require '../routes/api.php';
