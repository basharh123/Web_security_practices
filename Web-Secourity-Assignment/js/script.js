// تبديل عرض كلمة المرور
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// التحقق من قوة كلمة المرور
function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    if (!strengthBar || !strengthText) return;
    
    // التحقق من الطول
    if (password.length >= 8) strength++;
    
    // التحقق من وجود حرف كبير
    if (/[A-Z]/.test(password)) strength++;
    
    // التحقق من وجود حرف صغير
    if (/[a-z]/.test(password)) strength++;
    
    // التحقق من وجود رقم
    if (/[0-9]/.test(password)) strength++;
    
    // التحقق من وجود رمز خاص
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    // تحديث شريط القوة
    const width = strength * 20;
    strengthBar.style.width = width + '%';
    
    // تحديث اللون والنص
    switch(strength) {
        case 0:
        case 1:
            strengthBar.style.backgroundColor = '#e74c3c';
            strengthText.textContent = 'قوة كلمة المرور: ضعيفة';
            break;
        case 2:
            strengthBar.style.backgroundColor = '#e67e22';
            strengthText.textContent = 'قوة كلمة المرور: مقبولة';
            break;
        case 3:
            strengthBar.style.backgroundColor = '#f1c40f';
            strengthText.textContent = 'قوة كلمة المرور: جيدة';
            break;
        case 4:
            strengthBar.style.backgroundColor = '#2ecc71';
            strengthText.textContent = 'قوة كلمة المرور: قوية';
            break;
        case 5:
            strengthBar.style.backgroundColor = '#27ae60';
            strengthText.textContent = 'قوة كلمة المرور: ممتازة';
            break;
    }
}

// التحقق من تطابق كلمتي المرور
function validatePasswords() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const submitBtn = document.querySelector('button[name="register"]');
    
    if (!password || !confirmPassword || !submitBtn) return;
    
    if (password.value !== confirmPassword.value) {
        confirmPassword.style.borderColor = '#e74c3c';
        confirmPassword.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.2)';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
    } else {
        confirmPassword.style.borderColor = '#2ecc71';
        confirmPassword.style.boxShadow = '0 0 0 3px rgba(46, 204, 113, 0.2)';
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
        submitBtn.style.cursor = 'pointer';
    }
}

// إضافة مستمعي الأحداث
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من قوة كلمة المرور أثناء الكتابة
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }
    
    // التحقق من تطابق كلمتي المرور
    const confirmPasswordInput = document.getElementById('confirm_password');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', validatePasswords);
        passwordInput.addEventListener('input', validatePasswords);
    }
    
    // التحقق من صحة البريد الإلكتروني
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#e74c3c';
                this.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.2)';
            } else if (this.value) {
                this.style.borderColor = '#2ecc71';
                this.style.boxShadow = '0 0 0 3px rgba(46, 204, 113, 0.2)';
            }
        });
    }
    
    // منع إرسال النموذج إذا كان هناك أخطاء
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredInputs = this.querySelectorAll('input[required]');
            let isValid = true;
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#e74c3c';
                    input.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.2)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة');
            }
        });
    });
    
    // إضافة تأثيرات للعناصر
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});

// دالة لإظهار رسائل التنبيه بشكل مؤقت
function showAlert(message, type = 'success', duration = 5000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
        ${message}
    `;
    
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, duration);
}
