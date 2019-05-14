<?php

header("Access-Control-Allow-Origin: *");

$klein = new \Klein\Klein();

$klein->respond(function ($request, $response, $service, $app) {
    $app->register('container', function () {
        $container = new ioc();
        return $container->injectPriceIntervalController();
    });
});

$klein->respond('POST', '/api/intervals/save', function ($request, $response, $service, $app) {
    return $app->container->savePriceIntervals($request);
});

$klein->respond('GET', '/api/intervals/list', function ($request, $response, $service, $app) {
    return $app->container->listIntervals();
});

$klein->respond('GET', '/api/intervals/delete/[i:id]', function ($request, $response, $service, $app) {
    return $app->container->deleteInterval($request);
});

$klein->respond('GET', '/api/intervals/[i:id]', function ($request, $response, $service, $app) {
    return $app->container->findInterval($request);
});

$klein->respond('GET', '/migrations', function ($request, $response, $service, $app) {
    require '../database/migrations.php';
});


$klein->dispatch();
