

/****
 *  Data form to collect info
 *  need to style the form appropriately from MDN
 *
 */

<form id="data-form">
    <div class="form-wrapper">
        <div class="form-row">
            <div class="control-wrapper">
                <label for="activity-type">Activity type</label>
                <select id="activity-type" name="activity-type" size="1" aria-label="Select the type of activity">
                    <option value="Walk">Walk</option>
                    <option value="Run">Run</option>
                    <option value="Cycle">Cycle</option>
                </select>
            </div>
            <div class="control-wrapper">
                <label for="activity-date">Activity date </label>
                <input id="activity-date" type="date" name="activity-date" aria-label="The date of the activity"
                       required>
            </div>
        </div>

        <div class="form-row">
            <div class="control-wrapper">
                <label for="activity-distance">Distance</label>
                <input id="activity-distance" type="number" name="activity-distance" min="0" max="999" step=".01"
                       data-distance="0" aria-label="The distance of the activity">

                <span><?php echo $_SESSION['distance_unit'] ?>
                    (a href="your_account.php">change</a>)</span>
            </div>
            <div class="control-wrapper" id="activity-duration">
                <label for="activity-duration">Duration (hh:mm:ss)</label>
                <input id="activity-duration-hours" type="number" name="activity-duration-hours" min="0" max="999"
                       placeholder="hh" aria-label="The number of hours the activity required"> :
                <input id-="activity-duration-minutes" type="number" name="activity-duration-minutes" min="0" max="59"
                       placeholder="mm" aria-label="The number of minutes the activity required">
                <input id-="activity-duration-seconds" type="number" name="activity-duration-seconds" min="0" max="59"
                       placeholder="ss" aria-label="The number of seconds the activity required">
            </div>
        </div>
        <div class="form-row">
            <div class="control-wrapper">
                <div>
                    <button id="data-save-button" class="btn data-save-button" type="submit" role="button">
                        Save
                    </button>
                </div>
                <div>
                    <button id="data-cancel-button" class="btn btn-plain data-cancel-button" role="button">
                        Cancel
                    </button>
                    <span id="result" class="result-text"></span>
                </div>
            </div>

            <div class="control-wrapper">
                <div>
                    <button id="data-delete-button" class="btn data-delete-button" type="button" role="button">
                        Delete this Activity
                    </button>
                </div>
            </div>
        </div>
    </div>
    <span id="form-error" class="error error-message form-error-message"></span>
    <span id="form-message" class="form-message"></span>
    <input type="hidden" id="log-id" name="log-id" value="<?php
    echo $_SESSION['log_id']; ?>">
    <input type="hidden" id="activity-id" name="activity-id">
    <input type="hidden" id="data-verb" name="data-verb">
    <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
</form>









