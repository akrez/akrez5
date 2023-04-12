<?php

namespace App\Http\Requests;

use App\Services\MetaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreMetaWithoutKeyRequest extends FormRequest
{
    public $contentAsArray;

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
            'content' => [
                function ($attribute, $value, $fail) {
                    $this->contentAsArray = MetaService::parseWithoutKey($value);
                    //
                    $innerValidator = Validator::make([
                        'content' => $this->contentAsArray,
                    ], [
                        'content.*' => 'max:60',
                    ], [
                        //
                    ], [
                        'content.*' => $attribute,
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
