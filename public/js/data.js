function initializeCreateDataForm() {
    const $deleteBtn = $('#data-delete-button');
    $deleteBtn.hide().attr('aria-hidden', 'true');

    const formDefaults = {
        '#activity-type': 'Walk',
        '#activity-distance': 0,
        '#activity-duration-hours': 0,
        '#activity-duration-minutes': 0,
        '#activity-duration-seconds': 0

    };

    // modern data formatting
    const now = new Date();
    const todaysDate = now.toISOString().split('T')[0];
    $('#activity-date').val(todaysDate);

    Object.entries(formDefaults).forEach(([selector, value]) => {
        $(selector).val(value);
    });
}

// Send the form data to the server
$('#data-form').submit(async (e) => {
    e.preventDefault();
    const $saveBtn = $('#data-save-button');
    const $form = $(e.target);

    try {
        $saveBtn.prop('disabled', true);
        const response = await $.post({
            url: '/handlers/data_handler.php',
            data: $form.serialize(),
            dataType: 'json' // Auto-parse JSON
        });

        if (response.status === 'error') {
            showFormError(response.message)
        } else {
            showFOrmSuccess(response.message);
            await new Promise(resolve => setTimeout(resolve, 3000));
            window.location.href = 'index.php';
        }

    } catch (error) {
        showFormError(`Network error: ${error.statusText}`);
    } finally {
        $saveBtn.prop('disabled', false);
    }

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
    const filters = {
        fromDate: $('#activity-filter-date-from').val(),
        toDate: $('#activity-filter-date-to').val(),
        type: $('#activity-filter-type').val()
    };

    const filtered = activityLog.filter(activity => {
        const activityDate = new Date(activity.date);
        return (
            (!filters.fromDate || activityDate >= new Date(filters.fromDate)) &&
            (!filters.toDate || activityDate <= new Date(filters.toDate)) &&
            (filters.type === 'All' || activity.type === filters.type)
        );
    });
    displayActivityLog(filtered);
}


/**
 *
 * Display the actual data
 *
 */
function displayActivityLog(log) {
    const $logContainer = $('.activity-log').empty();
    const header = createLogHeader();

    const entries = log.map((activity, index) =>
        createLogEntry(activity, index)
    );

    $logContainer.append([header, ...entries]);
}

function createLogEntry(activity, index) {
    return $(`
        <div class="activity ${index % 2 ? 'activity-alt' : ''}" 
             role="row"
             aria-labelledby="activity-${index}-type">
            <div class="activity-item activity-type" role="gridcell">
                ${getActivityIcon(activity.type)}
                <span class="activity-type-text">${activity.type}</span>
            </div>
            <div class="activity-item activity-date">${activity.date}</div>
            <div class="activity-item activity-distance">
                ${formatDistance(activity)}
            </div>
            <div class="activity-item activity-duration">
                ${formatDuration(activity)}
            </div>
            <div class="activity-item activity-actions">
                <button type="button" class="activity-edit-btn" 
                        data-id="${activity.id}" aria-label="Edit activity">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="activity-delete-btn"
                        data-id="${activity.id}" aria-label="Delete activity">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `);
};
// TODO: Updating and Editing Data
// Placeholder handlers for editing/updating/deleting activities

// unified duration handling
// Unified duration handling
function parseDuration(activity) {
    if (activity.durationSeconds) {
        return {
            hours: Math.floor(activity.durationSeconds / 3600),
            minutes: Math.floor((activity.durationSeconds % 3600) / 60),
            seconds: activity.durationSeconds % 60
        };
    }
    return {
        hours: activity.durationHours || 0,
        minutes: activity.durationMinutes || 0,
        seconds: activity.durationSeconds || 0
    };
}

function formatDuration(activity) {
    const {hours, minutes, seconds} = parseDuration(activity);
    return [hours, minutes, seconds]
        .map(n => String(n).padStart(2, '0'))
        .join(':');
}
