/**
 * Forgot Password MVC Implementation
 * This file implements the password recovery flow using the MVC pattern
 */

// Model - Handles data and business logic
class ForgotPasswordModel {
  constructor() {
    this.currentStep = 1;
    this.email = '';
    this.verificationCode = '';
    this.newPassword = '';
    this.confirmPassword = '';
    this.resendTimer = null;
    this.resendCountdown = 60;
  }

  // Request password reset code
  async requestCode(email) {
    try {
      this.email = email;
      const response = await window.API.forgot.check(email);
      return { success: true, message: response.message || 'Verification code sent to your email' };
    } catch (error) {
      return { success: false, message: window.APIUtil.extractMessage(error) || 'Failed to send verification code' };
    }
  }

  // Verify the code entered by user
  async verifyCode(code) {
    try {
      this.verificationCode = code;
      const response = await window.API.forgot.verify(this.email, code);
      return { success: true, message: response.message || 'Code verified successfully' };
    } catch (error) {
      return { success: false, message: window.APIUtil.extractMessage(error) || 'Invalid verification code' };
    }
  }

  // Reset password with the new password
  async resetPassword(newPassword, confirmPassword) {
    if (newPassword !== confirmPassword) {
      return { success: false, message: 'Passwords do not match' };
    }

    try {
      const response = await window.API.forgot.reset(this.email, this.verificationCode, newPassword);
      return { success: true, message: response.message || 'Password reset successfully' };
    } catch (error) {
      return { success: false, message: window.APIUtil.extractMessage(error) || 'Failed to reset password' };
    }
  }

  // Check password strength
  checkPasswordStrength(password) {
    if (!password) return { score: 0, feedback: 'Password is required' };
    
    let score = 0;
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[^A-Za-z0-9]/.test(password);
    
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 1;
    if (hasLower) score += 1;
    if (hasUpper) score += 1;
    if (hasNumber) score += 1;
    if (hasSpecial) score += 1;
    
    let feedback = '';
    if (score <= 2) {
      feedback = 'Weak';
    } else if (score <= 4) {
      feedback = 'Moderate';
    } else {
      feedback = 'Strong';
    }
    
    return { score: Math.min(score, 5), feedback };
  }

  // Start resend timer
  startResendTimer(callback) {
    this.resendCountdown = 60;
    clearInterval(this.resendTimer);
    
    this.resendTimer = setInterval(() => {
      this.resendCountdown--;
      callback(this.resendCountdown);
      
      if (this.resendCountdown <= 0) {
        clearInterval(this.resendTimer);
        callback(0);
      }
    }, 1000);
  }

  // Clear resend timer
  clearResendTimer() {
    clearInterval(this.resendTimer);
  }
}

// View - Handles UI updates
class ForgotPasswordView {
  constructor() {
    // Form elements
    this.form = document.getElementById('forgotPasswordForm');
    this.stepDescription = document.getElementById('step-description');
    
    // Step containers
    this.step1 = document.getElementById('step1');
    this.step2 = document.getElementById('step2');
    this.step3 = document.getElementById('step3');
    
    // Step 1 elements
    this.emailInput = document.getElementById('email');
    this.requestCodeBtn = document.getElementById('requestCodeBtn');
    this.requestCodeBtnText = document.getElementById('requestCodeBtnText');
    this.requestCodeSpinner = document.getElementById('requestCodeSpinner');
    
    // Step 2 elements
    this.digitInputs = [
      document.getElementById('digit1'),
      document.getElementById('digit2'),
      document.getElementById('digit3'),
      document.getElementById('digit4'),
      document.getElementById('digit5'),
      document.getElementById('digit6')
    ];
    this.verificationCodeInput = document.getElementById('verificationCode');
    this.resendCodeBtn = document.getElementById('resendCodeBtn');
    this.resendTimer = document.getElementById('resendTimer');
    this.timerCount = document.getElementById('timerCount');
    this.verifyCodeBtn = document.getElementById('verifyCodeBtn');
    this.verifyCodeBtnText = document.getElementById('verifyCodeBtnText');
    this.verifyCodeSpinner = document.getElementById('verifyCodeSpinner');
    
    // Step 3 elements
    this.newPasswordInput = document.getElementById('newPassword');
    this.confirmPasswordInput = document.getElementById('confirmPassword');
    this.toggleNewPassword = document.getElementById('toggleNewPassword');
    this.strengthMeter = document.getElementById('strengthMeter');
    this.strengthText = document.getElementById('strengthText');
    this.resetPasswordBtn = document.getElementById('resetPasswordBtn');
    this.resetPasswordBtnText = document.getElementById('resetPasswordBtnText');
    this.resetPasswordSpinner = document.getElementById('resetPasswordSpinner');
    
    // Message display
    this.messageDisplay = document.getElementById('message');
  }

