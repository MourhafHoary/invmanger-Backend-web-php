var server = "";

const form = document.getElementById("loginForm");
if (form) {
  form.addEventListener("submit", loginUser);
}

async function loginUser(e) {
  e.preventDefault(); // منع إعادة تحميل الصفحة عند الإرسال

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
}
