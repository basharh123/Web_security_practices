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

// معالجة نموذج تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!validate_csrf_token($csrf_token)) {
        $error = "رمز التحقق غير صالح";
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $result = login_user($username, $password);
        
        if ($result === true) {
            header("Location: home.php");
            exit();
        } else {
            $error = $result;
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
    <title>تسجيل الدخول - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="header">
                <h1><i class="fas fa-lock"></i> تسجيل الدخول</h1>
                <p>مرحباً بك في نظام الأمان الإلكتروني</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> تم إنشاء حسابك بنجاح! يمكنك الآن تسجيل الدخول.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> اسم المستخدم</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="أدخل اسم المستخدم" autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-key"></i> كلمة المرور</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required 
                               placeholder="أدخل كلمة المرور" autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
                    </button>
                </div>
                
                <div class="form-links">
                    <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
                </div>
            </form>
            
            <div class="security-tips">
                <h3><i class="fas fa-shield-alt"></i> نصائح أمنية:</h3>
                <ul>
                    <li>استخدم كلمة مرور قوية تحتوي على أحرف وأرقام ورموز</li>
                    <li>لا تشارك بيانات الدخول الخاصة بك مع أي شخص</li>
                    <li>تأكد من تسجيل الخروج بعد الانتهاء من استخدام النظام</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>مشروع أمن الويب - <?php echo date('Y'); ?> &copy; جميع الحقوق محفوظة</p>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
