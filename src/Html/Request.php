<?php
 
namespace App\Html;
 
use App\Repositories\CountyRepository;

use App\Repositories\CityRepository;
 
class Request {
 
    static function handle(): void {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "GET":
                self::getRequest();
                break;
            case "PUT":
                self::putRequest();
                break;
            case "DELETE":
                self::deleteRequest();
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    /**
 * @api {get} /counties Get list of counties
 * @apiname index
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} counties List of counties.
 * @apiSuccess {Number} counties.id  County id
 * @apiSuccess {String} counties.name County name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *             {"id":2,"name":"B\u00elcs-Kiskun"},
 *  *          {"id":3,'name':"Baranya"},
 *             {...}
 *             ],
 *       "message":"Ok",
 *       "status":200
 *     }
 *
 * @apiError CountyNotFound The id of county was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "data": [],
 *       "message": "Not Found",
 *       "status": "404"
 *     }
 */

 /**
 * @api {get} /counties/:id Get county with given id
 * @apiParam {Number} id Users unique ID
 * @apiname index
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} counties      List of counties.
 * @apiSuccess {Number} counties.id     County id
 * @apiSuccess {String} counties.name   County name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *             {"id":2,"name":"B\u00elcs-Kiskun"},
 *             ],
 *       "message":"Ok",
 *       "status":200
 *     }
 *
 * @apiError CountyNotFound The id of county was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "data": [],
 *       "message": "CountyNotFound",
 *       "status": "404"
 *     }
 */
 
    private static function getRequest(): void
    {
        $resourceName = self::getResourceName();
        //$cityName = self::getCityName();
        switch ($resourceName){
            case 'counties':
                $db = new CountyRepository();
                $resourceId = self::getResourceId();
                $code = 200;
                if($resourceId){
                    $entity = $db->find($resourceId);
                    Response::response($entity, $code);
                    break;
                }
 
                $entities = $db->getAll();
                if(empty($entities)){
                    $code = 404;
                }
                Response::response($entities, $code);
                break;
            case 'cities':
                $db = new CityRepository();
                $resourceId = self::getCityName();
                $cityId = self::getCityId();
                $code = 200;
                if($cityId){
                    $entity = $db->findCityId($cityId);
                    Response::response($entity, $code);
                    break;
                }
                $entities = $db->getAll();
                if(empty($entities)){
                    $code = 404;
                }    
 
            default:
                Response::response([], 404,  $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    /** 
    * @api {delete} /counties/:id delete county with given id
    * @apiParam {Number} id Users unique ID
    * @apiname delete
    * @apiGroup Counties
    * @apiVersion 1.0.0
    *
    * @apiSuccess {Object[]} counties      List of counties.
    * @apiSuccess {Number} counties.id     County id
    * @apiSuccess {String} counties.name   County name
    *
    * @apiSuccessExample {json} Success-Response:
    *     HTTP/1.1 200 OK
    *     {
    *       "data": [],
    *       "message":"No content",
    *       "status":204
    *     }
    *
    * @apiError CountyNotFound The id of county was not found.
    *
    * @apiErrorExample Error-Response:
    *     HTTP/1.1 404 Not Found
    *     {
    *       "data": [],
    *       "message": "Bad Request",
    *       "status":400
    *     }
    */

    private static function deleteRequest(): void
    {
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUES[400]);
        }
        $resourceName = self::getResourceName();
        switch ($resourceName) {
            case 'counties':
                $code = 404;
                $db = new CountyRepository();
                $result = $db->delete($id);
                if ($result) {
                    $code = 204;
                }
                Response::response([], $code);
                break;
            case 'cities':
               $code = 404;
               $db = new CityRepository();
               $result = $db->delete($id);
               if ($result) {
                   $code = 204;
               }
               Response::response([], $code);
               break;
            default:
                Response::response([], 404,  $_SERVER['REQUEST_URI'] . " not found");
           
            }
                
    }

    /**
 * @api {post} /counties/:id post county with given id
 * @apiParam {Number} id Users unique ID
 * @apiname post
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} counties      List of counties.
 * @apiSuccess {Number} counties.id     County id
 * @apiSuccess {String} counties.name   County name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *             {"id":},
 *             ],
 *       "message":"Created",
 *       "status":201
 *     }
 *
 * @apiError CountyNotFound The id of county was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "data": [],
 *       "message": "County Not Found",
 *       "status": "404"
 *     }
 */

    private static function postRequest()
    {
        $newId = 0;
        $resource = self::getResourceName();
        switch ($resource) {
            case 'counties':
                $data = self::getRequestData();
                if (isset($data['name'])) {
                    $db = new CountyRepository();
                    $newId = $db->create($data);
                    $code = 201;
                    if (!$newId) {
                        $code = 400; // Bad request
                    }
                }
                Response::response(['id' => $newId], $code);
                break;
            case 'cities':
                $data = self::getRequestData();
                if (isset($data['city'])) {
                    $db = new CityRepository();
                    $newId = $db->create($data);
                    $code = 201;
                    if (!$newId) {
                        $code = 400; // Bad request
                    }
                }
                Response::response(['id' => $newId], $code);
                break;

            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
        }
    }

    /**
 * @api {post} /counties/:id post county with given id
 * @apiParam {Number} id Users unique ID
 * @apiname put
 * @apiGroup Counties
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} counties      List of counties.
 * @apiSuccess {Number} counties.id     County id
 * @apiSuccess {String} counties.name   County name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *             {"id":},
 *             ],
 *       "message":"Created",
 *       "status":201
 *     }
 *
 * @apiError CountyNotFound The id of county was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "data": [],
 *       "message": "County Not Found",
 *       "status": "404"
 *     }
 */

    private static function putRequest()
{
    $id = self::getResourceId();
    if (!$id) {
        Response::response([], 400, Response::STATUES[400]);
        return;
    }
    $resourceName = self::getResourceName();
    switch ($resourceName) {
        case 'counties':
            $code = 404;
            $db = new CountyRepository();
            $data = self::getRequestData();
            $entity = $db->find($id);
            
            if($entity) {
            $result = $db->update($id, ['name' => $data['name']]);
            }
            if ($result) {
                $code = 201;
            }
            Response::response([], $code);
            break;
        case 'cities':
            $code = 404;
            $db = new CityRepository();
            $data = self::getRequestData();
            $entity = $db->find($id);
            
            if($entity) {
            $result = $db->update($id, ['city' => $data['name']]);
            }
            if ($result) {
                $code = 201;
            }
            Response::response([], $code);
            break;
        default:
            Response::response([], 404, $_SERVER['REQUEST_URI'] . " not found");
    }
}
 
 
    private static function getRequestData(): ?array {
        return json_decode(file_get_contents("php://input"), true);
    }

    private static function getArrUri(string $requestUri): ?array
        {
            return explode("/", $requestUri) ?? null;
        }
        
        private static function getResourceName(): string
        {
            $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
            $result = $arrUri[count($arrUri) - 1];
            if(is_numeric($result))
            {
                $result = $arrUri[count($arrUri) - 2];
            }

            return $result;
        }

        private static function getCityName(): string
        {
            $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
            $result = $arrUri[count($arrUri) - 2];
            if(is_numeric($result))
            {
                $result = $arrUri[count($arrUri) - 2];
            }

            return $result;
        }

        private static function getResourceId(): int
        {
            $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
            $result = 0;
            if(is_numeric($arrUri[count($arrUri) - 1]))
            {
                $result = $arrUri[count($arrUri) - 1];
            }
            return $result;
        }

        private static function getCityId(): int
        {
            $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
            $result = 0;
            if(is_numeric($arrUri[count($arrUri) - 1]))
            {
                $result = $arrUri[count($arrUri) - 1];
            }
            return $result;
        }
}
 
 
?>
