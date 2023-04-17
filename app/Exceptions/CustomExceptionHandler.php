<?php

// namespace App\Exceptions;

// use Exception;
// use Symfony\Component\HttpKernel\Exception\HttpException;
// use Throwable;  
// class CustomExceptionHandler extends Exception
// {


//     public function render($request, Throwable $exception)
//     {
//         if ($exception instanceof HttpException) {
//             $statusCode = $exception->getStatusCode();
//             $message = $exception->getMessage() ?: 'Sorry, something went wrong.';
//             return response()->json(['error' => $message], $statusCode);
//         }
//         return parent::render($request, $exception);
//     }
    
//}