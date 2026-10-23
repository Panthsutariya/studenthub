/**
 * Practical 5: Registration Form with Frontend Validation & User-Friendly Errors
 * Features: Regular Expressions, Real-Time Feedback, Password Strength Meter, Canvas CAPTCHA
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT
 */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registrationForm') || document.querySelector('form');
  if (!form) return;

  const patterns = {
    username: /^[a-zA-Z0-9_]{3,25}$/,
    fullname: /^[a-zA-Z\s]{2,50}$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    mobile: /^[6-9]\d{9}$/,
    studentNumber: /^[0-9]{2}[A-Za-z]{2}[0-9]{3}$/
  };

  const getElement = (id) => document.getElementById(id) || form.querySelector(`[name="${id}"]`);

  const showError = (input, message) => {
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    let err = input.parentElement.querySelector('.form-error');
    if (!err) {
      err = document.createElement('span');
      err.className = 'form-error';
      input.parentElement.appendChild(err);
    }
    err.textContent = message;
    err.style.display = 'block';
  };

  const clearError = (input) => {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    const err = input.parentElement.querySelector('.form-error');
    if (err) {
      err.textContent = '';
      err.style.display = 'none';
    }
  };

  // Password Strength Meter
  const passwordInput = getElement('password');
  const meterFill = document.getElementById('pwdMeterFill');
  const meterText = document.getElementById('pwdMeterText');

  const checkPasswordStrength = (pwd) => {
    let score = 0;
    if (pwd.length >= 8) score++;
    if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
    if (/\d/.test(pwd)) score++;
    if (/[^a-zA-Z0-9]/.test(pwd)) score++;

    if (meterFill && meterText) {
      meterFill.className = 'pwd-meter-fill';
      if (pwd.length === 0) {
        meterFill.style.width = '0%';
        meterText.textContent = '';
      } else if (score <= 1) {
        meterFill.classList.add('pwd-weak');
        meterText.textContent = 'Weak (add numbers & special chars)';
        meterText.style.color = '#ef4444';
      } else if (score === 2) {
        meterFill.classList.add('pwd-fair');
        meterText.textContent = 'Fair';
        meterText.style.color = '#f59e0b';
      } else if (score === 3) {
        meterFill.classList.add('pwd-good');
        meterText.textContent = 'Good';
        meterText.style.color = '#3b82f6';
      } else {
        meterFill.classList.add('pwd-strong');
        meterText.textContent = 'Strong Password';
        meterText.style.color = '#22c55e';
      }
    }
    return score;
  };

  if (passwordInput) {
    passwordInput.addEventListener('input', () => {
      checkPasswordStrength(passwordInput.value);
    });
  }

  // Canvas CAPTCHA (Advanced Extension)
  let captchaCode = '';
  const captchaCanvas = document.getElementById('captchaCanvas');
  const refreshCaptchaBtn = document.getElementById('refreshCaptcha');

  const generateCaptcha = () => {
    if (!captchaCanvas) return;
    const ctx = captchaCanvas.getContext('2d');
    const chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghkmnpqrstuvwxyz';
    captchaCode = '';
    for (let i = 0; i < 5; i++) {
      captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    // Draw background
    ctx.clearRect(0, 0, captchaCanvas.width, captchaCanvas.height);
    ctx.fillStyle = '#f1f5f9';
    ctx.fillRect(0, 0, captchaCanvas.width, captchaCanvas.height);
    
    // Draw noise lines
    for (let i = 0; i < 4; i++) {
      ctx.strokeStyle = `rgba(${Math.random()*150}, ${Math.random()*150}, ${Math.random()*150}, 0.5)`;
      ctx.beginPath();
      ctx.moveTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
      ctx.lineTo(Math.random() * captchaCanvas.width, Math.random() * captchaCanvas.height);
      ctx.stroke();
    }

    // Draw characters
    ctx.font = 'bold 22px monospace';
    ctx.fillStyle = '#1e3a8a';
    for (let i = 0; i < captchaCode.length; i++) {
      ctx.save();
      const x = 20 + i * 22;
      const y = 28 + (Math.random() * 6 - 3);
      const angle = (Math.random() * 20 - 10) * Math.PI / 180;
      ctx.translate(x, y);
      ctx.rotate(angle);
      ctx.fillText(captchaCode[i], 0, 0);
      ctx.restore();
    }
  };

  if (captchaCanvas) {
    generateCaptcha();
    if (refreshCaptchaBtn) {
      refreshCaptchaBtn.addEventListener('click', (e) => {
        e.preventDefault();
        generateCaptcha();
      });
    }
  }

  // Validation function
  const validateField = (field) => {
    const name = field.name || field.id;
    const val = field.value.trim();

    if (field.required && !val && field.type !== 'checkbox' && field.type !== 'radio') {
      showError(field, 'This field is required.');
      return false;
    }

    if (name === 'fullname' || name === 'name') {
      if (!patterns.fullname.test(val)) {
        showError(field, 'Full name should be 2-50 characters with letters only.');
        return false;
      }
    } else if (name === 'username') {
      if (!patterns.username.test(val)) {
        showError(field, 'Username must be 3-25 alphanumeric characters or underscore.');
        return false;
      }
    } else if (name === 'email') {
      if (!patterns.email.test(val)) {
        showError(field, 'Please enter a valid email address (e.g., student@charusat.edu.in).');
        return false;
      }
    } else if (name === 'mobile' || name === 'phone') {
      if (!patterns.mobile.test(val)) {
        showError(field, 'Please enter a valid 10-digit mobile number starting with 6-9.');
        return false;
      }
    } else if (name === 'password') {
      if (val.length < 8) {
        showError(field, 'Password must be at least 8 characters long.');
        return false;
      }
    } else if (name === 'confirm_password' || name === 'confirmPassword') {
      const pwd = getElement('password');
      if (pwd && val !== pwd.value) {
        showError(field, 'Passwords do not match.');
        return false;
      }
    } else if (name === 'captcha') {
      if (captchaCode && val.toLowerCase() !== captchaCode.toLowerCase()) {
        showError(field, 'CAPTCHA code did not match.');
        return false;
      }
    }

    clearError(field);
    return true;
  };

  // Real-time validation on keyup and blur
  form.querySelectorAll('input, select, textarea').forEach((input) => {
    input.addEventListener('blur', () => validateField(input));
    input.addEventListener('keyup', () => {
      if (input.classList.contains('is-invalid')) {
        validateField(input);
      }
    });
  });

  // Validate on Submit
  form.addEventListener('submit', (e) => {
    let isValid = true;
    form.querySelectorAll('input[required], select[required], textarea[required]').forEach((input) => {
      if (input.type === 'checkbox' && !input.checked) {
        showError(input, 'You must accept the terms and conditions.');
        isValid = false;
      } else if (!validateField(input)) {
        isValid = false;
      }
    });

    const confirmPwd = getElement('confirm_password') || getElement('confirmPassword');
    if (confirmPwd && !validateField(confirmPwd)) {
      isValid = false;
    }

    const captchaInput = getElement('captcha');
    if (captchaInput && !validateField(captchaInput)) {
      isValid = false;
    }

    if (!isValid) {
      e.preventDefault();
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) firstInvalid.focus();
    }
  });
});
