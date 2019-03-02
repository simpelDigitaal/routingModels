<?php


namespace SimpelDigitaal\RoutingModels\Contract;


interface IsRoutable
{


    /**
     * Returns the method(s) for this routingModel:
     * Methods seperated with a comma.
     * Posible options: GET HEAD PUT POST DELETE
     *
     * @return string
     */
    public function getRouteMethod():string;

    /**
     * Returns the method to use to resolve this routable Model.
     *
     * ex. `RouteResolver@resolve` or `[RouteResolver::class, 'resolve']`
     *
     * @return string
     */


    /**
     * returns the slug for the url.
     *
     * @return string
     */
    public function getRouteSlug(): string;

    /**
     * Returns the title for this this model
     *
     * @return string|null
     */
    public function getRouteTitle(): ?string;


    /**
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string;

}