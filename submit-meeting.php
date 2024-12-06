<?php

// تفعيل عرض الأخطاء لتسهيل التصحيح أثناء التطوير
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// استيراد المكتبات المطلوبة
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $supervisorId = $_SESSION['record'] ?? null;

    // استلام البيانات المرسلة من النموذج
    $meetingDate = $_POST['meeting_date'] ?? null;
    $meetingTime = $_POST['meeting_time'] ?? null;
    $meetingDuration = $_POST['meeting_duration'] ?? null;
    $selectedEmails = json_decode($_POST['selected_emails'], true) ?? [];

    // التحقق من إدخال جميع البيانات المطلوبة
    if (empty($meetingDate) || empty($meetingTime) || empty($meetingDuration)) {
        echo "<script>alert('Meeting details are incomplete!'); window.history.back();</script>";
        exit;
    }

    if (empty($selectedEmails) || !is_array($selectedEmails)) {
        echo "<script>alert('No valid email addresses provided!'); window.history.back();</script>";
        exit;
    }

    // إعداد الاتصال بـ Firebase
    try {
   $firebase = (new \Kreait\Firebase\Factory())
    ->withServiceAccount(__DIR__ . '/config/firebase-service-account.json')
    ->withDatabaseUri('https://smart-helmet-database-affb6-default-rtdb.firebaseio.com');
$database = $firebase->createDatabase();

 $reference = $database->getReference('test');
    $reference->set(['message' => 'Testing connection']);
    echo "Database connection successful!";

    } catch (Exception $e) {
        echo "<script>alert('Error initializing Firebase: {$e->getMessage()}'); window.history.back();</script>";
        exit;
    }

    // إنشاء معرف فريد للاجتماع
    $meetingId = uniqid('meeting');
    $meetingData = [
        'date' => $meetingDate,
        'time' => $meetingTime,
        'duration' => $meetingDuration,
        'emails' => $selectedEmails
    ];

    // حفظ بيانات الاجتماع في Firebase
    try {
        $database->getReference("supervisors/$supervisorId/meetings/$meetingId")->set($meetingData);

        // إرسال دعوات عبر البريد الإلكتروني
        foreach ($selectedEmails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                sendEmail($email, "Meeting Invitation", formatEmailBody($meetingDate, $meetingTime, $meetingDuration));
            }
        }

        // رسالة نجاح
        echo "<script>alert('Meeting invitations sent and saved successfully!'); window.location.href='metting.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error saving meeting: {$e->getMessage()}'); window.history.back();</script>";
    }
    exit;
}

// وظيفة لإرسال البريد الإلكتروني
function sendEmail($to, $subject, $body) {
    $phpmailer = new PHPMailer(true);
    try {
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->Port = 587;
        $phpmailer->Username = 'neno646192@gmail.com'; // بريد المرسل
        $phpmailer->Password = 'qlgh oixu rtmx uwsc';   // كلمة مرور التطبيق
        $phpmailer->setFrom('supervisor@smart-helmet.com', 'Supervisor');
        $phpmailer->addAddress($to);
        $phpmailer->isHTML(true);
        $phpmailer->Subject = $subject;
        $phpmailer->Body = $body;
        $phpmailer->send();
    } catch (Exception $e) {
        echo "<script>alert('Email to $to could not be sent. Error: {$phpmailer->ErrorInfo}'); window.history.back();</script>";
        exit;
    }
}

// وظيفة لتنسيق محتوى البريد الإلكتروني
function formatEmailBody($date, $time, $duration) {
    return "
        <h3>You are invited to a meeting</h3>
        <p><strong>Date:</strong> $date</p>
        <p><strong>Time:</strong> $time</p>
        <p><strong>Duration:</strong> $duration minutes</p>
        <p>Thank you!</p>
    ";
}
?>
