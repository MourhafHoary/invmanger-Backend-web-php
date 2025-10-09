/**
 * Signup.js - Handles the signup functionality
 * Part of the MVC pattern: Controller for signup view
 */

// Model - Data handling functions
const SignupModel = {
  // Attempt to register a new user
  async signup(username, email, password, phone) {
    try {
      return await window.API.signup(username, email, password, phone);
    } catch (error) {
      console.error('Signup error:', error);
      return { status: 'error', message: 'Connection error' };
    }
  },
  
  // Validate user input
  validateInput(username, email, password, confirmPassword, phone) {
    const errors = [];
    
    // Username validation
    if (!username) {
      errors.push('Username is required');
    } else if (username.length < 3) {
      errors.push('Username must be at least 3 characters');
    }
    
    // Email validation
    if (!email) {
      errors.push('Email is required');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errors.push('Please enter a valid email address');
    }
    
    // Password validation
    if (!password) {
      errors.push('Password is required');
    } else if (password.length < 6) {
      errors.push('Password must be at least 6 characters');
    }
    
    // Confirm password validation
    if (password !== confirmPassword) {
      errors.push('Passwords do not match');
    }
    
    // Phone validation
    if (!phone) {
      errors.push('Phone number is required');
    } else if (!/^\d{10,15}$/.test(phone.replace(/[\s-+()]/g, ''))) {
      errors.push('Please enter a valid phone number');
    }
    
    return errors;
  },
  
  // Check password strength
  checkPasswordStrength(password) {
    if (!password) return { score: 0, text: 'No password' };
    
    let score = 0;
    
    // Length check
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 1;
    
    // Complexity checks
    if (/[A-Z]/.test(password)) score += 1; // Has uppercase
    if (/[a-z]/.test(password)) score += 1; // Has lowercase
    if (/[0-9]/.test(password)) score += 1; // Has number
    if (/[^A-Za-z0-9]/.test(password)) score += 1; // Has special char
    
    // Determine text based on score
    let text = '';
    let percentage = 0;
    
    switch (true) {
      case (score <= 2):
        text = 'Weak';
        percentage = 25;
        break;
      case (score <= 4):
        text = 'Moderate';
        percentage = 50;
        break;
      case (score <= 5):
        text = 'Strong';
        percentage = 75;
        break;
      default:
        text = 'Very Strong';
        percentage = 100;
    }
    
    return { score, text, percentage };
  }
};

// View - UI manipulation functions
const SignupView = {
  // DOM elements
  elements: {
    form: document.getElementById('signupForm'),
    username: document.getElementById('username'),
    email: document.getElementById('email'),
    password: document.getElementById('password'),
    confirmPassword: document.getElementById('confirmPassword'),
    phone: document.getElementById('phone'),
    signupBtn: document.getElementById('signupBtn'),
    signupBtnText: document.getElementById('signupBtnText'),
    signupSpinner: document.getElementById('signupSpinner'),
    message: document.getElementById('message'),
    togglePassword: document.getElementById('togglePassword'),
    strengthMeter: document.getElementById('strengthMeter'),
    strengthText: document.getElementById('strengthText'),
    passwordStrength: document.getElementById('passwordStrength')
  },
  
  // Show loading state
  showLoading() {
    this.elements.signupBtnText.textContent = 'Creating Account...';
    this.elements.signupSpinner.style.display = 'inline-block';
    this.elements.signupBtn.disabled = true;
  },
  
  // Hide loading state
  hideLoading() {
    this.elements.signupBtnText.textContent = 'Sign Up';
    this.elements.signupSpinner.style.display = 'none';
    this.elements.signupBtn.disabled = false;
  },
  
  // Show error message
  showError(message) {
    this.elements.message.className = 'message error';
    this.elements.message.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    this.elements.message.style.display = 'block';
  },
  
  // Show success message
  showSuccess(message) {
    this.elements.message.className = 'message success';
    this.elements.message.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    this.elements.message.style.display = 'block';
  },
  
  // Clear all messages
  clearMessages() {
    this.elements.message.style.display = 'none';
    this.elements.message.textContent = '';
  },
  
  // Update password strength indicator
  updatePasswordStrength(strength) {
    this.elements.passwordStrength.style.display = 'block';
    this.elements.strengthMeter.style.width = `${strength.percentage}%`;
    this.elements.strengthText.textContent = strength.text;
    
    // Update color based on strength
    const colors = {
      'Weak': 'var(--error-color)',
      'Moderate': 'var(--warning-color)',
      'Strong': 'var(--success-color)',
      'Very Strong': 'var(--success-color)'
    };
    
    this.elements.strengthMeter.style.backgroundColor = colors[strength.text] || 'var(--primary-color)';
  },
  
  // Toggle password visibility
  togglePasswordVisibility() {
    const passwordField = this.elements.password;
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    
    // Toggle icon
    this.elements.togglePassword.classList.toggle('fa-eye');
    this.elements.togglePassword.classList.toggle('fa-eye-slash');
  }
};

// Controller - Event handlers and business logic
const SignupController = {
  init() {
    // Add event listeners
    SignupView.elements.form.addEventListener('submit', this.handleSubmit.bind(this));
    SignupView.elements.password.addEventListener('input', this.handlePasswordInput.bind(this));
    SignupView.elements.togglePassword.addEventListener('click', SignupView.togglePasswordVisibility.bind(SignupView));
    
    // Add input event listeners to clear error messages when user types
    const inputFields = [
      SignupView.elements.username,
      SignupView.elements.email,
      SignupView.elements.password,
      SignupView.elements.confirmPassword,
      SignupView.elements.phone
    ];
    
    inputFields.forEach(field => {
      field.addEventListener('input', SignupView.clearMessages.bind(SignupView));
    });
    
    // Focus on username field when page loads
    SignupView.elements.username.focus();
  },
  
  handlePasswordInput() {
    const password = SignupView.elements.password.value;
    const strength = SignupModel.checkPasswordStrength(password);
    SignupView.updatePasswordStrength(strength);
  },
  
  async handleSubmit(e) {
    e.preventDefault();
    
    // Clear previous messages
    SignupView.clearMessages();
    
    // Get form values
    const username = SignupView.elements.username.value.trim();
    const email = SignupView.elements.email.value.trim();
    const password = SignupView.elements.password.value.trim();
    const confirmPassword = SignupView.elements.confirmPassword.value.trim();
    const phone = SignupView.elements.phone.value.trim();
    
    // Validate input
    const errors = SignupModel.validateInput(username, email, password, confirmPassword, phone);
    if (errors.length > 0) {
      SignupView.showError(errors.join('<br>'));
      return;
    }
    
    // Show loading state
    SignupView.showLoading();
    
    try {
      // Attempt signup
      const result = await SignupModel.signup(username, email, password, phone);
      
      // Process result
      if (result.status === 'success') {
        SignupView.showSuccess('Account created successfully! Redirecting to verification...');
        
        // Store email for verification page
        localStorage.setItem('verification_email', email);
        
        // Redirect after a short delay
        setTimeout(() => {
          window.location.href = 'verify.html';
        }, 1500);
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Registration failed';
        SignupView.showError(msg);
        SignupView.hideLoading();
      }
    } catch (error) {
      console.error('Signup error:', error);
      SignupView.showError('Connection error. Please try again later.');
      SignupView.hideLoading();
    }
  }
};

// Initialize the controller when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  SignupController.init();
});
