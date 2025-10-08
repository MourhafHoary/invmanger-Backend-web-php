(function () {
  const form = document.getElementById('forgotForm');
  const actionBtn = document.getElementById('actionBtn');
  const message = document.getElementById('message');

  let stage = 1; // 1: checkEmail, 2: verifyCode, 3: resetPassword

  form?.addEventListener('submit', async (e) => {
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
