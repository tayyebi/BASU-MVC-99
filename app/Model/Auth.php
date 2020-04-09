<?php

/**
 * 
 * An alternative to model `users`
 * because of Fatal error: Cannot declare class User, because the name is already in use
 * 
 */

class Auth extends Model {
    
    function CheckSessions($Values) {
        $Query = "SELECT '1' AS 'LoginStatus' FROM `Users` WHERE Email LIKE :Email";
        $Result = $this->DoSelect($Query, $Values);
        return $Result;
    }

    function CheckLogin($Values) {
        $Query = "SELECT `Id` FROM `Users` WHERE `Email`= :Email and `Password`= :Password";
        $Result = $this->DoSelect($Query, $Values);
        return $Result;
    }

}