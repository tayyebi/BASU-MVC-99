<?php

/**
 * 
 * Controller class for admin panel
 * 
 */
class AdminController extends Controller {

    /**
     * IndexGET
     *
     * Index view of admin dashboard
     * 
     * @return void
     */
    function IndexGET() {

        $this->CheckAuth($_COOKIE); // Check login

        $Data = [
            'Title' => 'پنل مدیر',
        ];

        $this->Render('Index', $Data);

    }


    /**
     * UsersGET
     *
     * List the posts
     * 
     * @return void
     */
    function UsersGET() {

        $this->CheckAuth($_COOKIE); // Check login

        // Ask database for data
        $Model = $this->CallModel("User");
        $Rows = $Model->GetAllUsers();

        // Package the response
        $Data = [
            'Title' => 'مدیریت کاربر‌ها',
            'Model' => $Rows
        ];
        
        // Render the view
        $this->Render('Users', $Data);

        
    }


    /**
     * UserGET
     *
     * Get a user details and managment stuff
     * 
     * @return void
     */
    function UserGET($Id = 0) {

        $this->CheckAuth($_COOKIE); // Check login


        // Check if Id is passed to function
        if ($Id != 0)
        {
            $Model = $this->CallModel("User");
            $Rows = $Model->GetUserById([
                'Id' => $Id,
            ]);

            if (count($Rows) == 0)
                goto the_notfound;

            $Row = $Rows[0];

            $Data = [
                'Title' => 'مدیریت کاربر',
                'Model' => $Row
            ];
            
            $this->Render('User', $Data);

        }
        // If it was insert
        else
        {
            the_notfound: // A goto (evil) stuff // Just a programming fun.
            throw new NotFoundException("شناسه‌ی کاربری مورد نظر پیدا نشد");
        }
    }


    /**
     * UserPOST
     *
     * Update user details
     * 
     * @return void
     */
    function UserPOST($Id = 0) {
        
        $this->CheckAuth($_COOKIE); // Check login

        // Check if Id is passed to the function
        if ($Id != 0)
        {
            // Call the model
            $Model = $this->CallModel("User");

            // Initialy set message to String.Empty
            $Message = '';

            // Check if user exist from parameters
            $Rows = $Model->GetUserById([
                'Id' => $Id
            ]);
            if (count($Rows) > 0)
            {

                // Check if new password and it's confirm match
                if ($_POST['NewPassInput'] == $_POST['ConfirmPassInput'])
                {

                    // Check former password
                    $CheckLogin = $Model->CheckLogin([
                        'Email' => $Rows[0]['Email'],
                        'Password' => (new Cryptography())->Encrypt($_POST['FormerPassInput'])
                    ]);

                    // If former password was correct
                    if (count($CheckLogin) == 1)
                    {
                        
                        // Update the password
                        $Response = $Model->UpdatePassword([
                            'Id' => $Id,
                            'Password' => (new Cryptography())->Encrypt($_POST['NewPassInput'])
                        ]);

                        // Check for database errors
                        $Message = $Response ? 'کلمه‌ی عبور کاربر به روز شد' : 'خطای پایگاه داده';
                    }
                    else
                    {
                        $Message = 'کلمه‌ی عبور پیشین معتبر نیست';
                    }
                }
                else
                {
                    $Message = 'کلمه‌ی عبور جدید با تکرار آن هم‌خوانی ندارد';
                }

                // Get the current record
                $Row = $Rows[0];

                // Return the view
                $Data = [
                    'Title' => 'مدیریت کاربر',
                    'Message' => $Message,
                    'Model' => $Row
                ];
                $this->Render('User', $Data);

                // Don't allow program to go to the next lines
                return;
            }
                
        }

        // If user id did not exist in database or sent as paramter to this method
        throw new NotFoundException("شناسه‌ی کاربری مورد نظر پیدا نشد");
    }



}