$(document).ready(function() {
    let usernameTimer, emailTimer;

    $('#username').on('input', function() {
        clearTimeout(usernameTimer);
        usernameTimer = setTimeout(checkUsername, 500);
    });

    $('#email').on('input', function() {
        clearTimeout(emailTimer);
        emailTimer = setTimeout(checkEmail, 500);
    });

    $('#password').on('input', function() {
        checkPasswordStrength();
    });
     // Add this new event listener for profile picture
    $('#profile_picture').on('change', function() {
        checkProfilePicture();
    });

    function checkUsername() {
        const username = $('#username').val();
        const usernameRegex = /^[a-zA-Z0-9._]{4,}$/;
        
        if (username.length > 0) {
            if (username.length < 4) {
                $('#username-status').text('Username must be at least 4 characters long').css('color', 'red');
                return;
            }
            if (!usernameRegex.test(username)) {
                $('#username-status').text('Username can only contain letters, numbers, dots, and underscores').css('color', 'red');
                return;
            }
            
            $.ajax({
                url: 'check_username.php',
                method: 'POST',
                data: { username: username },
                success: function(response) {
                    if (response === 'available') {
                        $('#username-status').text('Username is available').css('color', 'green');
                    } else if (response === 'taken') {
                        $('#username-status').text('Username is already taken').css('color', 'red');
                    } else {
                        $('#username-status').text('Invalid username format').css('color', 'red');
                    }
                }
            });
        } else {
            $('#username-status').text('');
        }
    }

    function checkEmail() {
        const email = $('#email').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const allowedDomains = ['gmail.com', 'outlook.com', 'hotmail.com', 'live.com'];
        
        if (email.length > 0) {
            if (!emailRegex.test(email)) {
                $('#email-status').text('Invalid email format').css('color', 'red');
                return;
            }
            
            const domain = email.split('@')[1];
            if (!allowedDomains.includes(domain)) {
                $('#email-status').text('Only Gmail and Microsoft email providers are allowed').css('color', 'red');
                return;
            }
            
            $.ajax({
                url: 'check_email.php',
                method: 'POST',
                data: { email: email },
                success: function(response) {
                    if (response === 'available') {
                        $('#email-status').text('Email is available').css('color', 'green');
                    } else if (response === 'taken') {
                        $('#email-status').text('Email is already taken').css('color', 'red');
                    } else {
                        $('#email-status').text('Invalid email').css('color', 'red');
                    }
                }
            });
        } else {
            $('#email-status').text('');
        }
    }

    function checkPasswordStrength() {
        const password = $('#password').val();
        const strengthMeter = $('#password-strength');
        
        // Define regex patterns for each criterion
        const lengthRegex = /.{8,}/;
        const uppercaseRegex = /[A-Z]/;
        const lowercaseRegex = /[a-z]/;
        const numberRegex = /[0-9]/;
        const symbolRegex = /[!@#$%^&*()_+\-=$$$${};':"\\|,.<>\/?]/;

        // Check each criterion
        const criteria = [
            { regex: lengthRegex, element: $('#length-check') },
            { regex: uppercaseRegex, element: $('#uppercase-check') },
            { regex: lowercaseRegex, element: $('#lowercase-check') },
            { regex: numberRegex, element: $('#number-check') },
            { regex: symbolRegex, element: $('#symbol-check') }
        ];

        let strength = 0;

        criteria.forEach(criterion => {
            if (criterion.regex.test(password)) {
                criterion.element.text('●').css('color', 'green');
                strength++;
            } else {
                criterion.element.text('○').css('color', 'red');
            }
        });

        // Update overall strength meter
        if (strength < 2) {
            strengthMeter.text('Weak password').css('color', 'red');
        } else if (strength < 4) {
            strengthMeter.text('Medium strength password').css('color', 'orange');
        } else {
            strengthMeter.text('Strong password').css('color', 'green');
        }
    }

   function checkProfilePicture() {
        const fileInput = $('#profile_picture')[0];
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (file) {
            if (!allowedTypes.includes(file.type)) {
                $('#profile-picture-status').text('Invalid file type. Please upload a JPG, JPEG, or PNG file.').css('color', 'red');
                fileInput.value = ''; // Clear the file input
                $('#profile-picture-preview').hide();
            } else if (file.size > maxSize) {
                $('#profile-picture-status').text('File is too large. Maximum size is 5MB.').css('color', 'red');
                fileInput.value = ''; // Clear the file input
                $('#profile-picture-preview').hide();
            } else {
                $('#profile-picture-status').text('File is valid.').css('color', 'green');
                
                // Preview the image
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile-picture-preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        } else {
            $('#profile-picture-status').text('');
            $('#profile-picture-preview').hide();
        }
    }
});