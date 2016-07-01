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

class database {
    /* Internal vars */
    private $link; //Holds current link
    private $db_ip; //DB-Connect userdata
    private $db_port;
    private $db_user;
    private $db_pass;
    private $db_dbase;
    private $error; //Holds last error

    /* __construct() function for class-init */
    function __construct($db_ip, $db_port, $db_user, $db_pass, $db_dbase) {
        //Set specific settings for connection
        $this->link = false;
        $this->error = false;
        $this->db_ip = $db_ip;
        $this->db_port = $db_port;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_dbase = $db_dbase;

        //Connect to database server
        self::connect();
    }

    /* connect()
     *
     * This function establishes a connection to a give mysql_db
     */
    private function connect() {
        //Disconnect old connection if open
        self::close();

        //Connect to server and selects DB
        $this->link = mysqli_connect($this->db_ip, $this->db_user, $this->db_pass, $this->db_dbase, $this->db_port);
        if (!$this->link) {
            $this->error =  "Can't connect to Database: " . mysqli_connect_error($this->link);
            return false;
        }

        //Select desired database
        if(!self::selectDatabase($this->db_dbase))
            return false;

        //Force UTF8
        mysqli_query($this->link, "SET NAMES 'utf8'");

        return true;
    }

    /* isError()
     *
     * This function returns an error if necessary
     *
     * Return:
     *   FALSE -> Good, no error
     *   STRING -> 2bad4u, error string returned
     */
    public function isError() {
        if($this->error)
            return $this->error;

        return false;
    }

    /* clearError()
     *
     * Clears error variable
     */
    public function clearError() {
        $this->error = false;
    }

    /* close()
     *
     * This function terminates a mysql connection
     */
    public function close() {
        //Close link if open
        if ($this->link) {
            mysqli_close($this->link);
        }

        $this->link = false;
        return true;
    }

    /* selectDatabase()
     *
     * This function selects a given database for the current link
     *
     * Input:
     *   $dbname -> The database name to select
     *
     * Return:
     *   TRUE -> Success
     *   FALSE -> Failed
     */
    public function selectDatabase($dbname) {
        //Checks for existing link
        if(!$this->link) {
            $this->error = "Can't select database. No Database connection!";
            return false;
        }

        //Try to select database
        if(!mysqli_select_db($this->link, $dbname)) {
            $this->error = "Can't select database: " . mysqli_error($this->link);
            return false;
        } else {
            return true;
        }
    }

    /* query()
     *
     * This function executes a query to the database
     *
     * Input:
     *   $query -> The Query (e.g. 'SELECT * FROM `table`')
     *
     * Return:
     *   $result -> Success, result returned
     *   FALSE -> Failed (See $this->error)
     */
    public function query($query) {
        //Check for existing link
        if(!$this->link) {
            $this->error = "Can't query database. No Database connection!";
            return false;
        }

        //Execute query
        $result = mysqli_query($this->link, $query);
        if(!$result) {
            $this->error = "Query failed: " . mysqli_error($this->link);
            return false;
        } else {
            return $result;
        }
    }

    /* escape()
     *
     * This function escapes a String for further SQL use
     *
     * Input:
     *   $input -> The given string
     *
     * Return:
     *   STRING -> The processed string
     */
    public function escape($input) {
        return mysqli_real_escape_string($this->link, $input);
    }

    /* getLastInsertId()
     *
     * This function returns the last "insert id"
     */
    public function getLastInsertId() {
        return mysqli_insert_id($this->link);
    }
}

?>