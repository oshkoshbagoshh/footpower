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


// function to handle AJAX request for data operations
function readActivities() {

    // get the form data and convert it to POST
    formData = $('#data-read-form').serializeArray();

    // Submit the data to the handler
    $.post('/handlers/data_handler.php', formData, function (data) {
        // code to handle the data returned from the server will go here

        // Convert the JSON string to a Javascript object
        var result = JSON.parse(data);

        // If there was an error, result.status will be defined
        if (typeof result.status !== 'undefined') {

             // If so, display the error
            $('#read-error').html(result.message).css('display', 'inline-block');
        } else {

            // Otherwise, go ahead and display the data
            activityLog -= result;
            applyFilters();
        }

    });
}


// ==================================================== //

//  FILTERING the data

// ======================================================//

/**
 *
 * Click handler for the Activity Log's Date "From" filter
 *
 */
$('#filter-activity-date-from').change(function () {
    applyFilters();
});

/**
 *
 * Click handler for the Activity Log's Date "To" filter
 *
 */
$('#activity-filter-date-to').change(function () {
    applyFilters();
});

/**
 *
 * Click handler for the Activity Log's Type filter
 *
 */
$('#activity-filter-type').change(function () {
    applyFilters();
})

/**
 *
 * Applies the current Activity Log filters
 *
 */
function applyFilters() {

    // Get the current filter values
    var earliestDateFilter = $('#activity-filter-date-from').val();
    var latestDateFilter = $('#activity-filter-date-to').val();
    var activityTypeFilter = $('#activity-filter-type > option:selected').text();

    // Filter based on the "From" date
    filteredLog = activityLog.filter(function (activity) {
        return activity.date >= earliestDateFilter;

    });

    // Filter based on the "To" date
    filteredLog = filteredLog.filter(function (activity) {
        return activity.date <= latestDateFilter;
    });

    // Filter based on the "Type" value
    if (activityTypeFilter === 'All') {
        displayActivityLog(filteredLog);
    } else {
        filteredLog = filteredLog.filter(function (activity) {
            return activity.type === activityTypeFilter;
        });
        displayActivityLog(filteredLog);
    }
    
    
}


/**
 * 
 * Display the actual data
 * 
 */
function displayActivityLog(log) {
    // Clear and build header
    $('.activity-log').empty();
    $('.activity-log').append(
        '<div id="activity-log-header" class="activity activity-log-header">' +
            '<div class="activity-item">Type</div>' +
            '<div class="activity-item">Date</div>' +
            '<div class="activity-item">Distance</div>' +
            '<div class="activity-item">Duration</div>' +
        '</div>'
    );

    // Helper to choose icon
    function getActivityIcon(type) {
        switch (type) {
            case 'Walk': return '<img src="images/walk.png" alt="Walk activity icon">';
            case 'Run':  return '<img src="images/run.png" alt="Run activity icon">';
            case 'Cycle':return '<img src="images/cycle.png" alt="Cycle activity icon">';
            default:     return '<img src="images/activity.png" alt="Activity icon">';
        }
    }

    // Helper to format duration
    function pad2(n) { return String(n).padStart(2, '0'); }
    function formatDuration(a) {
        // Accepts either totalSeconds or separate h/m/s properties
        if (typeof a.durationSeconds === 'number') {
            var s = a.durationSeconds;
            var h = Math.floor(s / 3600);
            var m = Math.floor((s % 3600) / 60);
            var sec = s % 60;
            return h + ':' + pad2(m) + ':' + pad2(sec);
        }
        var h2 = Number(a.durationHours || a.hours || 0);
        var m2 = Number(a.durationMinutes || a.minutes || 0);
        var s2 = Number(a.durationSeconds || a.seconds || 0);
        return h2 + ':' + pad2(m2) + ':' + pad2(s2);
    }

    // Render each activity row
    $.each(log, function (index, activity) {
        var rowId = 'activity' + index;
        var altClass = (index % 2 === 1) ? ' activity-alt' : '';
        $('.activity-log').append('<div id="' + rowId + '" class="activity' + altClass + '"></div>');

        var iconHtml = getActivityIcon(activity.type || '');

        // Type (with icon)
        $('#' + rowId).append(
            '<div class="activity-item activity-type">' +
                iconHtml + '<span class="activity-type-text">' + (activity.type || '') + '</span>' +
            '</div>'
        );

        // Date
        $('#' + rowId).append(
            '<div class="activity-item activity-date">' + (activity.date || '') + '</div>'
        );

        // Distance (assume numeric with optional unit)
        var distanceValue = (typeof activity.distance !== 'undefined') ? activity.distance : '';
        var distanceUnit = activity.unit || activity.distanceUnit || '';
        var distanceText = distanceValue + (distanceUnit ? ' ' + distanceUnit : '');
        $('#' + rowId).append(
            '<div class="activity-item activity-distance">' + distanceText + '</div>'
        );

        // Duration
        $('#' + rowId).append(
            '<div class="activity-item activity-duration">' + formatDuration(activity) + '</div>'
        );

        // Action buttons (Edit/Delete) – placeholders for updating/editing
        $('#' + rowId).append(
            '<div class="activity-item activity-actions">' +
                '<button type="button" class="activity-edit-btn" data-index="' + index + '">Edit</button>' +
                '<button type="button" class="activity-delete-btn" data-index="' + index + '">Delete</button>' +
            '</div>'
        );
    });

    // Note for CSS:
    // - Ensure .activity is a flex container for horizontal layout.
    // - Use .activity-alt to alternate backgrounds for readability.
    // Example (in CSS): .activity-alt { background: #f8f8f8; }
}
// TODO: Updating and Editing Data
// Placeholder handlers for editing/updating/deleting activities
