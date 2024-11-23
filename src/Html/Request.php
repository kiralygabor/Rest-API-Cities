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
 * @api {get} /cities Get list of cities
 * @apiname index
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} cities List of cities.
 * @apiSuccess {Number} cities.id  City id
 * @apiSuccess {String} cities.name City name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 * "data": [
 *       {
 *           "id": "5",
 *          "zip_code": "3261",
 *           "city": "Abasár",
 *          "id_county": "10"
 *       }
 *  ],
 *   "message": "OK",
 *   "code": 200
 * 
 * "data": [
 *      {
 *           "id": "6",
 *          "zip_code": "3882",
 *           "city": "Abaújalpár",
 *          "id_county": "10"
 *       }
 *   ],
 *   "message": "OK",
 *   "code": 200
 *
 * @apiError CityNotFound The id of city was not found.
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
 * @api {get} /cities/:id Get city with given id
 * @apiParam {Number} id Users unique ID
 * @apiname index
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} cities      List of cities.
 * @apiSuccess {Number} cities.id     City id
 * @apiSuccess {String} cities.name   City name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     "data": [
 *       {
 *           "id": "6",
 *           "zip_code": "3882",
 *           "city": "Abaújalpár",
 *           "id_county": "5"
 *       }
 *   ],
 *   "message": "OK",
 *   "code": 200
 *
 * @apiError CityNotFound The id of city was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *   "data": [],
 *  "message": "CityNotFound",
 *   "code": 404
*      }
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
    * @api {delete} /cities/:id delete city with given id
    * @apiParam {Number} id Users unique ID
    * @apiname delete
    * @apiGroup Cities
    * @apiVersion 1.0.0
    *
    * @apiSuccess {Object[]} cities      List of cities.
    * @apiSuccess {Number} cities.id     City id
    * @apiSuccess {String} cities.name   City name
    *
    * @apiSuccessExample {json} Success-Response:
    *     HTTP/1.1 200 OK
    *     {
    *       "data": [],
    *       "message":"",
    *       "status":204
    *     }
    *
    * @apiError CityNotFound The id of city was not found.
    *
    * @apiErrorExample Error-Response:
    *     HTTP/1.1 404 Not Found
    *     
    * "data": [],
    * "message": "/counties/7/citis/2 not found",
    * "code": 404
    * }
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
 * @api {post} /cities/:id post city with given id
 * @apiParam {Number} id Users unique ID
 * @apiname post
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} cities      List of cities.
 * @apiSuccess {Number} cities.id     City id
 * @apiSuccess {String} cities.name   City name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *             {"id":5},
 *             ],
 *       "message":"Created",
 *       "status":201
 *     }
 *
 * @apiError CityNotFound The id of city was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "data": [],
 *       "message": "City Not Found",
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
 * @api {put} /cities/:id post city with given id
 * @apiParam {Number} id Users unique ID
 * @apiname put
 * @apiGroup Cities
 * @apiVersion 1.0.0
 *
 * @apiSuccess {Object[]} cities      List of cities.
 * @apiSuccess {Number} cities.id     City id
 * @apiSuccess {String} cities.name   City name
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "data": [
 *             {"id":4},
 *             ],
 *       "message":"Updated",
 *       "status":201
 *     }
 *
 * @apiError CityNotFound The id of city was not found.
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Not Found
 *     {
 *       "data": [],
 *       "message": "Not Found",
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
