<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootPower! | <?php echo $page_title ?> </title>
    <link rel="stylesheet" href="../css/styles.css">
    <!--    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous">

    </script>

    <script>
        $(document).ready(function () {

            // get the current filename and run code for that file
            var currentURL = window.location.pathname;
            var currentFile = currentURL.substring(currentURL.lastIndexOf('/') + 1);

            switch (currentFile) {

                // display the signed-in user's Activity Log
                case 'index.php':
                    readActivities();
                    break;

                // set up the create data form
                case 'create_data.php':
                    initializeCreateDataForm();
                    break;

                // Set up the Edit Data form
                case 'update_data.php':
                    initializeUpdateDataForm();
                    break;


            }

        });
    </script>

</head>
<body>
<header role="banner"></header>
<main role="main">
    <article role="contentinfo">
        <header class="article-header" role="banner">
            <div class="header-title">
                <h1><?php echo $page_title ?></h1>
            </div>
        </header>


