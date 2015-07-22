<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
    const BAD_REQUEST = 400;
	const NOT_FOUND = 404;
	const NO_CONTENT = 204;
	const CREATED = 201;
	const OK = 200;
	const UNPROCESSABLE = 422;

	protected function error($errors, $statusCode)
	{
		return response()->json(array(
        	'success' => false,
        	'errors' => $errors
    	), $statusCode);
	}
	protected function response($data, $statusCode){
		return response()->json(array(
        	'success' => true,
        	'data' => $data
    	), $statusCode);
	}

}
