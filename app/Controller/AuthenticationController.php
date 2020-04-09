<?php

/**
 * 
 * Controller class for authentiction pages
 * 
 */
class AuthenticationController extends Controller {

    /**
     * LoginGET
     *
     * Shows up login form
     * 
     * @return void
     */
    function LoginGET() {
        $this->Render('Login', [
            "Title" => "ورود"
        ]);
    }


    /**
     * LoginPOST
     *
     * Does login action
     * 
     * @return void
     */
    function LoginPOST() {

        $Values = [
            'Email' => $_POST['EmailInput'],
            'Password' => md5($_POST['PasswordInput'])
        ];

        $Model = $this->CallModel("Auth");
        $Rows = $Model->CheckLogin($Values);

        if (count($Rows) == 1) {

            // Set cookies
            setcookie("UserId", $Rows[0]['Id'], time() + (2 * 86400 * 15), "/"); // Keep cookies for next two days
            setcookie("Email", $_POST['EmailInput'], time() + (2 * 86400 * 15), "/");

            // Response redirect
            $this->RedirectResponse(_Root . "Admin/Index");
        } else {
            $this->Render('Login', [
                "Title" => "ورود",
                "Message" => "نام کاربری یا کلمه‌ی عبور معتبر نیست"
            ]);
        }
        
    }

    /**
     * LogoutGET
     *
     * Does logout action
     * 
     * @return void
     */
    function LogoutGET() {
        $this->CheckAuth($_COOKIE); // Check login

        // Unset the cookies
        unset($_COOKIE['UserId']);
        unset($_COOKIE['Email']);
        setcookie('UserId', null, -1, '/');
        setcookie('Email', null, -1, '/');

        // Render logout form
        $this->Render('Login', [
            "Title" => "خروج",
            "Message" => "شما از سیستم خارج شدید"
        ]);
        
    }

}