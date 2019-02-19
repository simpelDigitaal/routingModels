<?php


namespace SimpelDigitaal\RoutingModels;


use App\Http\Controllers\Controller;

class RouteResolver extends Controller
{

    public function resolve(RoutingRecord $record)
    {
        dd($record);
    }

}