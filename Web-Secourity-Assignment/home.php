<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
if (!is_logged_in()) {
    header("Location: index.php");
    exit();
}

// الحصول على معلومات المستخدم
$user_info = get_user_info($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- الشريط الجانبي -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-shield-alt"></i> <?php echo SITE_NAME; ?></h2>
            </div>
            
            <div class="user-info">
                <div class="avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></h3>
                <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="home.php">
                            <i class="fas fa-home"></i> الصفحة الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="profile.php" onclick="alert('صفحة الملف الشخصي قيد التطوير')">
                            <i class="fas fa-user"></i> الملف الشخصي
                        </a>
                    </li>
                    <li>
                        <a href="settings.php" onclick="alert('صفحة الإعدادات قيد التطوير')">
                            <i class="fas fa-cog"></i> الإعدادات
                        </a>
                    </li>
                    <li>
                        <a href="security.php" onclick="alert('صفحة الأمان قيد التطوير')">
                            <i class="fas fa-lock"></i> الأمان
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a>
            </div>
        </div>
        
        <!-- المحتوى الرئيسي -->
        <div class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <h1>مرحباً، <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p>آخر دخول: <?php echo $user_info['last_login'] ? date('Y-m-d H:i:s', strtotime($user_info['last_login'])) : 'أول دخول'; ?></p>
                </div>
                <div class="header-right">
                    <span class="online-status">
                        <i class="fas fa-circle online"></i> متصل
                    </span>
                </div>
            </header>
            
            <div class="content">
                <div class="welcome-card">
                    <div class="welcome-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="welcome-text">
                        <h2>مرحباً بك في نظام الأمان الإلكتروني</h2>
                        <p>هذا النظام مصمم خصيصاً لمادة أمن الويب، ويتميز بنظام أمان متكامل باستخدام PDO وتشفير كلمات المرور.</p>
                    </div>
                </div>
                
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-icon" style="background-color: #4CAF50;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="card-content">
                            <h3>حسابك مفعل</h3>
                            <p>حسابك نشط وآمن</p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-icon" style="background-color: #2196F3;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-content">
                            <h3>تاريخ الإنشاء</h3>
                            <p><?php echo date('Y-m-d', strtotime($user_info['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-icon" style="background-color: #FF9800;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-content">
                            <h3>آخر نشاط</h3>
                            <p>الآن</p>
                        </div>
                    </div>
                </div>
                
                <div class="security-info">
                    <h2><i class="fas fa-info-circle"></i> معلومات أمنية</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <h4><i class="fas fa-hashtag"></i> تشفير كلمات المرور</h4>
                            <p>يتم تخزين كلمات المرور باستخدام خوارزمية <strong>bcrypt</strong> الآمنة</p>
                        </div>
                        <div class="info-item">
                            <h4><i class="fas fa-database"></i> حماية SQL Injection</h4>
                            <p>يستخدم النظام <strong>PDO Prepared Statements</strong> لمنع هجمات SQL Injection</p>
                        </div>
                        <div class="info-item">
                            <h4><i class="fas fa-shield-alt"></i> حماية CSRF</h4>
                            <p>يتم استخدام <strong>رموز CSRF</strong> لحماية النماذج من الهجمات</p>
                        </div>
                        <div class="info-item">
                            <h4><i class="fas fa-lock"></i> تأمين الجلسات</h4>
                            <p>الجلسات محمية وتنتهي تلقائياً بعد فترة من عدم النشاط</p>
                        </div>
                    </div>
                </div>
                
                <div class="system-info">
                    <h2><i class="fas fa-code"></i> تفاصيل المشروع</h2>
                    <p>هذا المشروع تم تطويره باستخدام:</p>
                    <ul>
                        <li><strong>PHP 7.4+</strong> مع دعم كامل للبرمجة الكائنية</li>
                        <li><strong>PDO</strong> للتفاعل الآمن مع قاعدة البيانات</li>
                        <li><strong>MySQL</strong> قاعدة البيانات العلائقية</li>
                        <li><strong>HTML5 & CSS3</strong> مع تصميم متجاوب</li>
                        <li><strong>JavaScript</strong> للتحقق من المدخلات وتحسين تجربة المستخدم</li>
                        <li><strong>مبادئ أمن الويب</strong> بما في ذلك التحقق من المدخلات وتشفير البيانات</li>
                    </ul>
                    <div class="github-link">
                        <a href="https://github.com" target="_blank" class="btn-github">
                            <i class="fab fa-github"></i> عرض المشروع على GitHub
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
