<?php
namespace App\Html;

class Response 
{
    const STATUES = [

        100 => "Continue",
        200 => "OK",
        201 => "Created",
        400 => "Bad Request",
        404 => "Not Found"
    ];

    public function __call($name, $arguments): void
    {
        $this->response(['data' => []], 404);
    }

    static function response(array $data, $code = 200, $message = ''): void
        {
            if(isset(self::STATUES[$code])) {
                http_response_code($code);
                if(!$message){
                    $message = self::STATUES[$code];
                }
                $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0';
                header($protocol . ' ' . $code . ' ' . self::STATUES[$code]);
            }
            header('Content-Type: applicatiob/json');
            $response = [
                'data' => $data,
                'message' => $message,
                'code' => $code,
            ];
            echo json_encode($response, JSON_THROW_ON_ERROR);
        
        }

        
    

}
