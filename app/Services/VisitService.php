<?php

namespace App\Services;

use App\Models\Visit;
use App\Support\Result;

class VisitService
{
    public static function store($blogName, $httpCode, $ip, $method, $url, $userAgent): Result
    {
        $visit = new Visit();
        $visit->blog_name = $blogName;
        $visit->http_code = $httpCode;
        $visit->ip = $ip;
        $visit->method = $method;
        $visit->url = $url;
        $visit->user_agent = $userAgent;
        return Result::make($visit->save(), [], $visit);
    }
}
