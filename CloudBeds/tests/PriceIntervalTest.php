<?php

class PriceIntervalTest
{

    public function testList()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $priceIntervalList = $priceInterval->listIntervals();

        return assert(is_object($priceIntervalList) || $priceIntervalList = [], 'The list have items');
    }

    public function testSave()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $startDate = '2010-11-04';
        $endDate = '2010-11-21';
        $price = 15;

        $priceIntervalMessage = $priceInterval->save($startDate, $endDate, $price);

        return assert('The interval was successfully saved' == $priceIntervalMessage, 'Interval was saved');
    }

    public function testMultipleUnionInterval()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $startDate = '2040-11-01';
        $endDate = '2040-11-05';
        $price = 15;

        $priceInterval->save($startDate, $endDate, $price);

        $startDate = '2040-11-20';
        $endDate = '2040-11-25';
        $price = 15;

        $priceInterval->save($startDate, $endDate, $price);

        $startDate = '2040-11-04';
        $endDate = '2040-11-21';
        $price = 45;

        $priceInterval->save($startDate, $endDate, $price);

        $startDate = '2040-11-03';
        $endDate = '2040-11-21';
        $price = 15;

        $priceInterval->save($startDate, $endDate, $price);

        $exists = \App\Models\PriceInterval::where('start_date', '11/01/40')
            ->where('end_date', '11/25/40')->where('price', 15)->exists();

        return assert($exists, 'Correct Multiple Union');
    }

    public function testUnionInterval()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $startDate = '2060-11-01';
        $endDate = '2060-11-10';
        $price = 15;

        $priceInterval->save($startDate, $endDate, $price);

        $startDate = '2060-11-09';
        $endDate = '2060-11-15';
        $price = 15;

        $priceInterval->save($startDate, $endDate, $price);

        $exists = \App\Models\PriceInterval::where('start_date', '11/01/60')
            ->where('end_date', '11/15/60')->where('price', 15)->exists();

        return assert(count($exists) != 0, 'Union interval');
    }

    public function testFindInterval()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $startDate = '2060-11-03';
        $endDate = '2060-11-21';
        $price = 15;

        $interval = \App\Models\PriceInterval::create(['start_date' => $startDate, 'end_date' => $endDate, 'price' => $price]);

        $priceIntervalList = $priceInterval->find($interval->id);

        return assert(is_object($priceIntervalList), 'The Object exist');
    }

    public function testUpdate()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $startDate = '2080-11-03';
        $endDate = '2060-11-21';
        $price = 15;

        $existsOldInterval = \App\Models\PriceInterval::where('start_date', $startDate)
            ->where('end_date', $endDate)->where('price', $price)->exists();

        $interval = \App\Models\PriceInterval::create(['start_date' => $startDate, 'end_date' => $endDate, 'price' => $price]);

        $startDate = '2090-11-03';
        $endDate = '2090-11-21';
        $price = 15;

        $priceInterval->update($interval->id, $startDate, $endDate, $price);

        $existsNewInterval = \App\Models\PriceInterval::where('start_date', $startDate)
            ->where('end_date', $endDate)->where('price', $price)->exists();

        return assert(!$existsOldInterval && $existsNewInterval, 'The interval was updated');
    }

    public function testDelete()
    {
        $container = new ioc();
        $priceInterval = $container->injectPriceIntervalService();

        $startDate = '2080-11-03';
        $endDate = '2080-11-21';
        $price = 15;

        $interval = \App\Models\PriceInterval::create(['start_date' => $startDate, 'end_date' => $endDate, 'price' => $price]);

        $priceInterval->delete($interval->id);

        $exists = \App\Models\PriceInterval::where('start_date', $startDate)
            ->where('end_date', $endDate)->where('price', $price)->exists();

        return assert(!$exists, 'Delete Interval');
    }

}