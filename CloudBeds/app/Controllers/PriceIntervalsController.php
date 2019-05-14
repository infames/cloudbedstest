<?php

namespace App\Controllers;

use App\Service\PriceIntervalService;
use Carbon\Carbon;

/**
 * Class PriceIntervalsController
 * @package App\Controllers
 */

class PriceIntervalsController
{
    private $priceIntervalService;

    /**
     *
     * this is the construct of the class
     *
     * PriceIntervalsController constructor.
     * @param PriceIntervalService $priceIntervalService
     */
    public function __construct(PriceIntervalService $priceIntervalService)
    {
        $this->priceIntervalService = $priceIntervalService;
    }

    /**
     *
     * this function send the request information to the
     * save function on service or the update function
     * depending of the data in request
     *
     * @param $request
     * @return array
     */
    public function savePriceIntervals($request)
    {
        $response = null;
        $errors = [];

        $startDate = $request->param('start_date', null);
        $endDate = $request->param('end_date', null);
        $price = $request->param('price', null);
        $id = $request->param('id', null);

        if (!is_numeric($price)) {
            $errors [] = 'Price have to be a number';
        }

        if (empty($price)) {
            $errors [] = 'You have to provide a valid price ';
        }

        try {

            $tempStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
            $tempEndDate = Carbon::createFromFormat('Y-m-d', $endDate);

            if ($tempStartDate->greaterThan($tempEndDate)){
                $errors [] = 'Start dta cant be greater than end_date';
            }

        } catch (\Exception $e) {

            $errors [] = 'You have to provide a valid date format';
        }

        if (empty($errors)) {

            if ($id) {
                return json_encode($this->setUpResponse($this->priceIntervalService->update($id, $startDate, $endDate, $price), $errors));
            }

            $response = $this->priceIntervalService->save($startDate, $endDate, $price);
        }

        return json_encode($this->setUpResponse($response, $errors));


    }

    /**
     *
     * this return the list of intervals
     *
     * @return array
     */
    public function listIntervals()
    {
        return json_encode($this->setUpResponse($this->priceIntervalService->listIntervals(), []));
    }

    /**
     *
     * this sen the id of the interval to the service so
     * it could be deleted
     *
     * @param $request
     * @return array
     */
    public function deleteInterval($request)
    {
        $errors = [];
        $response = null;

        if (!is_numeric($request->id)) {
            $errors [] = 'Id have to be a number';
        }

        if (empty($request->id)) {
            $errors [] = 'You have to provide a valid id ';
        }

        if (!$errors)
        {
            if ($this->priceIntervalService->delete($request->id)) {
                $response = 'The interval was deleted';
            } else {
                $response = 'The interval does not exist';
            }
        }

        return json_encode($this->setUpResponse($response, $errors));
    }

    /**
     *
     * this function send the id of the interval to return
     * his information
     *
     * @param $request
     * @return array
     */
    public function findInterval($request)
    {
        $errors = [];
        $interval = null;
        $response = null;

        if (!is_numeric($request->id)) {
            $errors [] = 'Id have to be a number';
        }

        if (empty($request->id)) {
            $errors [] = 'You have to provide a valid id ';
        }

        if (!$errors) {
            $interval = $this->priceIntervalService->find($request->id);

            if ($interval) {
                $response = $interval;
            } else {
                $response = 'the interval does not exist';
            }
        }


        return json_encode($this->setUpResponse($response, $errors));
    }

    /**
     *
     * this set up all the responses
     *
     * @param $response
     * @param $errors
     * @return array
     */
    public function setUpResponse($response, $errors)
    {
        $responseArray = [

            'response' => $response,
            'errors' => $errors

        ];

        return $responseArray;

    }

}