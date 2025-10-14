/**
 * Signup.js - Handles the signup functionality
 */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  initSignupPage();
});

function initSignupPage() {
  // Get DOM elements
  const form = document.getElementById('signupForm');
  const usernameInput = document.getElementById('username');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirmPassword');
  const phoneInput = document.getElementById('phone');
  const signupBtn = document.getElementById('signupBtn');
  const signupBtnText = document.getElementById('signupBtnText');
  const signupSpinner = document.getElementById('signupSpinner');
  const messageElement = document.getElementById('message');
  const togglePassword = document.getElementById('togglePassword');
  const strengthMeter = document.getElementById('strengthMeter');
  const strengthText = document.getElementById('strengthText');
  const passwordStrength = document.getElementById('passwordStrength');
  
  // Set up event listeners
  if (form) {
    form.addEventListener('submit', handleSubmit);
  }
  
  if (passwordInput) {
    passwordInput.addEventListener('input', handlePasswordInput);
  }
  
  if (togglePassword) {
    togglePassword.addEventListener('click', togglePasswordVisibility);
  }
  
  // Add input event listeners to clear error messages when user types
  const inputFields = [usernameInput, emailInput, passwordInput, confirmPasswordInput, phoneInput];
  inputFields.forEach(field => {
    if (field) {
      field.addEventListener('input', clearMessages);
    }
  });
  
  // Focus on username field when page loads
  if (usernameInput) {
    usernameInput.focus();
  }
  
  // Handle password input for strength meter
  function handlePasswordInput() {
    const password = passwordInput.value;
    const strength = checkPasswordStrength(password);
    updatePasswordStrength(strength);
  }
  
  // Handle form submission
  async function handleSubmit(e) {
    e.preventDefault();
    clearMessages();
    
    // Get form values
    const username = usernameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();
    const confirmPassword = confirmPasswordInput.value.trim();
    const phone = phoneInput.value.trim();
    
    // Validate input
    const errors = validateInput(username, email, password, confirmPassword, phone);
    if (errors.length > 0) {
      showError(errors.join('<br>'));
      return;
    }
    
    // Show loading state
    showLoading();
    
    try {
      // Attempt signup
      const result = await window.API.signup(username, email, password, phone);
      
      if (result.status === 'success') {
        showSuccess('Account created successfully! Redirecting to verification...');
        
        // Store email for verification page
        localStorage.setItem('verification_email', email);
        
        // Redirect after a short delay
        setTimeout(() => {
          window.location.href = 'verify.html';
        }, 1500);
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Registration failed';
        showError(msg);
      }
    } catch (error) {
      console.error('Signup error:', error);
      showError('Connection error. Please try again later.');
    } finally {
      hideLoading();
    }
  }
  
  // Check password strength
  function checkPasswordStrength(password) {
    if (!password) return { score: 0, text: 'No password', percentage: 0 };
    
    let score = 0;
    const hasLowerCase = /[a-z]/.test(password);
    const hasUpperCase = /[A-Z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    // Add points for complexity
    if (hasLowerCase) score++;
    if (hasUpperCase) score++;
    if (hasNumbers) score++;
    if (hasSpecialChars) score++;
    
    // Add points for length
    if (password.length >= 12) {
      score++;
    } else if (password.length >= 8) {
      score += 0.5;
    }
    
    // Cap score at 4
    score = Math.min(4, score);
    
    // Calculate percentage for meter
    const percentage = (score / 4) * 100;
    
    // Determine text based on score
    let text = 'Weak';
    if (score >= 3.5) text = 'Very Strong';
    else if (score >= 2.5) text = 'Strong';
    else if (score >= 1.5) text = 'Moderate';
    
    return { score, text, percentage };
  }
  
  // Validate input fields
  function validateInput(username, email, password, confirmPassword, phone) {
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
  }
  
  // UI Helper Functions
  
  // Show loading state
  function showLoading() {
    signupBtnText.textContent = 'Creating Account...';
    signupSpinner.style.display = 'inline-block';
    signupBtn.disabled = true;
  }
  
  // Hide loading state
  function hideLoading() {
    signupBtnText.textContent = 'Sign Up';
    signupSpinner.style.display = 'none';
    signupBtn.disabled = false;
  }
  
  // Show success message
  function showSuccess(message) {
    messageElement.className = 'message success';
    messageElement.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    messageElement.style.display = 'block';
  }
  
  // Show error message
  function showError(message) {
    messageElement.className = 'message error';
    messageElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    messageElement.style.display = 'block';
  }
  
  // Toggle password visibility
  function togglePasswordVisibility() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Toggle icon
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash');
  }
  
  // Update password strength indicator
  function updatePasswordStrength(strength) {
    passwordStrength.style.display = 'block';
    strengthMeter.style.width = `${strength.percentage}%`;
    strengthText.textContent = strength.text;
    
    // Update color based on strength
    const colors = {
      'Weak': '#ff4d4d',
      'Moderate': '#ffaa00',
      'Strong': '#2db92d',
      'Very Strong': '#0066ff'
    };
    
    strengthMeter.style.backgroundColor = colors[strength.text] || 'var(--primary-color)';
  }
  
  // Clear all messages
  function clearMessages() {
    messageElement.style.display = 'none';
    messageElement.textContent = '';
  }
}
