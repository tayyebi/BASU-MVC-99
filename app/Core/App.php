<?php

class App {

    function __construct() {

        if (isset($_SERVER['PATH_INFO'])) 
        {
            $Path = $_SERVER['PATH_INFO'];
        }
        else if (isset($_SERVER['QUERY_STRING']))
        {
            $Path = $_SERVER['QUERY_STRING'];
        }

        // =========
        echo "Hello from /Core/App.php";

        echo "<br/>Requested Path is: <br/>";
        echo $Path;

    }

}