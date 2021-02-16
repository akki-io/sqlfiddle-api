<?php

namespace App\Http\Controllers;

use function config;

class HealthController extends Controller
{
    /**
     * Get the health of the api.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->respondStandard([
            'message' => 'Request successfully completed.',
            'version' => config('app.version'),
        ]);
    }
}
