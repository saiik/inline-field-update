<?php

namespace Wehaa\Liveupdate\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class LiveUpdateController extends Controller
{
    public function update(NovaRequest $request)
    {
        $model = $request->model()->find($request->id);

        switch($model->attribute_type ?? 'text') {
            case "text":
            default:
                if (is_string($request->value) === false) {
                    throw new \RuntimeException('Invalid param');
                }
            break;
            case "integer":
                if (is_integer($request->value) === false) {
                    throw new \RuntimeException('Invalid param');
                }
            break;
            case "float":
                if (is_float($request->value) === false) {
                    throw new \RuntimeException('Invalid param');
                }
            break;
        }

        $model->{$request->attribute} = $request->value;
        $model->save();
    }
}
