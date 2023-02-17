<?php

namespace App\Http\Requests;

use App\Services\TagService;
use App\Support\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SyncTagRequest extends FormRequest
{
    public $tagsArray;

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
            'tags' => [
                function ($attribute, $value, $fail) {
                    $this->tagsArray = Helper::iexplode(TagService::DEFAULT_SEPARATORS, $value);
                    $this->tagsArray = Helper::filterArray($this->tagsArray);

                    $innerValidator = Validator::make(['tags' => $this->tagsArray], ['tags.*' => 'max:60'], [], ['tags.*' => $attribute]);

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
