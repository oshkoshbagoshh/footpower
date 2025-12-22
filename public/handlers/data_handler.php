<?php

    // initialize the app
    include_once '../../src/common/initialization.php';

    // Include the data class
    include_once '../../src/classes/Data.php';

    // Initialize the results

    $server_results['status'] = 'success';
    $server_results['message'] = '';

    // Make sure  a log ID was passed

    if (!isset($_POST['log-id'])) {
        $server_results['status'] = 'error';
        $server_results['message'] = 'Error: No log ID specified!';
    }

    // make sure the data verb was passed
    elseif (!isset($_POST['data-verb'])) {
        $server_results['status'] = 'error';
        $server_results['message'] = 'Error: No data verb specified!';
    }

    // Make sure a token value was passed
    elseif (!isset($_POST['token'])) {
        $server_results['status'] = 'error';
        $server_results['message'] = 'Error: Invalid action!';
    }


    // Make sure the token is legit (session token needs to match POST token
    elseif ($_SESSION['token'] !== $_POST['token']) {
        $server_results['status'] = 'error';
        $server_results['message'] = 'Timeout Error! <p>Please refresh the page and try again.</p>';
    }


    // If we get this far, all is well, so go for it
    else {

        // Create a new Data object
        /** @var Data $data data $mysqli */
        $data = new mysqli();


        // Pass the data verb to the appropriate method
        switch ($_POST['data-verb']) {

            // create a new data item
            case 'create':
                try {
                    $server_results = json_decode($data->createData(), false, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $e) {

                }
                break;

            // read all data items
            case 'read-all-data':
                $server_results = json_decode($data->readAllData(), false, 512, JSON_THROW_ON_ERROR);
                break;

            // read one data item
            case 'read-data-item':
                $server_results = json_decode($data->readDataItem());
                break;

            // update a data item
            case 'update':
                $server_results = json_decode($data->updateData());
                break;

            // delete a data item
            case 'delete':
                $server_results = json_decode($data->deleteData());
                break;

            default:
                $server_results['status'] = 'error';
                $server_results['message'] = 'Error: Unknown data verb!';



        }
    }

    // create and then output the JSON data
    $JSON_data = json_encode($server_results, JSON_THROW_ON_ERROR | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo $JSON_data;


//print_r($data);
//var_dump($JSON_data);

