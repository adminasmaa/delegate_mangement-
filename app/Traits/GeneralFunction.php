<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

trait GeneralFunction
{
    /**
     * change Language
     *
     * @param $lang
     * @return bool
     */
    public function changeLang($lang): bool
    {
        $langs = ['ar', 'en'];
        if (!in_array($lang, $langs)) {
            $lang = 'en';
        }
        App::setLocale($lang);
        return true;
    }

    /**
     * get local language
     *
     * @return string
     */
    public function getLang(): string
    {
        return App::getLocale();
    }


    /**
     * @param $result
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result = [], $message, int $code = 200): JsonResponse
    {
        $response = [
            'status' => $code,
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];
        return response()->json($response);
    }

    /**
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    /*    public function sendSuccessResponse($message, int $code = 200): JsonResponse
    {
        $response = [
            'status' => $code,
            'success' => true,
            'message' => $message,
        ];

        return response()->json($response);
    }*/

    /**
     * return error response.
     *
     * @param $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($errorMessages, int $code = 404): JsonResponse
    {
        if (is_array($errorMessages))
            $response = [
                'status' => $code,
                'success' => false,
                'message' => implode(" \n ", $errorMessages),
            ];
        else
            $response = [
                'status' => $code,
                'success' => false,
                'message' => $errorMessages,
            ];
        return response()->json($response, $code);
    }
}
