// Centralized API client for invmanger
const API_BASE = 'http://127.0.0.1/invmanger';

// Helper function to handle API requests
async function apiPost(path, params = {}) {
  // Create form data from parameters
  const formData = new FormData();
  Object.entries(params).forEach(([k, v]) => {
    if (v !== undefined && v !== null) formData.append(k, v);
  });

  try {
    // Make the request
    const response = await fetch(`${API_BASE}${path}`, {
      method: 'POST',
      body: formData
    });
    const text = await response.text();
    
    // Parse JSON response
    try {
      return JSON.parse(text);
    } catch (e) {
      return { status: 'parse_error', message: 'Invalid JSON response', raw: text };
    }
  } catch (err) {
    return { status: 'network_error', message: 'Network error', error: String(err) };
  }
}

// API methods
window.API = {
  // Authentication
  async login(email, password) {
    return apiPost('/auth/login.php', { email, password });
  },
  
  async signup(username, email, password, phone) {
    return apiPost('/auth/signup.php', { username, email, password, phone });
  },
  
  async verifyCode(email, verfiycode) {
    return apiPost('/auth/verfiycode.php', { email, verfiycode });
  },
  
  async resendVerificationCode(email) {
    return apiPost('/auth/resend.php', { email });
  },

  // Forgot password
  forgot: {
    async checkEmail(email) {
      return apiPost('/forgetpassword/checkemail.php', { email });
    },
    
    async check(email) {
      return this.checkEmail(email);
    },
    
    async verifyCode(email, verfiycode) {
      return apiPost('/forgetpassword/verfiycode.php', { email, verfiycode });
    },
    
    async verify(email, verfiycode) {
      return this.verifyCode(email, verfiycode);
    },
    
    async resetPassword(email, password) {
      return apiPost('/forgetpassword/resetpassword.php', { email, password });
    },
    
    async reset(email, verificationCode, password) {
      const pwd = password || verificationCode;
      return this.resetPassword(email, pwd);
    }
  }
};

// Response utilities
window.APIUtil = {
  getMessage(result) {
    if (!result) return '';
    return result.message || result['message '] || '';
  },
  
  extractMessage(error) {
    if (!error) return '';
    if (typeof error === 'string') return error;
    if (error.message) return error.message;
    if (error['message ']) return error['message '];
    if (error.error) return error.error;
    if (error.raw) return error.raw;
    return 'An error occurred';
  }
};

