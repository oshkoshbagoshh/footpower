<?php

    // initialize the app
    include_once  '../../private/common/initialization.php';

    // Include the data class
    include_once  '../../private/classes/data_class.php';

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
        $data = new Data($mysqli);

        // Pass the data verb to the appropriate method
        switch ($_POST['data-verb']) {

            // create a new data item
            case 'create':
                $server_results = json_decode($data->createData());
                break;

            // read all data items
            case 'read-all-data':
                $server_results = json_decode($data->readAllData());
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
    $JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
    echo $JSON_data;



