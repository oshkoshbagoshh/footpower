<?php

/******
 *
 * Create a class for the one to many ("one" side of the relationship), often called the master table
 * needs to do the following:
 * 1. accept a parameter that references the current MySQLi object
 * 2. define a method for each of the four CRUD verbs
 * 3. define any helper functions required by the CRUD verbs
 */

class Data
{

    // holds the app's current MySQLi object
    /**
     * @var mixed|null
     */
    private mixed $_mysqli;

    // use the class constructor to store the passed MySQLi object

    /**
     * @param $mysqli
     */
    public function __construct($mysqli = null)
    {
        $this->_mysqli = $mysqli;
    }

    // CRUD

    /**
     * @return false|string|void
     */
    public function createData()
    {

        // create Data

        // store the default status
        {
            $server_results['status'] = 'success';
            $server_results['control'] = 'form';

            // check the log-id field
            $log_id = $_POST['log-id'];

            if (empty($log_id)) {
                $server_results['status'] = 'error';
                $server_results['message'] = 'Error: Missing log ID';
            } else {
                // sanitize it to an integer
                $log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);
                if (!$log_id) {
                    $server_results['status'] = 'error';
                    $server_results['message'] = 'Error: Invalid log ID';
                } else {
                    // Check the activity-type field (required)
                    if (isset($_POST['activity-type'])) {
                        $activity_type = $_POST['activity-type'];
                        if (empty($activity_type)) {
                            $server_results['status'] = 'error';
                            $server_results['message'] = 'Error: Missing Activity type';
                        } else {

                            // Sanitize it by accepting only one of the three values: 'Walk', 'Run', or 'Cycle'

                            if ($activity_type !== 'Walk' and $activity_type !== 'Run' and $activity_type !== 'Cycle') {
                                $server_results['status'] = 'error';
                                $server_results['message'] = 'Error: Invalid activity type';
                            } else {
                                // Check the activity-date field (required)
                                if (isset($_POST['activity-date'])) {
                                    $activity_date = $_POST['activity-date'];

                                    if (empty($activity_date)) {
                                        $server_results['status'] = 'error';
                                        $server_results['message'] = 'Error: Missing activity date';
                                    } else {
                                        // Check for a valid date (pattern matching of YYYY-MM-DD
                                        if (!preg_match('/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/',
                                            $activity_date)) {
                                            $server_results['status'] = 'error';
                                            $server_results['message'] = 'Error: Invalid activity date';
                                        }

                                    }

                                }


                            }
                        }
                    }
                }
            }
        }


        // check the activity-distance field
        $activity_distance = 0;
        if (isset($_POST['activity-distance'])) {
            $activity_distance = $_POST['activity-distance'];

            // Sanitize it to a floating-point value
            $activity_distance = filter_var($activity_distance, FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION);

            // Check the activity-duration-hours field
            $activity_hours = 0;
            if (isset($_POST['activity-duration-hours'])) {
                $activity_hours = $_POST['activity-duration-hours'];
                $activity_hours = filter_var($activity_hours, FILTER_SANITIZE_NUMBER_FLOAT);
            }
            // check the minutes
            if (isset($_POST['activity-duration-minutes'])) {
                $activity_minutes = $_POST['activity-duration-minutes'];
                $activity_minutes = filter_var($activity_minutes, FILTER_SANITIZE_NUMBER_FLOAT);
            }
            // check the seconds
            if (isset($_POST['activity-duration-seconds'])) {
                $activity_seconds = $_POST['activity-duration-seconds'];
                $activity_seconds = filter_var($activity_seconds, FILTER_SANITIZE_NUMBER_FLOAT);
            }
            // you made it :D

            $activity_duration = $activity_hours . ':' . $activity_minutes . ':' . $activity_seconds;


            if ($server_results['status'] === 'success') {

                // Create the SQL template
                $sql = "INSERT INTO activities (log_id, type, date,distance, duration)
                        VALUES (?,?,?,?,?)";

//                // Prepare the statement template
                $stmt = $this->_mysqli->prepare($sql);
//
//                // Bind the parameters
                $stmt->bind_param("issds", $log_id, $activity_type, $activity_date, $activity_distance,
                    $activity_duration);
//
//                // Execute the prepared statement
                $stmt->execute();
//
//                // Get the results
                $result = $stmt->get_results();
//
                if ($this->_mysqli->errno === 0) {
                    $server_results['message'] = 'Activity saved successfully! Sending you back to the activity log...';
                } else {
                    $server_results['status'] = 'error';
                    $server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errorno . ': ' . $this->_mysqli->error;
                }

//                //            create and then output the JSON data
                $JSON_data = json_encode($server_results, JSON_THROW_ON_ERROR | JSON_HEX_APOS | JSON_HEX_QUOT);
//
                return $JSON_data;
            }
        }
    }


    /**
     * @return string|false
     */
    public
    function readAllData(): string|false
    {

        // read ALl Data
        //Store the default status
        $server_results['status'] = 'success';

        // Check the log-id field
        if (empty($log_id)) {
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Missing log ID';
        } else {
            // Sanitize it to an integer
            $log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);
            if (!$log_id) {
                $server_results['status'] = 'error';
                $server_results['message'] = 'Error: Invalid log ID';

            }

        }

        if ($server_results['status'] === 'success') {

            // create the SQL template
            $sql = "SELECT * FROM  activities       
                                WHERE log_id=?
                                ORDER BY date DESC";

            // Prepare the statement template
            $stmt = $this->_mysqli->prepare($sql);

            // Bind the parameter
            $stmt->bind_param("i", $log_id);

            // Execute the prepared statement
            $stmt->execute();

            // Get the results
            $result = $stmt->get_result();

            if($this->_mysqli->errno === 0) {
                // Get the query rows as an associative array
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                // Convert the array to JSON, then output it
                $JSON_data = json_encode($rows, JSON_HEX_APOS | JSON_HEX_QUOT);
                return $JSON_data;
            }
        }

    }

    /**
     * @return void
     */
    public function readDataItem()
    {

        // read one data Item

    }

    /**
     * @return void
     */
    public
    function updateData()
    {

        // update data
    }

    /**
     * @return void
     */
    public
    function deleteData()
    {

        // delete data
    }


}


