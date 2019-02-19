<?php


namespace SimpelDigitaal\RoutingModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpelDigitaal\RoutingModels\Contract\IsRoutable;

class RoutingRecord extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param IsRoutable $model
     * @return RoutingRecord
     */
    public static function createRoute(IsRoutable $model)
    {
        $routeRecord = new static([
            'slug' => $model->getRouteSlug(),
            'method' => $model->getRouteMethod(),
            'subject_id' => $model->getAttributeValue('id'),
            'subject_type' => $model->getMorphClass(),

        ]);
        $routeRecord->save();

        return $routeRecord;
    }

    public function getAction()
    {
        return [RouteResolver::class, 'resolve'];
    }

    public function getMethods()
    {
       return array_filter(explode(',', $this->method), 'strtoupper');
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return config('routingmodels.table', 'routes');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }


}