  // Show specific step
  showStep(stepNumber) {
    // Hide all steps
    this.step1.classList.remove('active');
    this.step2.classList.remove('active');
    this.step3.classList.remove('active');
    
    // Show requested step
    switch (stepNumber) {
      case 1:
        this.step1.classList.add('active');
        this.stepDescription.textContent = 'Enter your email to reset your password';
        break;
      case 2:
        this.step2.classList.add('active');
        this.stepDescription.textContent = 'Enter the verification code sent to your email';
        // Focus on first digit input
        setTimeout(() => this.digitInputs[0].focus(), 100);
        break;
      case 3:
        this.step3.classList.add('active');
        this.stepDescription.textContent = 'Create a new password';
        setTimeout(() => this.newPasswordInput.focus(), 100);
        break;
    }
  }

  // Show loading state for a button
  showLoading(button, spinnerElement, textElement, isLoading) {
    if (isLoading) {
      textElement.style.opacity = '0';
      spinnerElement.style.display = 'inline-block';
      button.disabled = true;
    } else {
      textElement.style.opacity = '1';
      spinnerElement.style.display = 'none';
      button.disabled = false;
    }
  }

  // Show request code button loading state
  showRequestCodeLoading(isLoading) {
    this.showLoading(
      this.requestCodeBtn,
      this.requestCodeSpinner,
      this.requestCodeBtnText,
      isLoading
    );
  }

  // Show verify code button loading state
  showVerifyCodeLoading(isLoading) {
    this.showLoading(
      this.verifyCodeBtn,
      this.verifyCodeSpinner,
      this.verifyCodeBtnText,
      isLoading
    );
  }

  // Show reset password button loading state
  showResetPasswordLoading(isLoading) {
    this.showLoading(
      this.resetPasswordBtn,
      this.resetPasswordSpinner,
      this.resetPasswordBtnText,
      isLoading
    );
  }

  // Update resend timer display
  updateResendTimer(seconds) {
    if (seconds <= 0) {
      this.resendTimer.style.display = 'none';
      this.resendCodeBtn.style.display = 'inline';
    } else {
      this.timerCount.textContent = seconds;
      this.resendTimer.style.display = 'inline';
      this.resendCodeBtn.style.display = 'none';
    }
  }

  // Display message to user
  showMessage(message, isError = false) {
    this.messageDisplay.textContent = message;
    this.messageDisplay.style.display = 'block';
    
    if (isError) {
      this.messageDisplay.classList.add('error');
      this.messageDisplay.classList.remove('success');
    } else {
      this.messageDisplay.classList.add('success');
      this.messageDisplay.classList.remove('error');
    }
    
    // Auto hide after 5 seconds
    setTimeout(() => {
      this.messageDisplay.style.display = 'none';
    }, 5000);
  }

  // Update password strength indicator
  updatePasswordStrength(score, feedback) {
    const percentage = (score / 5) * 100;
    this.strengthMeter.style.width = `${percentage}%`;
    this.strengthText.textContent = feedback;
    
    // Update colors based on strength
    this.strengthMeter.className = 'strength-meter-fill';
    if (score <= 2) {
      this.strengthMeter.classList.add('weak');
    } else if (score <= 4) {
      this.strengthMeter.classList.add('medium');
    } else {
      this.strengthMeter.classList.add('strong');
    }
  }

  // Toggle password visibility
  togglePasswordVisibility() {
    const type = this.newPasswordInput.type === 'password' ? 'text' : 'password';
    this.newPasswordInput.type = type;
    this.toggleNewPassword.classList.toggle('fa-eye');
    this.toggleNewPassword.classList.toggle('fa-eye-slash');
  }

  // Get verification code from digit inputs
  getVerificationCode() {
    return this.digitInputs.map(input => input.value).join('');
  }
}

// Controller - Handles events and user interactions
class ForgotPasswordController {
  constructor(model, view) {
    this.model = model;
    this.view = view;
    
    // Initialize event listeners
    this.initEventListeners();
  }

