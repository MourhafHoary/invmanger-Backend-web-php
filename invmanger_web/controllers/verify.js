/**
 * Verify.js - Handles the account verification functionality
 * Part of the MVC pattern: Model, View, and Controller for verification
 */

// Model - Data handling functions
const VerifyModel = {
  // Attempt to verify account with provided code
  async verifyCode(email, verificationCode) {
    try {
      return await window.API.verifyCode(email, verificationCode);
    } catch (error) {
      console.error('Verification error:', error);
      return { status: 'error', message: 'Connection error' };
    }
  },
  
  // Request a new verification code
  async resendCode(email) {
    try {
      // Check if the API has a resend verification code method
      if (window.API.resendVerificationCode) {
        return await window.API.resendVerificationCode(email);
      } else {
        // Fallback if the API doesn't have this method
        return { status: 'error', message: 'Resend functionality not available' };
      }
    } catch (error) {
      console.error('Resend code error:', error);
      return { status: 'error', message: 'Connection error' };
    }
  },
  
  // Validate input fields
  validateInput(email, verificationCode) {
    const errors = [];
    
    if (!email) {
      errors.push('Email is required');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errors.push('Please enter a valid email address');
    }
    
    if (!verificationCode) {
      errors.push('Verification code is required');
    } else if (!/^\d{5}$/.test(verificationCode)) {
      errors.push('Verification code must be 5 digits');
    }
    
    return errors;
  }
};

// View - UI manipulation functions
const VerifyView = {
  // DOM elements
  elements: {
    form: document.getElementById('verifyForm'),
    email: document.getElementById('email'),
    verificationCode: document.getElementById('verfiycode'),
    verifyButton: document.getElementById('verifyButton'),
    verifySpinner: document.getElementById('verifySpinner'),
    message: document.getElementById('message'),
    resendLink: document.getElementById('resendCode')
  },
  
  // Show loading state
  showLoading() {
    this.elements.verifyButton.disabled = true;
    this.elements.verifySpinner.style.display = 'inline-block';
    this.elements.verifyButton.querySelector('.button-text').style.opacity = '0.5';
  },
  
  // Hide loading state
  hideLoading() {
    this.elements.verifyButton.disabled = false;
    this.elements.verifySpinner.style.display = 'none';
    this.elements.verifyButton.querySelector('.button-text').style.opacity = '1';
  },
  
  // Show success message
  showSuccess(message) {
    this.elements.message.className = 'auth-message success';
    this.elements.message.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    this.elements.message.style.display = 'block';
  },
  
  // Show error message
  showError(message) {
    this.elements.message.className = 'auth-message error';
    this.elements.message.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    this.elements.message.style.display = 'block';
  },
  
  // Show resend success
  showResendSuccess() {
    const resendMsg = document.createElement('div');
    resendMsg.className = 'resend-success';
    resendMsg.innerHTML = '<i class="fas fa-check-circle"></i> New code sent!';
    
    // Insert after the resend link
    this.elements.resendLink.parentNode.appendChild(resendMsg);
    
    // Remove after 3 seconds
    setTimeout(() => {
      if (resendMsg.parentNode) {
        resendMsg.parentNode.removeChild(resendMsg);
      }
    }, 3000);
  },
  
  // Start resend cooldown
  startResendCooldown(seconds = 60) {
    const resendLink = this.elements.resendLink;
    const originalText = resendLink.textContent;
    resendLink.style.pointerEvents = 'none';
    resendLink.style.opacity = '0.5';
    
    let timeLeft = seconds;
    resendLink.textContent = `Resend (${timeLeft}s)`;
    
    const countdownInterval = setInterval(() => {
      timeLeft--;
      if (timeLeft <= 0) {
        clearInterval(countdownInterval);
        resendLink.textContent = originalText;
        resendLink.style.pointerEvents = 'auto';
        resendLink.style.opacity = '1';
      } else {
        resendLink.textContent = `Resend (${timeLeft}s)`;
      }
    }, 1000);
  },
  
  // Clear all messages
  clearMessages() {
    this.elements.message.style.display = 'none';
    this.elements.message.textContent = '';
  }
};

// Controller - Event handlers and business logic
const VerifyController = {
  init() {
    // Initialize event listeners
    VerifyView.elements.form.addEventListener('submit', this.handleVerify.bind(this));
    
    if (VerifyView.elements.resendLink) {
      VerifyView.elements.resendLink.addEventListener('click', this.handleResendCode.bind(this));
    }
    
    // Auto-focus verification code input if email is in URL params
    const urlParams = new URLSearchParams(window.location.search);
    const emailParam = urlParams.get('email');
    if (emailParam) {
      VerifyView.elements.email.value = emailParam;
      VerifyView.elements.verificationCode.focus();
    }
  },
  
  // Handle verification form submission
  async handleVerify(e) {
    e.preventDefault();
    VerifyView.clearMessages();
    
    const email = VerifyView.elements.email.value.trim();
    const verificationCode = VerifyView.elements.verificationCode.value.trim();
    
    // Validate input
    const errors = VerifyModel.validateInput(email, verificationCode);
    if (errors.length > 0) {
      VerifyView.showError(errors.join('<br>'));
      return;
    }
    
    // Show loading state
    VerifyView.showLoading();
    
    // Attempt to verify
    const result = await VerifyModel.verifyCode(email, verificationCode);
    
    // Hide loading state
    VerifyView.hideLoading();
    
    if (result.status === 'success') {
      VerifyView.showSuccess('Verification successful! Redirecting...');
      setTimeout(() => {
        window.location.href = 'home.html';
      }, 1500);
    } else {
      const msg = window.APIUtil.getMessage(result) || 'Verification failed';
      VerifyView.showError(msg);
    }
  },
  
  // Handle resend code request
  async handleResendCode(e) {
    e.preventDefault();
    
    const email = VerifyView.elements.email.value.trim();
    if (!email) {
      VerifyView.showError('Please enter your email address to resend the code');
      return;
    }
    
    // Start cooldown
    VerifyView.startResendCooldown();
    
    // Request new code
    const result = await VerifyModel.resendCode(email);
    
    if (result.status === 'success') {
      VerifyView.showResendSuccess();
    } else {
      const msg = window.APIUtil.getMessage(result) || 'Failed to resend code';
      VerifyView.showError(msg);
    }
  }
};

// Initialize the controller when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  VerifyController.init();
});
