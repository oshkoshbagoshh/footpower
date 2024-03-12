<?php 
class Data {
    // holds apps curreny mySQLi Object
    private $_mysqli;

    // use class constructor to store the passed mySQLi object
    public function __construct($mysqli=NULL) {
        $this->_mysqli = $mysqli;

    }

    // HERE COMES THE CRUD 


    public function createData () {
        
    }
    public function readAllData () {
        
    }

    public function readDataItem() {
        
    }
    public function updateData () {
        
    }
    public function deleteData () {
        
    }




}

// to create an instance of this class, 
// $log = new Data($mysqli);