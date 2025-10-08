document.getElementById('signUpForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();

  const username = document.getElementById('username').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const confirmPassword = document.getElementById('confirmPassword').value.trim();
  const phone = document.getElementById('phone').value.trim();

  if (!username || !email || !password || !confirmPassword || !phone) {
    document.getElementById('message').innerText = 'Please fill all fields.';
    return;
  }
  if (password !== confirmPassword) {
    document.getElementById('message').innerText = 'Passwords do not match.';
    return;
  }

  const result = await window.API.signup(username, email, password, phone);
  if (result.status === 'success') {
    // Navigate to verify code page
    window.location.href = 'verify.html';
  } else {
    const msg = window.APIUtil.getMessage(result) || 'Signup failed';
    document.getElementById('message').innerText = '❌ ' + msg;
  }
});
