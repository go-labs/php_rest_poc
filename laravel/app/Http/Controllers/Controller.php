<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Mail;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    const BAD_REQUEST = 400;
	const NOT_FOUND = 404;
	const NO_CONTENT = 204;
	const CREATED = 201;
	const OK = 200;
	const UNPROCCESSABLE = 422;
	const EMAIL_SEND = 'jd.golabs@gmail.com';

	protected function error($errors, $statusCode)
	{
		return response()->json([
        	'success' => false,
        	'errors' => $errors
    	], $statusCode);
	}

	protected function response($data, $statusCode)
	{
		return response()->json([
        	'success' => true,
        	'data' => $data
    	], $statusCode);
	}

	protected function email_sender($template, $data, $subject)
	{
		$email = self::EMAIL_SEND;
		Mail::queue($template, ['data' => $data], function ($m) use($email, $subject){
            $m->to( $email, $email)->subject($subject);
        });
	}
}
