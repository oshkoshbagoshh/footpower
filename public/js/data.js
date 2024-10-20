function initializeCreateDataForm() {

    // Hide the delete button
    $('#data-delete-button').hide();

    // Set the data verb to create
    $('#data-verb').val('create');

    // Populate the form
    $('#activity-type').val('Walk');

    var d = new Date();
    var todaysDate = d.getFullYear() + '-' + Number(d.getMonth() + 1).padWithZeros(2, 'left') + '-' + d.getDate().padWithZeros(2, 'left');
    console.log(todaysDate);

    $('#activity-date').val(todaysDate);
    $('#activity-distance').val(0);
    $('#activity-duration-hours').val(0);
    $('#activity-duration-minutes').val(0);
    $('#activity-duration-seconds').val(0);

}

// Send the form data to the server
$('#data-form').submit(function (e) {

    // Prevent default submission
    e.preventDefault();

    // Disable the Save button to prevent double submissions
    $('#data-save-button').prop('disabled', true);

    // Convert the data to POST format
    var formData = $(this).serializeArray();

    // Submit the data to the handler
    $.post('/handlers/data_handler.php', formData, function (data) {

        // Convert JSON string to a JavaaScript object
        var result = JSON.parse(data);

        if (result.status === 'error') {

            // Display error
            $('#form-error').html(result.message).css('display', 'inline-block');

            // Enable the save button
            $('#data-save-button').prop('disabled', false);


        } else {

            // display the success message
            $('#form-message').html(result.message).css('display', 'inline-block');

            // Return to the home page after 3 seconds
            window.setTimeout("window.location='index.php'", 3000);
        }

    });
});

