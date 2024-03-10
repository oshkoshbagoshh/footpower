<?php 

class Data {
    // holds the apps current mysql object
    private $_mysqli;

    // use the constructor to create a new mysqli object
    public function __construct($mysqli=NULL) {
        $this->_mysqli = $mysqli; 
        }

        // here comes the crud 
        public function createData() {
            // create data  
        }
        public function readData() {
            // read data
        }
        public function updateData() {
            // update data
        }
        public function deleteData() {
            // delete data
        }

}


// create an instance of the class
$log = new Data($mysqli);


