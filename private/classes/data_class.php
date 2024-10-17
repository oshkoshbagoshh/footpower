<?php

/******
 *
 * Create a class for the one to many ("one" side of the relationship), often called the master table
 * needs to do the following:
 * 1. accept a parameter that references the current MySQLi object
 * 2. define a method for each of the four CRUD verbs
 * 3. define any helper functions required by the CRUD verbs
 */

class Data {

    // holds the app's current MySQLi object
    private  $_mysqli;

    // use the class constructor to store the passed MySQLi object
    public function __construct($mysqli=NULL) {
        $this->_mysqli = $mysqli;
    }

    // CRUD
    public function createData() {

        // create Data

    }
    public function readAllData() {

        // read ALl Data

    }

    public function readDataItem() {

        // read one data Item

    }

    public function updateData() {

        // update data
    }

    public function deleteData() {

        // delete data
    }





}


