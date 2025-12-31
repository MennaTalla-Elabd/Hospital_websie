
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form.login_signup');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault(); 
        
        const email = form.querySelector('input[name="email"]').value.trim();
        const password = form.querySelector('input[name="password"]').value;
        const position = form.querySelector('input[name="Position"]:checked');
        
        let valid = true;
        let errorMessage = '';

    function validEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
        
        if (!email) {
            errorMessage += 'Email is required.\n';
            valid = false;
        } else if (!validEmail(email)) {
            errorMessage += 'Please enter a valid email address.\n';
            valid = false;
        }
        
        if (!password) {
            errorMessage += 'Password is required.\n';
            valid = false;
        } else if (password.length < 6) {
            errorMessage += 'Password must be at least 6 characters long.\n';
            valid = false;
        }
        
        if (!position) {
            errorMessage += 'Please select your position (Patient/Doctor/Staff).\n';
            valid = false;
        }
        
        if (!valid) {
            alert('Please fix the following errors:\n\n' + errorMessage);
            return false;
        }
        
        
        const positionValue = position.value();
        
        
        console.log('Login attempt:', {
            email: email,
            position: positionValue,
            timestamp: new Date().toISOString()
        });
        
        
        
        return true;
    });
    
    const inputs = form.querySelectorAll('input[type="text"], input[type="password"]');
    
    inputs.forEach(input => {
        
        input.addEventListener('focus', function() {
            this.style.borderColor = '#1d7be7ff';
            this.style.boxShadow = '0 0 5px rgba(47, 65, 198, 1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        });
    });
    
    

});
