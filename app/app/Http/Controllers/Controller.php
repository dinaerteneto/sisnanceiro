<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Respond with fractal wrapper
     *
     * @param array $data
     * @return Response
     */
    public function fractal($data)
    {
        return response()->json($data->toArray());
    }    

    /**
     * Respond with success status and json data
     *
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function apiSuccess($data = [], $headers = [])
    {
        return response()->json($data, 200, $headers);
    }    
}
