<?php
include_once '../src/common/initialization.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$page_title = 'Login';
include_once './common/top.php';
?>

<div class="auth-container">
    <div class="auth-form-wrapper">
        <h2>Login to FootPower</h2>
        
        <form id="login-form" method="post" autocomplete="on" aria-describedby="login-error login-message">
            <div class="form-wrapper">
                <div class="form-row">
                    <div class="control-wrapper">
                        <label for="username">Username</label>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            required
                            autocomplete="username"
                            aria-label="Enter your username"
                            aria-describedby="username-help"
                        >
                        <span id="username-help" class="help-text">Enter your registered username</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="control-wrapper">
                        <label for="password">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            aria-label="Enter your password"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="control-wrapper">
                        <button id="login-button" class="btn btn-primary" type="submit">
                            Login
                        </button>
                    </div>
                </div>
            </div>

            <span id="login-error" class="error error-message" role="alert" aria-live="assertive"></span>
            <span id="login-message" class="form-message" role="status" aria-live="polite"></span>

            <!-- Hidden metadata fields -->
            <input type="hidden" id="user-verb" name="user-verb" value="login">
            <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_SESSION['token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<?php include_once './common/bottom.php'; ?>

<script>
// Login form handler
$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $loginBtn = $('#login-button');
        const $errorSpan = $('#login-error');
        const $messageSpan = $('#login-message');

        // Clear previous messages
        $errorSpan.text('').hide();
        $messageSpan.text('').hide();

        // Disable button during submission
        $loginBtn.prop('disabled', true).text('Logging in...');

        $.ajax({
            url: 'handlers/user_handler.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $messageSpan.text(response.message).show();
                    // Redirect to home page after successful login
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 1000);
                } else {
                    $errorSpan.text(response.message).show();
                    $loginBtn.prop('disabled', false).text('Login');
                }
            },
            error: function(xhr, status, error) {
                $errorSpan.text('Network error: ' + error).show();
                $loginBtn.prop('disabled', false).text('Login');
            }
        });
    });
});
</script>

