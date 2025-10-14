/**
 * Login.js - Handles the login functionality
 * Part of the MVC pattern: Controller for login view
 */

// Model - Data handling functions
const LoginModel = {
  // Attempt to login with provided credentials
  async login(email, password) {
    try {
      return await window.API.login(email, password);
    } catch (error) {
      console.error('Login error:', error);
      return { status: 'error', message: 'Connection error' };
    }
  },
  
  // Validate user input
  validateInput(email, password) {
    const errors = [];
    
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
    
    return errors;
  }
};

// View - UI manipulation functions
const LoginView = {
  // DOM elements
  elements: {
    form: document.getElementById('loginForm'),
    email: document.getElementById('email'),
    password: document.getElementById('password'),
    loginBtn: document.getElementById('loginBtn'),
    loginBtnText: document.getElementById('loginBtnText'),
    loginSpinner: document.getElementById('loginSpinner'),
    message: document.getElementById('message')
  },
  
  // Show loading state
  showLoading() {
    this.elements.loginBtnText.textContent = 'Logging in...';
    this.elements.loginSpinner.style.display = 'inline-block';
    this.elements.loginBtn.disabled = true;
  },
  
  // Hide loading state
  hideLoading() {
    this.elements.loginBtnText.textContent = 'Login';
    this.elements.loginSpinner.style.display = 'none';
    this.elements.loginBtn.disabled = false;
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
  }
};

// Controller - Event handlers and business logic
const LoginController = {
  init() {
    // Add event listeners
    LoginView.elements.form.addEventListener('submit', this.handleSubmit.bind(this));
    
    // Focus on email field when page loads
    LoginView.elements.email.focus();
    
    // Add input event listeners to clear error messages when user types
    LoginView.elements.email.addEventListener('input', LoginView.clearMessages.bind(LoginView));
    LoginView.elements.password.addEventListener('input', LoginView.clearMessages.bind(LoginView));
  },
  
  async handleSubmit(e) {
    e.preventDefault();
    
    // Clear previous messages
    LoginView.clearMessages();
    
    // Get form values
    const email = LoginView.elements.email.value.trim();
    const password = LoginView.elements.password.value.trim();
    
    // Validate input
    const errors = LoginModel.validateInput(email, password);
    if (errors.length > 0) {
      LoginView.showError(errors.join('<br>'));
      return;
    }
    
    // Show loading state
    LoginView.showLoading();
    
    try {
      // Attempt login
      const result = await LoginModel.login(email, password);
      
      // Process result
      if (result.status === 'success') {
        LoginView.showSuccess('Login successful! Redirecting...');
        
        // Store user session data if available
        if (result.data && result.data.token) {
          localStorage.setItem('auth_token', result.data.token);
        }
        
        // Redirect after a short delay
        setTimeout(() => {
          window.location.href = './home.php';
        }, 1000);
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Invalid credentials';
        LoginView.showError(msg);
        LoginView.hideLoading();
      }
    } catch (error) {
      console.error('Login error:', error);
      LoginView.showError('Connection error. Please try again later.');
      LoginView.hideLoading();
    }
  }
};

// Initialize the controller when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  LoginController.init();
});
