<?php

use App\Controllers\PriceIntervalsController;
use App\Persistences\PriceIntervalPersistence;
use App\Service\PriceIntervalService;

class ioc
{

    public function injectPriceIntervalController()
    {
        $builderContainer = new \DI\ContainerBuilder();
        $builderContainer->useAutowiring(true);
        $container = $builderContainer->build();

        $container->set('PriceIntervalsPersistence', PriceIntervalPersistence::class);
        $container->set('PriceIntervalsService', PriceIntervalService::class);

        return $container->get(PriceIntervalsController::class);
    }

    public function injectPriceIntervalService()
    {
        $builderContainer = new \DI\ContainerBuilder();
        $builderContainer->useAutowiring(true);
        $container = $builderContainer->build();

        $container->set('PriceIntervalsPersistence', PriceIntervalPersistence::class);

        return $container->get(PriceIntervalService::class);
    }
}