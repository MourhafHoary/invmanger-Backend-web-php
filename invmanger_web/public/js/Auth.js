// Auth.js - unified handlers for Login, Signup, Verify, and Forgot Password

// Login handler
(function () {
  const form = document.getElementById("loginForm");
  if (!form) return;
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!email || !password) {
      document.getElementById("message").innerText = "يرجى إدخال جميع الحقول.";
      return;
    }

    try {
      const result = await window.API.login(email, password);
      if (result.status === "success") {
        document.getElementById("message").innerText = "تم تسجيل الدخول بنجاح ✅";
        window.location.href = "home.html";
      } else {
        const msg = window.APIUtil.getMessage(result) || "تحقق من البيانات";
        document.getElementById("message").innerText = "❌ فشل تسجيل الدخول: " + msg;
      }
    } catch (error) {
      document.getElementById("message").innerText = "حدث خطأ في الاتصال بالسيرفر 😕";
      console.error(error);
    }
  });
})();

// Signup handler
(function () {
  const form = document.getElementById('signUpForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
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
      window.location.href = 'verify.html';
    } else {
      const msg = window.APIUtil.getMessage(result) || 'Signup failed';
      document.getElementById('message').innerText = '❌ ' + msg;
    }
  });
})();

// Verify code handler
(function () {
  const form = document.getElementById('verifyForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const verfiycode = document.getElementById('verfiycode').value.trim();

    if (!email || !verfiycode) {
      document.getElementById('message').innerText = 'Please enter email and code.';
      return;
    }

    const result = await window.API.verifyCode(email, verfiycode);
    if (result.status === 'success') {
      window.location.href = 'home.html';
    } else {
      const msg = window.APIUtil.getMessage(result) || 'Verification failed';
      document.getElementById('message').innerText = '❌ ' + msg;
    }
  });
})();

// Forgot password staged handler
(function () {
  const form = document.getElementById('forgotForm');
  if (!form) return;

  const actionBtn = document.getElementById('actionBtn');
  const message = document.getElementById('message');

  let stage = 1; // 1: checkEmail, 2: verifyCode, 3: resetPassword

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    message.textContent = '';

    const email = document.getElementById('email').value.trim();

    if (stage === 1) {
      if (!email) { message.textContent = 'Please enter your email.'; return; }
      const result = await window.API.forgot.checkEmail(email);
      if (result.status === 'success') {
        document.getElementById('step2').style.display = 'block';
        stage = 2;
        actionBtn.value = 'Verify Code';
        message.style.color = '#090';
        message.textContent = 'Email found. Check your email for the code.';
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Email not found';
        message.style.color = '#e00';
        message.textContent = '❌ ' + msg;
      }
    } else if (stage === 2) {
      const code = document.getElementById('code').value.trim();
      if (!email || !code) { message.textContent = 'Enter the code.'; return; }
      const result = await window.API.forgot.verifyCode(email, code);
      if (result.status === 'success') {
        document.getElementById('step3').style.display = 'block';
        stage = 3;
        actionBtn.value = 'Reset Password';
        message.style.color = '#090';
        message.textContent = 'Code verified. Enter a new password.';
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Invalid code';
        message.style.color = '#e00';
        message.textContent = '❌ ' + msg;
      }
    } else if (stage === 3) {
      const newpassword = document.getElementById('newpassword').value.trim();
      if (!email || !newpassword) { message.textContent = 'Enter the new password.'; return; }
      const result = await window.API.forgot.resetPassword(email, newpassword);
      if (result.status === 'success') {
        message.style.color = '#090';
        message.textContent = 'Password reset successfully. You can now login.';
        setTimeout(() => { window.location.href = 'login.html'; }, 800);
      } else {
        const msg = window.APIUtil.getMessage(result) || 'Reset failed';
        message.style.color = '#e00';
        message.textContent = '❌ ' + msg;
      }
    }
  });
})();
