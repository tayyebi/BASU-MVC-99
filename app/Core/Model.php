<?php

/**
 * 
 * Master class for models
 * 
 */
class Model{

    public static $Connection = '';

    /**
     * __toString
     *
     * Returns the connection stirng
     * 
     * @return string ConnectionString
     */
    public function __toString()
    {
        return 'mysql:host=' . _DatabaseServer . ';dbname=' . _DatabaseName;
    }

    
    /**
     * __construct
     *
     * Create a connection to database
     * 
     * @return void
     */
    function __construct()
    {
        $ConnectionParameters = []; // array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
        self::$Connection = new PDO((string)$this,  _DatabaseUsername, _DatabasePassword, $ConnectionParameters);
        if (_Debug)
            self::$Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    /**
     * DoSelect
     *
     * Runs a select query
     * 
     * @param  mixed $Query
     * @param  mixed $Values
     * @param  mixed $FetchStyle
     *
     * @return array Query outputs
     */
    function DoSelect($Query, $Values = [], $FetchStyle = PDO::FETCH_ASSOC)
    {
        $LiveConnection = self::$Connection->prepare($Query);
        foreach ($Values as $Key => $Value) {
            $LiveConnection->bindValue($Key, $Value);
        }
        $LiveConnection->execute();
        $Result = $LiveConnection->fetchAll($FetchStyle);
        return $Result;
    }


    /**
     * DoQuery
     *
     * Runs a executing query
     * 
     * @param  mixed $Query
     * @param  mixed $Values
     *
     * @return void
     */
    function DoQuery($Query, $Values = [])
    {
        $LiveConnection = self::$Connection->prepare($Query);
        foreach ($Values as $Key => $Value) {
            if (gettype($Value) == "integer" || gettype($Value) == "boolean") // Recommended for bit(1) values
                $LiveConnection->bindValue($Key, $Value, PDO::PARAM_INT);
            else
                $LiveConnection->bindValue($Key, $Value);
        }
        return $LiveConnection->execute();
    }
}