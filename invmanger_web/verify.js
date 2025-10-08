document.getElementById('verifyForm')?.addEventListener('submit', async (e) => {
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
