<?php

/**
 * App holder class
 */
class App {
    
    /**
     * 
     * Values are default values
     * 
     */
    private $Controller = 'HomeController';
    private $DefaultViewDirectoryOfController = 'Home';
    private $View = 'Index';
    private $Params = [];

    /**
     * GetPathInfo
     *
     * Returns URL requested by user
     * 
     * @return array
     */
    function  GetPathInfo(){
        
        // Use path info if exists
        // index.php/value1/value2
        if (isset($_SERVER['PATH_INFO']))
		$PathInfo = trim(
		    $_SERVER['PATH_INFO']
		    , '/');
        // Use query string if exists
        // index.php?/value1/value2
        else if (isset($_SERVER['QUERY_STRING']))
            $PathInfo = trim(
                $_SERVER['QUERY_STRING']
                , '/');
        // return null if no one exist
        else
            return [];
        
        // convert to array and return
        return explode('/', $PathInfo);
    }


    /**
     * ThowError
     *
     * Throw low level errors which are log-able from web server
     * 
     * @return array
     */
    public function ThowError($HttpStatusCode, $Message = '') {
        switch ($HttpStatusCode)
        {
            case 403:
                header('HTTP/1.0 403 Forbidden');
                include('static/errors/HTTP403.html');
                exit;
            case 404:
                header('HTTP/1.0 404 Not Found');
                include('static/errors/HTTP404.html');
                exit;
            default:
                throw new Exception($Message);
                die();
        }
    }

    /**
     * __construct
     *
     * Class Constructor which is called on any request
     * 
     * @return void
     */
    function __construct(){
        // Get URL
        $URL = $this->GetPathInfo();
        
        // Routing
        // TODO: Use a routing mechanism which allows configuration

        // Set Controller
        if (isset($URL[0]) and $URL[0] != '')
        {
            $this->DefaultViewDirectoryOfController = $URL[0];
            $this->Controller = $URL[0].'Controller';
            unset($URL[0]);
        }
        // Set View
        if (isset($URL[1]) and $URL[1] != '')
        {
            $this->View = $URL[1];
            unset($URL[1]);
        }
        
        // Get other parameters
        $this->Params = array_values($URL);
        // Call the method form class
        $ControllerFilePath = 'Controller/' . $this->Controller.'.php';
        // If controller file does not exist
        if (!file_exists($ControllerFilePath))
	        $this->ThowError(404);
        // Include the controller file
        include($ControllerFilePath);
        // Create an instance of controller class
        $ClassObject = new $this->Controller();
       // Set the view folder
        $ClassObject->SetViewDirectory($this->DefaultViewDirectoryOfController);
        // Get the method
        $HttpMethod = $_SERVER['REQUEST_METHOD'];
        // Find the function
        $ControllerMethod = $this->View . $HttpMethod;
        // Call the method if exists
        if (!method_exists($ClassObject, $ControllerMethod))
            $this->ThowError(404);
        try {
            // Call the view
            call_user_func_array([$ClassObject, $ControllerMethod], $this->Params);
	}
	catch (AuthException $exp ){ // On auth error
            $this->ThowError(403);
        } catch (NotFoundException $exp ){ // on not found error
            $this->ThowError(404);
        }
    }

}