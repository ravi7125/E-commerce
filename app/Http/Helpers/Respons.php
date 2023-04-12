<?php
// Api response helper file 
// This helper file to register in composer.json file under the "autoload-dev > files" section this type 
// "files": [ "app/Http/Helpers/Respons.php" ] 

if(!function_exists('ok')){
    function ok( $data = [], $status = 200,$message = null,)
    {
        $response = [
            'status'    =>  $status,
            'message'   =>  $message ?? 'success',
            'data'      =>  $data
        ];

        return response()->json($response,$status);
    }
}

if(!function_exists('error')){
    function error($message = null, $data = [], $type = null)
    {
        $status = 400;
        $message ?? $message = 'Server error, please try again later';

        switch ($type) {
            case 'validation':
                $status  = 422;
                $message =   $message ?? 'Validation Failed please check the request attributes and try again request';
            break;

            case 'unauthenticated':
                $status  = 401;
                $message =  $message ?? 'User token has been expired new token insert and again request send';
            break;

            case 'notfound':
                $status  = 404;
                $message = $message ?? 'Sorry no results query for your request';
            break;

            case 'forbidden':
                $status  = 403;
                $message =  $message ??  'You don\'t have permission to access this content';
            break;

            default:
                $status = 400;
                $message ?? $message = 'Server error, please try again later';
            break;
        }

        $response = [
            'status'    =>  $status,
            'message'   =>  $message,
            'data'      =>  $data
        ];

        return response()->json($response,$status);
    }
}