<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// ضبط رأس الاستجابة كـ JSON
header('Content-Type: application/json');

// التحقق من أن الطلب جاء عبر POST فقط
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "❌ الطلب غير صالح."]);
    exit;
}

// استقبال البيانات
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

// التحقق من صحة البيانات
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "❌ يرجى ملء جميع الحقول المطلوبة."]);
    exit;
}

// تحقق من صحة البريد الإلكتروني
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "❌ يرجى إدخال بريد إلكتروني صالح."]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // إعدادات SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'xxs8arhxx@gmail.com'; 
    $mail->Password = 'lrqn veid zreq kimr';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // تعيين معلومات البريد
    $mail->setFrom($email, $name);
    $mail->addAddress('xxs8arhxx@gmail.com'); 

    // محتوى الرسالة
    $mail->isHTML(true);
    $mail->Subject = 'رسالة جديدة من نموذج التواصل';
    $mail->Body = "
        <h3>تفاصيل الرسالة:</h3>
        <p><strong>الاسم:</strong> $name</p>
        <p><strong>البريد الإلكتروني:</strong> $email</p>
        <p><strong>الهاتف:</strong> $phone</p>
        <p><strong>الخدمة المطلوبة:</strong> $service</p>
        <p><strong>الرسالة:</strong><br>$message</p>
    ";

    $mail->send();
    echo json_encode(["status" => "success", "message" => "تم إرسال رسالتك بنجاح!"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "❌ حدث خطأ أثناء إرسال البريد: " . $mail->ErrorInfo]);
}
?>
