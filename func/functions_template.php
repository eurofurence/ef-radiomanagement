<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2016 Niels Gandraß <ngandrass@squacu.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class template {
    /* Internal Variables */
    private $curTemplate;
    private $curRequest;

    /* __construct sets curPage and includes templates
     *
     * Input:
     *   $reqPage -> GET parameter for page from siterequest
     */
    function __construct($reqPage) {
        //Check if requested template is available
        $this->curTemplate = self::selectTemplate($reqPage);
        if(!$this->curTemplate) {
            self::throw404();
        }

        //Check userLevel
        if(!self::checkAccessLevel()) {
            self::throw403();
        }

        //Build complete page
        self::buildPage();
        return true;
    }

    /* selectTemplate()
     *
     * This function tries to select a template for the request
     *
     * Input:
     *   $request -> The requested Template
     *
     * Return:
     *   STRING -> Success, filepath returned
     *   FALSE -> Error, template not found
     */
    private function selectTemplate($request) {
        global $_TEMPLATES;

        //Request Index if no template is requested
        if(!$request)
            $request = "index";

        //Check for requested template
        foreach($_TEMPLATES as $template) {
            if($template["alias"] == $request) {
                $this->curRequest = $request;
                return $template;
            }
        }

        return false;
    }

    /* buildPage()
     *
     * This function constructs the page by including
     * the desired templates
     */
    private function buildPage() {
        global $core; //Set $core global for usage in templates
        global $sessions; //Set $sessions global for usage in templates
        global $_TEMPLATES_SPECIAL; //To check if template requires footer/header/navi

        //Init Buffer
        ob_start();

        //Include desired template (Header and footer are included in template itself)
        require($this->curTemplate["file"]);

        //Output & Destroy Buffer
        while (@ob_end_flush());
        ob_end_clean();

        return true;
    }



    /* checkAccessLevel()
     *
     * This function checks if the user is allowed to request
     * the requested page
     *
     * Return:
     *   TRUE -> User is allowed
     *   FALSE -> User it NOT allowed
     */
    private function checkAccessLevel() {
        global $sessions;

        if($sessions->getUserLevel()>=$this->curTemplate["accessLevel"]) {
            return true;
        }

        return false;
    }

    /* throw403()
     *
     * This function produces an 403 error and stopps exexution
     */
    private function throw403() {
        header("HTTP/1.0 403 Forbidden");
        echo "<h1>403 Forbidden</h1>";
        echo "You don't have permission to access the requested site on the server.";
        echo "<br/><br/><a href=\"".domain."index.php?p=login\">Login</a>";
        die();
    }

    /* throw404()
     *
     * This function produces an 404 error and stopps execution
     */
    private function throw404() {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        die();
    }

}

?>