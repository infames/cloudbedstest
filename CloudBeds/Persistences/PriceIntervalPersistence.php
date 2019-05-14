<?php

namespace App\Persistences;


use App\Models\PriceInterval;

class PriceIntervalPersistence
{
    /**
     * @return mixed
     */
    public function listPriceIntervals()
    {
        return PriceInterval::orderBy('start_date', 'desc')->get();
    }

    /**
     * @param $interval
     * @param $ids
     * @return mixed
     */
    public function save($interval, $ids)
    {
        $deleted = PriceInterval::destroy($ids);
        $saved = PriceInterval::insert($interval);
        return $saved;
    }

    /**
     * @param $ids
     * @return int
     */
    public function destroy($ids)
    {
        $deleted = PriceInterval::destroy($ids);
        return $deleted;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return PriceInterval::find($id);
    }

}