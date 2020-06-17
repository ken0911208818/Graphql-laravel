<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function responseWithJson($request, array $info)
    {
        // 整理傳入參數
        $apiCode = ($info['code'] && strlen($info['code']) == 6) ? $info['code'] : config('apiCode.notAPICode'); // 回傳前端的 api code
        $result = $info['result'] ?? ''; // 回傳前端的資料
        $error = $info['error'] ?? ''; // 錯誤訊息(紀錄 log 使用)

        // 整理要回傳的格式
        $statusCode = substr($apiCode, 0, 3);
        $response = ['result' => $result, 'code' => $apiCode];
        if ($statusCode != 200) { // 非 200 的一律寫入到 log
            $this->addErrorLog($request, $response, $error);
            if (env('APP_DEBUG') == true) {
                $response['error'] = $error;
            }
        }
        return response()->json($response, $statusCode);
    }

    protected function addErrorLog($request, array $response, string $error)
    {
        $info = [
            'time'       => \Carbon\Carbon::now()->toDateTimeString(),
            'path'       => $request->path(),
            'method'     => $request->method(),
            'parameters' => $request->all(),
            'ip'         => $request->ip(),
        ];
        Log::debug("========Error Log Start========");
        Log::info(json_encode($info));
        Log::debug(json_encode($response));
        Log::error($error);
        Log::debug("========Error Log End==========");
    }
}
