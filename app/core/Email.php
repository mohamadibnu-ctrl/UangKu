<?php

class Email {
    // Mode bisa 'dev' (log ke file) atau 'prod' (mengirim email sungguhan)
    private $mode = 'dev'; 
    private $logFile = __DIR__ . '/../logs/emails.log';

    public function __construct()
    {
        // Buat folder log jika belum ada
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        if (getenv('APP_ENV') === 'production') {
            $this->mode = 'prod';
        }
    }

    public function send($to, $subject, $body)
    {
        if ($this->mode === 'dev') {
            $logContent = "========================================\n";
            $logContent .= "Date: " . date('Y-m-d H:i:s') . "\n";
            $logContent .= "To: " . $to . "\n";
            $logContent .= "Subject: " . $subject . "\n";
            $logContent .= "Body: \n" . $body . "\n";
            $logContent .= "========================================\n\n";
            
            file_put_contents($this->logFile, $logContent, FILE_APPEND);
            return true;
        } else {
            // Pengaturan SMTP asli untuk production bisa disesuaikan di sini
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@uangku.com" . "\r\n";
            return mail($to, $subject, $body, $headers);
        }
    }
}
