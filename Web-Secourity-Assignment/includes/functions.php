<?php
require_once 'config.php';

// دالة تسجيل المستخدم الجديد
function register_user($username, $email, $password, $full_name) {
    global $pdo;
    
    // تنظيف المدخلات
    $username = sanitize_input($username);
    $email = sanitize_input($email);
    $full_name = sanitize_input($full_name);
    
    // التحقق من صحة البيانات
    if (empty($username) || empty($email) || empty($password)) {
        return "جميع الحقول مطلوبة";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "البريد الإلكتروني غير صالح";
    }
    
    if (strlen($password) < 8) {
        return "كلمة المرور يجب أن تكون 8 أحرف على الأقل";
    }
    
    // التحقق من عدم وجود مستخدم بنفس الاسم أو البريد
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->rowCount() > 0) {
        return "اسم المستخدم أو البريد الإلكتروني موجود مسبقاً";
    }
    
    // تشفير كلمة المرور
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // إضافة المستخدم إلى قاعدة البيانات
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name) VALUES (?, ?, ?, ?)");
    
    try {
        $stmt->execute([$username, $email, $password_hash, $full_name]);
        return true;
    } catch (PDOException $e) {
        return "خطأ في التسجيل: " . $e->getMessage();
    }
}

// دالة تسجيل الدخول
function login_user($username, $password) {
    global $pdo;
    
    // التحقق من محاولات الدخول الفاشلة
    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        if (isset($_SESSION['lockout_time']) && time() - $_SESSION['lockout_time'] < LOCKOUT_TIME) {
            return "لقد تجاوزت عدد المحاولات المسموحة. الرجاء الانتظار 15 دقيقة";
        } else {
            // إعادة تعيين العدادات بعد انتهاء الوقت
            unset($_SESSION['login_attempts']);
            unset($_SESSION['lockout_time']);
        }
    }
    
    // تنظيف المدخلات
    $username = sanitize_input($username);
    
    // البحث عن المستخدم
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND is_active = TRUE");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // زيادة عداد المحاولات الفاشلة
        $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
        
        if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION['lockout_time'] = time();
        }
        
        return "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
    
    // التحقق من كلمة المرور
    if (!password_verify($password, $user['password_hash'])) {
        // زيادة عداد المحاولات الفاشلة
        $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
        
        if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION['lockout_time'] = time();
        }
        
        return "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
    
    // إعادة تعيين عدادات المحاولات الفاشلة
    unset($_SESSION['login_attempts']);
    unset($_SESSION['lockout_time']);
    
    // تحديث وقت آخر دخول
    $stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // تخزين بيانات المستخدم في الجلسة
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['logged_in'] = true;
    
    return true;
}

// دالة التحقق من تسجيل الدخول
function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// دالة تسجيل الخروج
function logout() {
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

// دالة الحصول على معلومات المستخدم
function get_user_info($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT username, email, full_name, created_at, last_login FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}
?>
