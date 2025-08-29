<?php
/***
 * Activity Entry Form
 *
 * This form gathers:
 * - activity-type: Walk | Run | Cycle
 * - activity-date: YYYY-MM-DD
 * - activity-distance: numeric distance in the user's unit (km/mi)
 * - activity-duration: user-friendly time input (hh:mm:ss) that is split to hours/minutes/seconds in hidden fields
 *
 * Hidden fields:
 * - log-id: the ID of the log to which the new item will be added (from $_SESSION['log_id'])
 * - activity-id: during an update task, the ID of the activity currently being edited
 * - data-verb: CRUD operation verb (e.g., create | update | delete)
 * - token: current session token ($_SESSION['token'])
 */
?>

<form id="data-form" method="post" autocomplete="on" aria-describedby="form-error form-message">
    <div class="form-wrapper">
        <div class="form-row">
            <div class="control-wrapper">
                <label for="activity-type">Activity type</label>
                <select id="activity-type" name="activity-type" size="1" aria-label="Select the type of activity" required>
                    <option value="Walk">Walk</option>
                    <option value="Run">Run</option>
                    <option value="Cycle">Cycle</option>
                </select>
            </div>
            <div class="control-wrapper">
                <label for="activity-date">Activity date</label>
                <input
                    id="activity-date"
                    type="date"
                    name="activity-date"
                    aria-label="The date of the activity"
                    required
                >
            </div>
        </div>

        <div class="form-row">
            <div class="control-wrapper">
                <label for="activity-distance">Distance</label>
                <input
                    id="activity-distance"
                    type="number"
                    name="activity-distance"
                    min="0"
                    max="999"
                    step="0.01"
                    inputmode="decimal"
                    data-distance="0"
                    aria-label="The distance of the activity"
                    aria-describedby="distance-unit-help"
                >
                <span id="distance-unit-help">
                    <?php echo htmlspecialchars($_SESSION['distance_unit'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    <a href="../your_account.php">change</a>
                </span>
            </div>

            <div class="control-wrapper" id="activity-duration">
                <label for="activity-duration-time">Duration (hh:mm:ss)</label>
                <!-- User-friendly duration input; seconds enabled with step=1 -->
                <input
                    id="activity-duration-time"
                    type="time"
                    step="1"
                    aria-label="The duration of the activity in hours, minutes, and seconds"
                    placeholder="hh:mm:ss"
                />

                <!-- Hidden fields preserved for backend compatibility -->
                <input id="activity-duration-hours" type="hidden" name="activity-duration-hours" value="">
                <input id="activity-duration-minutes" type="hidden" name="activity-duration-minutes" value="">
                <input id="activity-duration-seconds" type="hidden" name="activity-duration-seconds" value="">
            </div>
        </div>

        <div class="form-row">
            <div class="control-wrapper">
                <div>
                    <button id="data-save-button" class="btn data-save-button" type="submit">
                        Save
                    </button>
                </div>
                <div>
                    <button id="data-cancel-button" class="btn btn-plain data-cancel-button" type="button">
                        Cancel
                    </button>
                    <span id="result" class="result-text" role="status" aria-live="polite"></span>
                </div>
            </div>

            <div class="control-wrapper">
                <div>
                    <button id="data-delete-button" class="btn data-delete-button" type="button">
                        Delete this Activity
                    </button>
                </div>
            </div>
        </div>
    </div>
    <span id="form-error" class="error error-message form-error-message" role="alert" aria-live="assertive"></span>
    <span id="form-message" class="form-message" role="status" aria-live="polite"></span>

    <!-- Hidden metadata fields -->
    <input type="hidden" id="log-id" name="log-id" value="<?php echo htmlspecialchars($_SESSION['log_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" id="activity-id" name="activity-id">
    <input type="hidden" id="data-verb" name="data-verb">
    <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_SESSION['token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
</form>

<script type="module">
/**
 * Modern, minimal JS to:
 * - keep hours/minutes/seconds fields in sync with a single time input
 * - prevent accidental submissions
 * - provide basic client-side validation and user feedback
 */
const form = document.querySelector('#data-form');
const saveBtn = document.querySelector('#data-save-button');
const cancelBtn = document.querySelector('#data-cancel-button');
const deleteBtn = document.querySelector('#data-delete-button');

const timeInput = document.querySelector('#activity-duration-time');
const hoursInput = document.querySelector('#activity-duration-hours');
const minutesInput = document.querySelector('#activity-duration-minutes');
const secondsInput = document.querySelector('#activity-duration-seconds');

const distanceInput = document.querySelector('#activity-distance');
const formError = document.querySelector('#form-error');
const formMessage = document.querySelector('#form-message');
const resultText = document.querySelector('#result');

// Utility: parse "HH:MM" or "HH:MM:SS" into {h, m, s}
const parseTime = (value) => {
    if (!value || typeof value !== 'string') return { h: '', m: '', s: '' };
    const parts = value.split(':').map(Number);
    const [h = 0, m = 0, s = 0] = parts;
    return { h: String(h), m: String(m), s: String(s) };
};

// Reflect timeInput into hidden fields
const syncDurationFields = () => {
    const { h, m, s } = parseTime(timeInput.value);
    hoursInput.value = h;
    minutesInput.value = m;
    secondsInput.value = s;
};

// Basic distance guardrails
const validateDistance = () => {
    formError.textContent = '';
    if (!distanceInput.value) return true; // optional
    const value = Number(distanceInput.value);
    if (Number.isNaN(value) || value < 0 || value > 999) {
        formError.textContent = 'Please enter a distance between 0 and 999.';
        return false;
    }
    return true;
};

// Prevent multiple submissions and provide basic UX feedback
const withSubmitLock = async (fn) => {
    saveBtn.disabled = true;
    saveBtn.setAttribute('aria-busy', 'true');
    resultText.textContent = 'Saving...';
    try {
        await fn();
    } finally {
        saveBtn.disabled = false;
        saveBtn.removeAttribute('aria-busy');
    }
};

// Events
timeInput?.addEventListener('change', syncDurationFields);
timeInput?.addEventListener('input', syncDurationFields);
distanceInput?.addEventListener('input', () => {
    // normalize to 2 decimals without being intrusive
    resultText.textContent = '';
    formError.textContent = '';
});

cancelBtn?.addEventListener('click', () => {
    form.reset();
    // Clear messages
    formError.textContent = '';
    formMessage.textContent = '';
    resultText.textContent = '';
});

deleteBtn?.addEventListener('click', () => {
    // Consumers can listen for this event to handle deletion flow (confirm, etc.)
    const evt = new CustomEvent('activity:delete:request', { bubbles: true });
    deleteBtn.dispatchEvent(evt);
});

// Main submit handler (non-AJAX; preserves default form submit unless errors are found)
form?.addEventListener('submit', async (e) => {
    // Sync duration fields before submit
    syncDurationFields();

    // Validate distance
    if (!validateDistance()) {
        e.preventDefault();
        return;
    }

    // Optional: prevent accidental double click while server processes request
    await withSubmitLock(async () => {
        // If you later switch to AJAX, you can serialize the form here
        // and POST via fetch(). For now we allow the default submit.
    });
});
</script>
