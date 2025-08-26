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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
<header role="banner">
<main role="main">
    <article role="contentinfo">
        <header class="article-header" role="banner">
            <div class="header-title">
                <h1><?php echo $page_title ?></h1>
            </div>
        </header>
    </article>
    <div class="logo" aria-label="FootPower logo">
        <!-- Inline SVG logo here -->
        <svg width="120" height="60" viewBox="0 0 120 60" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="FootPower logo">
            <text x="10" y="40" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="#333">F</text>
            <g transform="translate(30,10)">
                <circle cx="10" cy="10" r="6" fill="#555"/>
                <ellipse cx="10" cy="25" rx="14" ry="10" fill="#777"/>
                <circle cx="5" cy="5" r="2" fill="#555"/>
                <circle cx="15" cy="5" r="2" fill="#555"/>
                <circle cx="7" cy="0" r="1.5" fill="#555"/>
                <circle cx="13" cy="0" r="1.5" fill="#555"/>
            </g>
            <text x="70" y="40" font-family="Arial, sans-serif" font-size="28" font-weight="bold" fill="#333">T</text>
            <text x="10" y="58" font-family="Arial, sans-serif" font-size="12" fill="#666">POWER!</text>
        </svg>
    </div>





