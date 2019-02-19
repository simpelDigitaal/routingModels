<?php


namespace SimpelDigitaal\RoutingModels\Contract;


interface IsRoutable
{

    public function getRouteMethod();

    public function getRouteSlug();

}