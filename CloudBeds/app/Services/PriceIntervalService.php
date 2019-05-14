<?php

namespace App\Service;

use App\Models\PriceInterval;
use App\Persistences\PriceIntervalPersistence;
use Carbon\Carbon;

class PriceIntervalService
{
    private $priceIntervalPersistence;

    public function __construct(PriceIntervalPersistence $priceIntervalPersistence)
    {
        $this->priceIntervalPersistence = $priceIntervalPersistence;
    }

    /**
     *
     *this function list all the intervals
     *
     * @return mixed
     */
    public function listIntervals()
    {
        return $this->priceIntervalPersistence->listPriceIntervals();
    }

    /**
     *
     * this function return a interval for his id
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->priceIntervalPersistence->find($id);
    }

    /**
     *
     * this functions updates a existing interval
     *
     * @param $id
     * @param $startDate
     * @param $endDate
     * @param $price
     * @return string
     */
    public function update($id, $startDate, $endDate, $price)
    {
        $this->delete($id);
        $this->save($startDate, $endDate, $price);

        return 'the interval was saved';
    }

    /**
     *
     * this function deletes a existing interval
     *
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->priceIntervalPersistence->destroy($id);
    }

    /**
     *
     *
     * this function save a new interval depending of the intervals that already exists
     *
     * @param $startDate
     * @param $endDate
     * @param $price
     * @return string
     */
    public function save($startDate, $endDate, $price)
    {

        // first we get all the intervals on the database
        $deletedIds = [];
        $union = false;
        $unionInterval = null;
        $newIntervals = [];
        $newStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $newEndDate =Carbon::createFromFormat('Y-m-d', $endDate);
        $intervals = $this->priceIntervalPersistence->listPriceIntervals();
        $exist = PriceInterval::where('start_date', $startDate)->where('end_date', $endDate)->where('price', $price)->first();

        // is there are none intervals in the database we directly insert
        if (!count($intervals)) {

            $newInterval['start_date'] = $newStartDate->toDateString();
            $newInterval['end_date'] = $newEndDate->toDateString();
            $newInterval['price'] = $price;
            $this->priceIntervalPersistence->save($newInterval, $deletedIds);

            die();
        }

        if ($exist) {
            $deletedIds[] = $exist->id;
        }

        foreach ($intervals as $interval) {

            $intervalStartDate = Carbon::createFromFormat('Y-m-d', $interval->start_date);
            $intervalEndDate = Carbon::createFromFormat('Y-m-d', $interval->end_date);

            // is the interval have the same price as the new interval we enter to this part of the function

            if ($interval->price == $price) {

                $tempIntervalStartDateOneDayBefore = Carbon::createFromFormat('Y-m-d', $interval->start_date)->subDay();
                $tempIntervalStartDateOneDayAfter = Carbon::createFromFormat('Y-m-d', $interval->start_date)->addDay();
                $tempIntervalEndDateOneDayBefore = Carbon::createFromFormat('Y-m-d', $interval->end_date)->subDay();
                $tempIntervalEndDateOneDayAfter = Carbon::createFromFormat('Y-m-d', $interval->end_date)->addDay();

                // if exist a interval equal to the new interval we just skip it

                if ($intervalStartDate->equalTo($newStartDate) &&
                    $intervalEndDate->equalTo($newEndDate) &&
                    $price == $interval->price) {

                    continue;
                }

                // if the new interval exist already inside another there is no need to do more

                if ($newEndDate->between($intervalStartDate, $intervalEndDate) &&
                    $newStartDate->between($intervalStartDate, $intervalEndDate)) {
                    die();
                }

                // if the new interval have a gap of 1 day or the new end date is equal to the start date, then is going to be a union
                // and we have to do the same process with the new interval

                if ($newEndDate->equalTo($intervalStartDate) || $newEndDate->equalTo($intervalEndDate) ||
                    $newEndDate->equalTo($tempIntervalStartDateOneDayAfter) || $newEndDate->equalTo($tempIntervalEndDateOneDayAfter) ||
                    $newEndDate->equalTo($tempIntervalStartDateOneDayBefore) || $newEndDate->equalTo($tempIntervalStartDateOneDayAfter)) {

                    $newInterval = $this->setUpInterval($newStartDate->toDateString(), $intervalEndDate->toDateString(), $price);
                    $deletedIds [$interval->id] = $interval->id;

                    $this->priceIntervalPersistence->save($newIntervals, $deletedIds);
                    $this->save($newInterval['start_date'], $newInterval['end_date'], $newInterval['price']);

                }

                // if the new interval have a gap of 1 day or the new start date is equal to the start date, then is going to be a union
                // and we have to do the same process with the new interval

                if ($newStartDate->equalTo($intervalEndDate) || $newStartDate->equalTo($intervalStartDate) ||
                    $newStartDate->equalTo($tempIntervalEndDateOneDayBefore) || $newStartDate->equalTo($tempIntervalStartDateOneDayBefore) ||
                    $newStartDate->equalTo($tempIntervalEndDateOneDayAfter) || $newStartDate->equalTo($tempIntervalStartDateOneDayAfter)) {

                    $newInterval = $this->setUpInterval($intervalStartDate->toDateString(), $newEndDate->toDateString(), $price);
                    $deletedIds [$interval->id] = $interval->id;

                    $this->priceIntervalPersistence->save($newIntervals, $deletedIds);
                    $this->save($newInterval['start_date'], $newInterval['end_date'], $newInterval['price']);

                }

                // if the new interval start date  is between the interval and the new end date is not

                if ($newStartDate->between($intervalStartDate, $intervalEndDate) &&
                    !$newEndDate->between($intervalStartDate, $intervalEndDate)) {

                    $newIntervals[$intervalStartDate->toDateString() . $newEndDate->toDateString() . $price] =
                        $this->setUpInterval($intervalStartDate->toDateString(), $newEndDate->toDateString(), $price);

                    $deletedIds [$interval->id] = $interval->id;

                    $this->priceIntervalPersistence->destroy($deletedIds);
                    continue;
                }

                // is the interval have a different price as the new interval we enter to this part of the function

            } elseif ($interval->price != $price) {

                $tempStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
                $tempEndDate = Carbon::createFromFormat('Y-m-d', $endDate);

                //if exist we just skip this if doesnt we need to create it because the new interval have priority

                if (!$exist) {

                    $newIntervals[$newStartDate->toDateString() . $newEndDate->toDateString() . $price] =
                        $this->setUpInterval($newStartDate->toDateString(), $newEndDate->toDateString(), $price);
                }

                //if the interval is inside the new interval then we just delete this interval

                if ($newEndDate->greaterThan($intervalEndDate)
                    && $newStartDate->lessThan($intervalStartDate)) {

                    $deletedIds [$interval->id] = $interval->id;

                    continue;
                }

                //if the new interval is between  the interval que have to split and create two more intervals

                if ($newEndDate->between($intervalStartDate, $intervalEndDate)
                    && $newStartDate->between($intervalStartDate, $intervalEndDate)) {

                    $tempStartDateOneDayBefore = $tempStartDate->subDay();
                    $tempEndDateOneDayAfter = $tempEndDate->addDay();

                    if ($intervalStartDate->equalTo($tempStartDateOneDayBefore)) {

                        $newIntervals[$intervalStartDate->toDateString() . $tempStartDateOneDayBefore->toDateString() . $interval->price] =
                            $this->setUpInterval($intervalStartDate->toDateString(), $tempStartDateOneDayBefore->toDateString(), $interval->price);
                    }

                    $newIntervals[$tempEndDateOneDayAfter->toDateString() . $intervalEndDate->toDateString() . $interval->price] =
                        $this->setUpInterval($tempEndDateOneDayAfter->toDateString(), $intervalEndDate->toDateString(), $interval->price);

                    $deletedIds [$interval->id] = $interval->id;
                    continue;
                }

                //if only the new end date is between the interval we have to create a interval that start one day after  the new end date

                if ($newEndDate->between($intervalStartDate, $intervalEndDate)) {

                    $tempEndDateOneDayAfter = $tempEndDate->addDay();

                    $newIntervals[$tempEndDateOneDayAfter->toDateString() . $intervalEndDate->toDateString() . $interval->price] =
                        $this->setUpInterval($tempEndDateOneDayAfter->toDateString(), $intervalEndDate->toDateString(), $interval->price);

                    $deletedIds [$interval->id] = $interval->id;
                    continue;
                }

                //if only the new start date is between the interval we have to create a interval that ends one day before  the new start date


                if ($newStartDate->between($intervalStartDate, $intervalEndDate)) {

                    $tempStartDateOneDayBefore = $tempStartDate->subDay();

                    $newIntervals[$intervalStartDate->toDateString() . $tempStartDateOneDayBefore->toDateString() . $interval->price] =
                        $this->setUpInterval($intervalStartDate->toDateString(), $tempStartDateOneDayBefore->toDateString(), $interval->price);

                    $deletedIds [$interval->id] = $interval->id;
                    continue;
                }

            }

            //if the interval is not between any interval then we just create it

            if (!$newStartDate->between($intervalStartDate, $intervalEndDate) &&
                !$newEndDate->between($intervalStartDate, $intervalEndDate) && !$union) {

                $newIntervals[$newStartDate->toDateString() . $newEndDate->toDateString() . $price] =
                    $this->setUpInterval($newStartDate->toDateString(), $newEndDate->toDateString(), $price);

                continue;
            }

        }

        // we che if there are new intervals an then we just save it
        if ($newIntervals) {
            $this->priceIntervalPersistence->save($newIntervals, $deletedIds);
        }

        die();

    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $price
     * @return array
     */
    public function setUpInterval($startDate, $endDate, $price)
    {
        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'price' => $price
        ];
    }

}