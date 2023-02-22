<?php

namespace App\Http\Requests;

use App\Services\PropertyService;
use App\Support\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SyncPropertyRequest extends FormRequest
{
    public $propertiesArray;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'properties' => [
                function ($attribute, $value, $fail) {
                    $properties = [];
                    //
                    $linesArray = Helper::iexplode(PropertyService::DEFAULT_LINE_SEPARATOR, $value);
                    $linesArray = Helper::filterArray($linesArray);
                    foreach ($linesArray as $line) {
                        $lineArray = Helper::iexplode(PropertyService::DEFAULT_KEY_VALUES_SEPARATOR, $line, 2);
                        $lineArray = Helper::filterArray($lineArray);
                        if (2 != count($lineArray)) {
                            continue;
                        }
                        //
                        $lineKey = $lineArray[0];
                        //
                        $valuesArray = Helper::iexplode(PropertyService::DEFAULT_VALUES_SEPARATORS, $lineArray[1]);
                        $valuesArray = Helper::filterArray($valuesArray);
                        if (empty($valuesArray)) {
                            continue;
                        }
                        //
                        if (!isset($properties[$lineKey])) {
                            $properties[$lineKey] = [
                                'key' => $lineKey,
                                'values' => [],
                            ];
                        }
                        $properties[$lineKey]['values'] = array_merge($properties[$lineKey]['values'], $valuesArray);
                        $properties[$lineKey]['values'] = Helper::filterArray($properties[$lineKey]['values']);
                    }
                    //
                    $this->propertiesArray = array_values($properties);
                    //
                    $maxLength = 60;
                    //
                    $innerValidator = Validator::make(['properties' => $this->propertiesArray,], [
                        'properties.*.key' => 'max:' . $maxLength,
                        'properties.*.values.*' => 'max:' . $maxLength,
                    ], [], [
                        'properties.*.key' => 'key',
                        'properties.*.values.*' => 'value',
                    ]);
                    if ($innerValidator->fails()) {
                        foreach ($innerValidator->errors()->all() as $error) {
                            $fail($error);
                        }
                    }
                },
            ],
        ];
    }
}
