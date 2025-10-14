/**
 * Login.js - Handles the login functionality
 */

// DOM elements
const elements = {
  form: document.getElementById('loginForm'),
  email: document.getElementById('email'),
  password: document.getElementById('password'),
  loginBtn: document.getElementById('loginBtn'),
  loginBtnText: document.getElementById('loginBtnText'),
  loginSpinner: document.getElementById('loginSpinner'),
  message: document.getElementById('message')
};

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
  // Add event listeners
  elements.form.addEventListener('submit', handleSubmit);
  elements.email.addEventListener('input', clearMessages);
  elements.password.addEventListener('input', clearMessages);
  
  // Focus on email field when page loads
  elements.email.focus();
});

// Handle form submission
async function handleSubmit(e) {
  e.preventDefault();
  clearMessages();
  
  // Get and validate form values
  const email = elements.email.value.trim();
  const password = elements.password.value.trim();
  
  const errors = validateInput(email, password);
  if (errors.length > 0) {
    showError(errors.join('<br>'));
    return;
  }
  
  // Show loading state
  showLoading(true);
  
  try {
    // Attempt login
    const result = await window.API.login(email, password);
    
    // Process result
    if (result.status === 'success') {
      showSuccess('Login successful! Redirecting...');
      
      // Store user session data if available
      if (result.data && result.data.token) {
        localStorage.setItem('auth_token', result.data.token);
      }
      
      // Redirect after a short delay
      setTimeout(() => {
        // Ensure we're using http protocol for PHP processing
        window.location.href = 'http://localhost/invmanger/invmanger_web/views/home.php';
      }, 1000);
    } else {
      const msg = result.message || 'Invalid credentials';
      showError(msg);
      showLoading(false);
    }
  } catch (error) {
    console.error('Login error:', error);
    showError('Connection error. Please try again later.');
    showLoading(false);
  }
}

// Validate user input
function validateInput(email, password) {
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

// UI helper functions
function showLoading(isLoading) {
  elements.loginBtnText.textContent = isLoading ? 'Logging in...' : 'Login';
  elements.loginSpinner.style.display = isLoading ? 'inline-block' : 'none';
  elements.loginBtn.disabled = isLoading;
}

function showError(message) {
  elements.message.className = 'message error';
  elements.message.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
  elements.message.style.display = 'block';
}

function showSuccess(message) {
  elements.message.className = 'message success';
  elements.message.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
  elements.message.style.display = 'block';
}

function clearMessages() {
  elements.message.style.display = 'none';
  elements.message.textContent = '';
}

// Note: Initialization is now handled at the top of the file
