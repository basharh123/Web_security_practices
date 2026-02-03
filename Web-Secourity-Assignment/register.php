<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

// إذا كان المستخدم مسجلاً بالفعل، توجيهه للصفحة الرئيسية
if (is_logged_in()) {
    header("Location: home.php");
    exit();
}

// معالجة نموذج التسجيل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!validate_csrf_token($csrf_token)) {
        $error = "رمز التحقق غير صالح";
    } else {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        
        // التحقق من تطابق كلمتي المرور
        if ($password !== $confirm_password) {
            $error = "كلمتا المرور غير متطابقتين";
        } else {
            $result = register_user($username, $email, $password, $full_name);
            
            if ($result === true) {
                header("Location: index.php?registered=true");
                exit();
            } else {
                $error = $result;
            }
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="header">
                <h1><i class="fas fa-user-plus"></i> إنشاء حساب جديد</h1>
                <p>انضم إلى نظام الأمان الإلكتروني</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="register-form" id="registerForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="full_name"><i class="fas fa-id-card"></i> الاسم الكامل</label>
                    <input type="text" id="full_name" name="full_name" 
                           placeholder="أدخل اسمك الكامل" value="<?php echo $_POST['full_name'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> اسم المستخدم *</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="أدخل اسم المستخدم" value="<?php echo $_POST['username'] ?? ''; ?>">
                    <small>يجب أن يكون اسم المستخدم فريداً</small>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني *</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="أدخل بريدك الإلكتروني" value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-key"></i> كلمة المرور *</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required 
                               placeholder="كلمة المرور (8 أحرف على الأقل)">
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <span id="strengthText">قوة كلمة المرور:</span>
                        <div class="strength-meter">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-key"></i> تأكيد كلمة المرور *</label>
                    <div class="password-container">
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="أعد إدخال كلمة المرور">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="agree_terms" name="agree_terms" required>
                        <label for="agree_terms">أوافق على <a href="#" onclick="alert('شروط الاستخدام ستظهر هنا')">شروط الاستخدام</a> و <a href="#" onclick="alert('سياسة الخصوصية ستظهر هنا')">سياسة الخصوصية</a></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="register" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> إنشاء الحساب
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> العودة لتسجيل الدخول
                    </a>
                </div>
            </form>
            
            <div class="password-requirements">
                <h3><i class="fas fa-info-circle"></i> متطلبات كلمة المرور:</h3>
                <ul>
                    <li>8 أحرف على الأقل</li>
                    <li>يجب أن تحتوي على حرف كبير على الأقل</li>
                    <li>يجب أن تحتوي على رقم على الأقل</li>
                    <li>يجب أن تحتوي على رمز خاص على الأقل (@, #, $, %, إلخ)</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