  // Set up all event listeners
  initEventListeners() {
    // Step 1: Request code button
    this.view.requestCodeBtn.addEventListener('click', () => this.handleRequestCode());
    
    // Step 2: Verification code inputs
    this.view.digitInputs.forEach((input, index) => {
      // Auto-focus next input
      input.addEventListener('input', (e) => {
        if (e.target.value && index < this.view.digitInputs.length - 1) {
          this.view.digitInputs[index + 1].focus();
        }
        
        // Update hidden verification code input
        this.view.verificationCodeInput.value = this.view.getVerificationCode();
      });
      
      // Handle backspace to go to previous input
      input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
          this.view.digitInputs[index - 1].focus();
        }
      });
    });
    
    // Resend code button
    this.view.resendCodeBtn.addEventListener('click', () => this.handleResendCode());
    
    // Verify code button
    this.view.verifyCodeBtn.addEventListener('click', () => this.handleVerifyCode());
    
    // Password strength check
    this.view.newPasswordInput.addEventListener('input', () => {
      const { score, feedback } = this.model.checkPasswordStrength(this.view.newPasswordInput.value);
      this.view.updatePasswordStrength(score, feedback);
    });
    
    // Toggle password visibility
    this.view.toggleNewPassword.addEventListener('click', () => {
      this.view.togglePasswordVisibility();
    });
    
    // Reset password button
    this.view.resetPasswordBtn.addEventListener('click', () => this.handleResetPassword());
  }

  // Handle request code button click
  async handleRequestCode() {
    const email = this.view.emailInput.value.trim();
    
    if (!email) {
      this.view.showMessage('Please enter your email address', true);
      return;
    }
    
    if (!this.validateEmail(email)) {
      this.view.showMessage('Please enter a valid email address', true);
      return;
    }
    
    this.view.showRequestCodeLoading(true);
    
    const result = await this.model.requestCode(email);
    
    this.view.showRequestCodeLoading(false);
    
    if (result.success) {
      this.view.showMessage(result.message);
      this.view.showStep(2);
      this.model.startResendTimer((seconds) => {
        this.view.updateResendTimer(seconds);
      });
    } else {
      this.view.showMessage(result.message, true);
    }
  }

  // Handle resend code button click
  async handleResendCode() {
    this.view.resendCodeBtn.style.display = 'none';
    
    const result = await this.model.requestCode(this.model.email);
    
    if (result.success) {
      this.view.showMessage('Verification code resent to your email');
      this.model.startResendTimer((seconds) => {
        this.view.updateResendTimer(seconds);
      });
    } else {
      this.view.showMessage(result.message, true);
      this.view.resendCodeBtn.style.display = 'inline';
    }
  }

  // Handle verify code button click
  async handleVerifyCode() {
    const code = this.view.getVerificationCode();
    
    if (!code || code.length !== 6) {
      this.view.showMessage('Please enter the 6-digit verification code', true);
      return;
    }
    
    this.view.showVerifyCodeLoading(true);
    
    const result = await this.model.verifyCode(code);
    
    this.view.showVerifyCodeLoading(false);
    
    if (result.success) {
      this.view.showMessage(result.message);
      this.view.showStep(3);
      this.model.clearResendTimer();
    } else {
      this.view.showMessage(result.message, true);
    }
  }

  // Handle reset password button click
  async handleResetPassword() {
    const newPassword = this.view.newPasswordInput.value;
    const confirmPassword = this.view.confirmPasswordInput.value;
    
    if (!newPassword) {
      this.view.showMessage('Please enter a new password', true);
      return;
    }
    
    if (!confirmPassword) {
      this.view.showMessage('Please confirm your new password', true);
      return;
    }
    
    if (newPassword !== confirmPassword) {
      this.view.showMessage('Passwords do not match', true);
      return;
    }
    
    const { score } = this.model.checkPasswordStrength(newPassword);
    if (score < 3) {
      this.view.showMessage('Please use a stronger password', true);
      return;
    }
    
    this.view.showResetPasswordLoading(true);
    
    const result = await this.model.resetPassword(newPassword, confirmPassword);
    
    this.view.showResetPasswordLoading(false);
    
    if (result.success) {
      this.view.showMessage(result.message);
      // Redirect to login page after 2 seconds
      setTimeout(() => {
        window.location.href = 'login.html';
      }, 2000);
    } else {
      this.view.showMessage(result.message, true);
    }
  }

  // Validate email format
  validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  const model = new ForgotPasswordModel();
  const view = new ForgotPasswordView();
  const controller = new ForgotPasswordController(model, view);
});
