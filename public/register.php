<?php
include_once '../src/common/initialization.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$page_title = 'Register';
include_once './common/top.php';
?>

<div class="auth-container">
    <div class="auth-form-wrapper">
        <h2>Create an Account</h2>
        
        <form id="register-form" method="post" autocomplete="on" aria-describedby="register-error register-message">
            <div class="form-wrapper">
                <div class="form-row">
                    <div class="control-wrapper">
                        <label for="reg-username">Username</label>
                        <input
                            id="reg-username"
                            type="text"
                            name="username"
                            required
                            autocomplete="username"
                            aria-label="Choose a username"
                            aria-describedby="username-help"
                            minlength="3"
                            maxlength="150"
                        >
                        <span id="username-help" class="help-text">Must be at least 3 characters</span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="control-wrapper">
                        <label for="reg-password">Password</label>
                        <input
                            id="reg-password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            aria-label="Choose a password"
                            minlength="4"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="control-wrapper">
                        <label for="reg-password-confirm">Confirm Password</label>
                        <input
                            id="reg-password-confirm"
                            type="password"
                            name="password-confirm"
                            required
                            autocomplete="new-password"
                            aria-label="Confirm your password"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="control-wrapper">
                        <label for="distance-unit">Distance Unit</label>
                        <select id="distance-unit" name="distance-unit" aria-label="Select your preferred distance unit">
                            <option value="miles">Miles</option>
                            <option value="km">Kilometers</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="control-wrapper">
                        <button id="register-button" class="btn btn-primary" type="submit">
                            Register
                        </button>
                    </div>
                </div>
            </div>

            <span id="register-error" class="error error-message" role="alert" aria-live="assertive"></span>
            <span id="register-message" class="form-message" role="status" aria-live="polite"></span>

            <!-- Hidden metadata fields -->
            <input type="hidden" id="user-verb" name="user-verb" value="register">
            <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_SESSION['token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php include_once './common/bottom.php'; ?>

<script>
// Register form handler
$(document).ready(function() {
    // Client-side password confirmation check
    $('#reg-password-confirm').on('blur', function() {
        const password = $('#reg-password').val();
        const confirm = $(this).val();
        
        if (password !== confirm && confirm.length > 0) {
            $(this).addClass('error');
            $('#register-error').text('Passwords do not match').show();
        } else {
            $(this).removeClass('error');
            $('#register-error').hide();
        }
    });

    $('#register-form').submit(function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $registerBtn = $('#register-button');
        const $errorSpan = $('#register-error');
        const $messageSpan = $('#register-message');
        const password = $('#reg-password').val();
        const passwordConfirm = $('#reg-password-confirm').val();

        // Clear previous messages
        $errorSpan.text('').hide();
        $messageSpan.text('').hide();

        // Validate password match
        if (password !== passwordConfirm) {
            $errorSpan.text('Error: Passwords do not match').show();
            return;
        }

        // Disable button during submission
        $registerBtn.prop('disabled', true).text('Registering...');

        $.ajax({
            url: 'handlers/user_handler.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $messageSpan.text(response.message).show();
                    // Redirect to login page after successful registration
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                } else {
                    $errorSpan.text(response.message).show();
                    $registerBtn.prop('disabled', false).text('Register');
                }
            },
            error: function(xhr, status, error) {
                $errorSpan.text('Network error: ' + error).show();
                $registerBtn.prop('disabled', false).text('Register');
            }
        });
    });
});
</script>

