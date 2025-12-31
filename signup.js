document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form.login_signup');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); 
        
        const firstName = form.querySelector('input[name="firstName"]').value.trim();
        const secondName = form.querySelector('input[name="secondName"]').value.trim();
        const email = form.querySelector('input[name="email"]').value.trim();
        const password = form.querySelector('input[name="password"]').value;
        const gender = form.querySelector('input[name="gender"]:checked');

        let valid = true;
        let errorMessage = '';

        if (!firstName || firstName.length < 2) {
            errorMessage += 'First Name must be at least 2 characters long.\n';
            valid = false;
        }

        if (!secondName || secondName.length < 2) {
            errorMessage += 'Second Name must be at least 2 characters long.\n';
            valid = false;
        }

        if (!email) {
            errorMessage += 'Email is required.\n';
            valid = false;
        } else if (!validEmail(email)) {
            errorMessage += 'Please enter a valid email address.\n';
            valid = false;
        }

        function validEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        if (!password) {
            errorMessage += 'Password is required.\n';
            valid = false;
        } else if (password.length < 8) {
            errorMessage += 'Password must be at least 8 characters long.\n';
            valid = false;
        } else if (!hasUpperCase(password) || !hasLowerCase(password) || !hasNumber(password)) {
            errorMessage += 'Password must contain at least one uppercase letter, one lowercase letter, and one number.\n';
            valid = false;
        }

        function hasUpperCase(str) { return /[A-Z]/.test(str); }
        function hasLowerCase(str) { return /[a-z]/.test(str); }
        function hasNumber(str) { return /[0-9]/.test(str); }

        if (!gender) {
            errorMessage += 'Please select your gender.\n';
            valid = false;
        }

        if (!valid) {
            alert('Please fix the following errors:\n\n' + errorMessage);
            return false;
        } else {
            const genderValue = gender.value;
            alert(`Account created successfully!\n\nWelcome ${firstName} ${secondName}!\nEmail: ${email}\nGender: ${genderValue}`);
        }

        return true;
    });

    const inputs = form.querySelectorAll('input[type="text"], input[type="password"]');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#57e24a';
            this.style.boxShadow = '0 0 5px rgba(11, 181, 70, 0.5)';
        });

        input.addEventListener('blur', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });
    });

    // Password Strength Indicator
    const passwordInput = form.querySelector('input[name="password"]');
    const strengthIndicator = document.createElement('div');
    strengthIndicator.style.marginTop = '5px';
    strengthIndicator.style.fontSize = '12px';
    passwordInput.parentNode.appendChild(strengthIndicator);

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 'Weak';
        let color = 'red';

        if (password.length >= 8) {
            if ((hasUpperCase(password) || hasLowerCase(password)) && hasNumber(password)) {
                strength = 'Medium';
                color = 'orange';
            }
            if (hasUpperCase(password) && hasLowerCase(password) && hasNumber(password)) {
                strength = 'Strong';
                color = 'green';
            }
        }

        strengthIndicator.textContent = 'Password strength: ' + strength;
        strengthIndicator.style.color = color;
    });

    function hasUpperCase(str) { return /[A-Z]/.test(str); }
    function hasLowerCase(str) { return /[a-z]/.test(str); }
    function hasNumber(str) { return /[0-9]/.test(str); }

});
