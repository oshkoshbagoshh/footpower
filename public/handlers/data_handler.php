<?php


// initialize the app
include_once '../../private/common/initialization.php';


// include the data class 
include_once '../../private/classes/data_class.php';


// initialize results 
$server_results['status'] = 'success';
$server_results['message'] = '';


// make sure a log ID was passed
if (!isset($_POST['log-id'])) {

    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: No log ID was passed';
}
// make sure a data verb was passed
elseif (!isset($_POST['data-verb'])) {

    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: No data verb was passed';
}
// make sure a token value was passed
elseif (!isset($_POST['token-value'])) {

    $server_results['status'] = 'error';
    $server_results['message'] = 'Error: No token value was passed';
}
//  make sure the token is legit
elseif ($_POST['token-value'] !== $_SESSION['token-value']) {

    $server_results['status'] = 'error';
    $server_results['message'] = '!Timeout Error: Invalid token value<p>Please refresh the page and try again...</p>';
} // if you made it this far, go for it 
else {
    // create a new Data object
    $data = new Data($mysqli);

    // pass data verb to the appropriate method
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


            // delete a new data item
        case 'delete':
            $server_results = json_decode($data->deleteData());
            break;

        default:
            $server_results['status'] = 'error';
            $server_results['message'] = 'Error: Unknown data verb';
    }
}

// create and output the JSON data
$JSON_data  = json_encode(
    $server_results,
    JSON_HEX_APOS | JSON_HEX_QUOT
);
echo $JSON_data;
