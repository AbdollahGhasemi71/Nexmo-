<?php

namespace Cyaxaress\Common\Responses;

use Illuminate\Http\Response;

class AjaxResponses
{
    public static function SuccessResponse()
    {
        return response()->json(['message' => 'عملیات با موفقیت انجام شد.'], Response::HTTP_OK);
    }

    public static function FailResponse()
    {
        return response()->json(['message' => 'عملیات با موفقیت انجام نشد!!.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
