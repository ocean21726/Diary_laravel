<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('list', function ($list, $pageData=[], $alertMessage='') {
            return Response::json([
                'code' => 200,
                'success' => true,
                'data' => $list,
                'pageData' => $pageData,
                'alertMessage' => $alertMessage
            ]);
        });

        Response::macro('object', function ($data, $alertMessage='') {
            return Response::json([
                'code' => 200,
                'success' => true,
                'data' => $data,
                'alertMessage' => $alertMessage
            ]);
        });

        Response::macro('success', function ($alertMessage=null) {
            return Response::json(array_filter([
                'code' => 200,
                'success' => true,
                'alertMessage' => $alertMessage
            ]));
        });

        Response::macro('fail', function ($alertMessage=null) {
            return Response::json([
                'code' => 200,
                'success' => false,
                'alertMessage' => $alertMessage
            ]);
        });
    }
}
