<?php

// initialize the app
include_once '../../private/common/initialization.php';

// include data class
include_once '../../private/classes/data_class.php';

// initialize the results
$server_results = [
    'status' => 'success',
    'message' => ''
];

// make sure a log ID was passed
if (!isset($_POST['log-id'])) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: No log ID specified!';
}
// make sure a data verb was passed
elseif (!isset($_POST['data-verb'])) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: No data verb specified!';
}
// make sure a token value was passed
elseif (!isset($_POST['token'])) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: Invalid action!';
}

// make sure the token is legit
elseif (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
    $server_results['status'] = 'error';
    $server_results['message'] = 'Timeout Error! <p> Please refresh the browser and try again.</p>';
}

// if we get this far, go for it
else {
    // create new data object
    $data = new Data($mysqli);

    // pass the data verb to the appropriate method
    switch ($_POST['data-verb']) {

            // create a new data item
        case 'create':
            $server_results = $data->createData();
            break;

            // read all the data items
        case 'read-all-data':
            $server_results = $data->readAllData();
            break;

            // read one data item
        case 'read-data-item':
            $server_results = $data->readDataItem();
            break;

            // Update a data item
        case 'update':
            // Assuming updateData() is the correct method for updating data
            $server_results = $data->updateData();
            break;

        default:
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Unknown data verb!';
    }
}

// create and then output the JSON data 
$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
echo $JSON_data;

?>

