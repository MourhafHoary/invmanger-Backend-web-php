// Centralized API client for invmanger
// Base URL for all API requests
const API_BASE = 'http://127.0.0.1/invmanger';

// Internal helper to POST form data and parse JSON safely
async function apiPost(path, params = {}) {
  const formData = new FormData();
  Object.entries(params).forEach(([k, v]) => {
    if (v !== undefined && v !== null) formData.append(k, v);
  });

  let response, text;
  try {
    response = await fetch(`${API_BASE}${path}`, {
      method: 'POST',
      body: formData
    });
    text = await response.text();
  } catch (err) {
    return { status: 'network_error', message: 'Network error', error: String(err) };
  }

  try {
    const json = JSON.parse(text);
    return json;
  } catch (e) {
    return { status: 'parse_error', message: 'Invalid JSON response', raw: text };
  }
}

// Expose API methods on window for simple usage in pages
window.API = {
  // Auth: Login
  async login(email, password) {
    return apiPost('/auth/login.php', { email, password });
  },

  // Auth: Signup
  async signup(username, email, password, phone) {
    return apiPost('/auth/signup.php', { username, email, password, phone });
  },

  // Auth: Verify code (after signup)
  async verifyCode(email, verfiycode) {
    return apiPost('/auth/verfiycode.php', { email, verfiycode });
  },

  // Forgot password flows
  forgot: {
    async checkEmail(email) {
      return apiPost('/forgetpassword/checkemail.php', { email });
    },
    async verifyCode(email, verfiycode) {
      return apiPost('/forgetpassword/verfiycode.php', { email, verfiycode });
    },
    async resetPassword(email, password) {
      return apiPost('/forgetpassword/resetpassword.php', { email, password });
    }
  }
};

// Utility to normalize API responses and extract message if present
window.APIUtil = {
  getMessage(result) {
    // handle possible different keys for message (some code uses 'message ' with a space)
    if (!result) return '';
    return result.message || result['message '] || '';
  }
};
