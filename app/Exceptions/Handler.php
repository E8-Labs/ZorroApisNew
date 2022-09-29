<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        $fullUrl = $request->fullUrl();
         
         if (strpos($fullUrl, 'api') !== false || strpos($fullUrl, 'oauth') !== false) {
            
                if($exception instanceof AuthenticationException){
                    return response()->json(['message' => "AuthenticationException", 'success' => false], 401) ;
                }
                else
                if($exception instanceof MethodNotAllowedHttpException){
                    return response()->json(['message' => "MethodNotAllowedHttpException",'success' => false], 405) ;
                }
            }
            else{

                 if($exception instanceof AuthenticationException){
                   //// return redirect('/app'); 
                   // return response()->json( "We will be back shortly");
                 }  

            }
        return parent::render($request, $exception);
    }
}
