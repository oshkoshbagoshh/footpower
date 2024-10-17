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


    // Make sure the token is legit


    // If we get this far, all is well, so go for it





