<?php
include_once '../src/common/initialization.php';
$page_title = 'Home';
// TOP
include_once('./common/top.php');
?>
<!-- TOP ENDS -->

<!-- MAIN CONTENT -->

<!-- Activity Log Toolbar -->
<div class="activity-log-toolbar" role="toolbar">
    <label for="activity-filter-date-from"> From </label>
    <input id="activity-filter-date-from" class="activity-filter" type="date" value="<?php echo date('Y-m-d', strtotime('-30 days')) ?>">
    <label for="activity-filter-date-to"> to </label>
    <input id="activity-filter-date-to" class="activity-filter" type="date" value="<?php echo date('Y-m-d') ?>">
    <label for="activity-filter-type">Type</label>
    <select id="activity-filter-type" class="activity-filter">
        <option id="activity-filter-type-all">All</option>
        <option id="activity-filter-type-walk">Walk</option>
        <option id="activity-filter-type-run">Run</option>
        <option id="activity-filter-type-cycle">Cycle</option>
    </select>
    <button id="data-create-button" class="btn" role="button">Add New</button>
</div>

<!-- The Activity Log appears here -->
<section id="activity-log" class="activity-log">
</section>


<!-- This hidden form contains the values we need to read the data: log-id, data-verb, and token -->
<form id="data-read-form" class="hidden">
    <input type="hidden" id="log-id" name="log-id" value="<?php echo $_SESSION['log_id']; ?>">
    <input type="hidden" id="token" name="token" value="<?= $_SESSION['token'] ?>">
</form>

<!-- If there's an error reading the data, the error message appears inside this span -->
<span id="read-error" class="error error-message"></span>


<!-- END MAIN -->


<!-- SIDE BAR  -->
<?php include_once('./common/sidebar.php'); ?>
<!-- END SIDEBAR -->

<!-- FOOTER / BOTTOM -->
<?php include_once('./common/bottom.php'); ?>

<!-- END FOOTER / BOTTOM -->