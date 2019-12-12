<?php

namespace Wehaa\Liveupdate\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class LiveUpdateController extends Controller
{
    public function update(NovaRequest $request)
    {
        $model = $request->model()->find($request->id);

        switch($model->attribute->attribute_type ?? 'text') {
            case "text":
            default:
                $value = (string)$request->value;

                if (is_string($value) === false) {
                    throw new \RuntimeException('Invalid param');
                }
            break;
            case "integer":
                $value = $request->value;

                if (is_numeric($value) === false) {
                    throw new \RuntimeException('Invalid param');
                }
            break;
            case "float":
                $value = floatval($request->value);
                
                if (is_numeric($request->value) === false) {
                    throw new \RuntimeException('Invalid param');
                }
            break;
        }

        $model->{$request->attribute} = $value;
        $model->save();
    }
}
