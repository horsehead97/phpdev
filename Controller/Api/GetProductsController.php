<?php
class GetProductsController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function cors() {
    
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: *");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
            exit(0);
        }
        
        ServerLogger::log("You have CORS!");
    }
    public function singleProd()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        ServerLogger::log("the request method is: $requestMethod");
        
 
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $gpModel = new GetProductModel();
                $upc='010939136336';
                $prod_data = $gpModel->getProduct($upc);
                $responseData = json_encode($prod_data);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }elseif (strtoupper($requestMethod) == 'POST') {
            try {
                $gpModel = new GetProductModel();
                $upc='';
                $arrParams = $this->getReqBody();
                if (isset($arrParams['upc']) && $arrParams['upc']) {
                    $upc= $arrParams['upc'];
                }
                ServerLogger::log("upc: $upc");
                ServerLogger::log(gettype($upc));
                if (!ctype_digit($upc) || strlen($upc)>15){
                    throw new Error('upc value is invalid, please refer to API documentation');
                } 
                $prod_data = $gpModel->getProduct($upc);
                //$prod_data=$prod_data[0];
                // ServerLogger::log("prod data: $prod_date  afterwards");
                // foreach($prod_data as $x => $x_value) {
                //     ServerLogger::log("Key=" . $x . ", Value=" . $x_value);
                // }
                //ServerLogger::log(var_dump($prod_data));
                //$responseData = $prod_data[0];
                //$responseData=implode('',$prod_data);
                //$type=gettype($responseData);
                //ServerLogger::log("responsedata: $responseData type: $type");
                $responseData = json_encode($prod_data);
                ServerLogger::log("response data: var_dump($responseData)");
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }else{
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
 
        // send output
        if (!$strErrorDesc) {
            ServerLogger::log("response data: $responseData");
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
