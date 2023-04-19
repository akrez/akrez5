<?php

namespace App\Support;

class Result
{
    public $status = false;
    public $messages;
    public $model;
    public $validator;
    public $data;

    public static function make($status = null, $messages = [], $model = null, $validator = null, $data = [])
    {
        $result = new static();

        $result->status = $status;
        $result->messages = $messages;
        $result->model = $model;
        $result->validator = $validator;
        $result->data = $data;

        return $result;
    }
}
