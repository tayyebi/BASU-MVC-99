<?php
/**
 * Master class of controllers
 */
class Controller {

    protected $ViewDirectory;


    /**
     * SetViewDirectory
     *
     * A simple setter for $ViewDirectory
     * which we call it from App.php
     * 
     * @param  mixed $Value
     *
     * @return void
     */
    function SetViewDirectory(string $Value){
        $this->ViewDirectory = $Value;
    }

    /**
     * CallModel
     *
     * Sets, calls, and loads the model
     * 
     * @param string $Entity
     *
     * @return void
     */
    function CallModel(string $Entity){
        include('Model/' . $Entity . '.php');
        return new $Entity;
    }

    /**
     * GetPayload
     *
     * Returns the payload/tail of view file
     * 
     * @param  mixed $ViewFile
     * @param  mixed $ExcludePayload
     *
     * @return void
     */
    private function GetPayload($ViewFile, $ExcludePayload = false){
        $Separator = '<!--PAYLOAD_CONTENT_END-->';
        $TextInsideFile = file_get_contents($ViewFile);
        // If text does not contains the payload pointer
        if (strpos($TextInsideFile, $Separator) == false)
            if ($ExcludePayload)
            return $TextInsideFile;
            else return "";
        // If head
        if (!$ExcludePayload and (strpos($TextInsideFile, $Separator) !== false))
        $TextInsideFile = substr($TextInsideFile, 0, strpos($TextInsideFile, $Separator));
        // If separator does not exist
        else if (!$ExcludePayload and !(strpos($TextInsideFile, $Separator) !== false))
        $TextInsideFile = '';
        // If tail
        else
        $TextInsideFile = substr($TextInsideFile,
        strpos($TextInsideFile, $Separator) + strlen($Separator));
        return $TextInsideFile;
    }


    /**
     * RenderBody
     *
     * Returns header and footer of the layout file
     * 
     * @param  mixed $LayoutFile
     * @param  mixed $Head
     *
     * @return void
     */
    private function RenderBody($LayoutFile, $Head){
        $Separator = '<!--VIEW_CONTENT-->';
        $TextInsideFile = file_get_contents($LayoutFile);
        // If head
        if ($Head and (strpos($TextInsideFile, $Separator) !== false) )
        $TextInsideFile = substr($TextInsideFile, 0, strpos($TextInsideFile, $Separator));
        // If separator does not exist
        else if ($Head and !(strpos($TextInsideFile, $Separator) !== false))
        $TextInsideFile = '';
        // If tail
        else
        $TextInsideFile = substr($TextInsideFile,
        strpos($TextInsideFile, $Separator) + strlen($Separator));
        return $TextInsideFile;
    }

    /**
     * Evaluate
     *
     * An `include`-based equivalent to php eval code
     * 
     * @param  mixed $Code
     *
     * @return void
     */
    private function Evaluate($Code, $Data = []) {

        // Algorithm
        // 1. Create a temp file from payload
        // 2. Include the payload
        $TempPointer = tmpfile();
        fwrite($TempPointer, $Code);
        $MetaData = stream_get_meta_data($TempPointer);
        $TempFileName = $MetaData['uri'];
        chmod($TempFileName, 775);
        include($TempFileName);
        fclose($TempPointer);
    }

    /**
     * Render
     *
     * Renders the view
     * 
     * @param  mixed $View
     * @param  mixed $Data
     *
     * @return void
     */
    function Render($View, $Data = [], $IsPartial = false)
    {
        // The current view file
        $CurrentViewFile = 'View/' . $this->ViewDirectory . '/' . $View . '.php';
        // Run the payload for current file
        $this->Evaluate($this->GetPayload($CurrentViewFile, false), $Data);
        // Get master layout head
        if (!$IsPartial)
            $this->Evaluate($this->RenderBody('View/_Layout.php', true), $Data);
        // Get slave layout head
        if (!$IsPartial)
            if (file_exists('View/' . $this->ViewDirectory . '/_Layout.php'))
                $this->Evaluate($this->RenderBody('View/' . $this->ViewDirectory . '/_Layout.php', true),
                $Data);
        // Render the view body
        $this->Evaluate($this->GetPayload($CurrentViewFile, true), $Data);
        // Get slave layout tail
        if (!$IsPartial)
            if (file_exists('View/' . $this->ViewDirectory . '/_Layout.php'))
                $this->Evaluate($this->RenderBody('View/' . $this->ViewDirectory . '/_Layout.php', false),
                $Data);
        // Get master layout tail
        if (!$IsPartial)
            $this->Evaluate($this->RenderBody('View/_Layout.php', false), $Data);

    }


    /**
     * RedirectResponse
     *
     * Sets the header to redirect
     * 
     * @param  mixed $Route
     *
     * @return void
     */
    function RedirectResponse($Route)
    {
        header("Location: " . $Route);
    }


    /**
     * CheckAuth
     *
     * Renders the view
     * 
     * @param  mixed $Cookies
     * @param  mixed $Data
     *
     * @return void
     */
    function CheckAuth($Cookies, $LoginRequired = true)
    {
        if ($LoginRequired)
        {
            // Check cookies
            if (!isset($Cookies['Email'], $Cookies['UserId']))
            {
                throw new AuthException("شناسه کاربری یافت نشد");
                exit;
            }
            
            // Connect to the model
            $Model = $this->CallModel("Auth");

            // Check if user is still login
            $CheckSessions = $Model->CheckSessions([
                'Email' => $Cookies['Email']
            ]);

            // If user was not found in database
            // (regarding to search based on `Id`)
            if (count($CheckSessions) == 0)
            {
                throw new AuthException("شناسه کاربری در پایگاه داده یافت نشد");
                exit;
            }

            // If session not valid
            if ($CheckSessions[0]['LoginStatus'] == 0)
            {
                throw new AuthException("نشست فعال نیست");
                exit;
            }

            return true;
            
        }
    }
}