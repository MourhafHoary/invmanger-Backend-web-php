/**
 * Verify.js - Handles the account verification functionality
 */

// Initialize DOM elements when the document is ready
document.addEventListener('DOMContentLoaded', () => {
  initVerificationPage();
});

function initVerificationPage() {
  // Get DOM elements
  const form = document.getElementById('verifyForm');
  const emailInput = document.getElementById('email');
  const codeInput = document.getElementById('verfiycode');
  const verifyButton = document.getElementById('verifyButton');
  const verifySpinner = document.getElementById('verifySpinner');
  const messageElement = document.getElementById('message');
  const resendLink = document.getElementById('resendCode');
  
  // Auto-focus verification code input if email is in URL params
  const urlParams = new URLSearchParams(window.location.search);
  const emailParam = urlParams.get('email');
  if (emailParam) {
    emailInput.value = emailParam;
    codeInput.focus();
  }
  
  // Add event listeners
  if (form) {
    form.addEventListener('submit', handleVerify);
  }
  
  if (resendLink) {
    resendLink.addEventListener('click', handleResendCode);
  }
  
  // Handle form submission
  async function handleVerify(e) {
    e.preventDefault();
    clearMessages();
    
    // Get form values
    const email = emailInput.value.trim();
    const verificationCode = codeInput.value.trim();
    
    // Validate input
    const errors = validateInput(email, verificationCode);
    if (errors.length > 0) {
      showError(errors.join('<br>'));
      return;
    }
    
    // Show loading state
    showLoading();
    
    // Attempt verification
    try {
      const result = await window.API.verifyCode(email, verificationCode);
      
      if (result.status === 'success') {
        showSuccess('Verification successful! Redirecting...');
        
        // Redirect to home page after successful verification
        setTimeout(() => {
          window.location.href = 'home.html';
        }, 1500);
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Verification failed';
        showError(msg);
      }
    } catch (error) {
      console.error('Verification error:', error);
      showError('Connection error');
    } finally {
      hideLoading();
    }
  }
  
  // Handle resend code request
  async function handleResendCode(e) {
    e.preventDefault();
    
    const email = emailInput.value.trim();
    if (!email) {
      showError('Please enter your email address to resend the code');
      return;
    }
    
    // Start cooldown
    startResendCooldown();
    
    // Request new code
    try {
      // Check if the API has a resend verification code method
      if (window.API.resendVerificationCode) {
        const result = await window.API.resendVerificationCode(email);
        
        if (result.status === 'success') {
          showResendSuccess();
        } else {
          const msg = window.APIUtil.getMessage(result) || 'Failed to resend code';
          showError(msg);
        }
      } else {
        showError('Resend functionality not available');
      }
    } catch (error) {
      console.error('Resend code error:', error);
      showError('Connection error');
    }
  }
  
  // Validate input fields
  function validateInput(email, verificationCode) {
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
  
  // UI Helper Functions
  
  // Show loading state
  function showLoading() {
    verifyButton.disabled = true;
    verifySpinner.style.display = 'inline-block';
    verifyButton.querySelector('.button-text').style.opacity = '0.5';
  }
  
  // Hide loading state
  function hideLoading() {
    verifyButton.disabled = false;
    verifySpinner.style.display = 'none';
    verifyButton.querySelector('.button-text').style.opacity = '1';
  }
  
  // Show success message
  function showSuccess(message) {
    messageElement.className = 'auth-message success';
    messageElement.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    messageElement.style.display = 'block';
  }
  
  // Show error message
  function showError(message) {
    messageElement.className = 'auth-message error';
    messageElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    messageElement.style.display = 'block';
  }
  
  // Show resend success
  function showResendSuccess() {
    const resendMsg = document.createElement('div');
    resendMsg.className = 'resend-success';
    resendMsg.innerHTML = '<i class="fas fa-check-circle"></i> New code sent!';
    
    // Insert after the resend link
    resendLink.parentNode.appendChild(resendMsg);
    
    // Remove after 3 seconds
    setTimeout(() => {
      if (resendMsg.parentNode) {
        resendMsg.parentNode.removeChild(resendMsg);
      }
    }, 3000);
  }
  
  // Start cooldown for resend button
  function startResendCooldown(seconds = 60) {
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
  }
  
  // Clear all messages
  function clearMessages() {
    messageElement.style.display = 'none';
    messageElement.textContent = '';
  }
}
