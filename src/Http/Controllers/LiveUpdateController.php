<?php

namespace Wehaa\Liveupdate\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class LiveUpdateController extends Controller
{
    use AuthorizesRequests;

    public function update(NovaRequest $request)
    {
        $attribute = $request->get('attribute', null);

        if ($attribute === 'ComputedField') {
            $templateId = $request->get('parentRouterResourceId');
            $attributeId = $request->get('parentId');
            $sortValue = $request->get('value');

            DB::update('UPDATE template_attributes SET order_number = ? WHERE attribute_id = ? AND template_id = ?', [(int)$sortValue, (int)$attributeId, (int)$templateId]);
        } else {
            $model = $request->model()->find($request->id);
            
            try {
                $this->authorize('update', $model);
            } catch(\Exception $e) {
                throw new \RuntimeException('No access');
            }

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
}
