<?php
namespace Harps\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SMTP
{
    /**
     * Mailer using PHPMailer API
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    public $mail;

    /**
     * Initializing optionals SMTP parameters
     * @param string $host Optional SMTP host
     * @param string $username Optional SMTP username
     * @param string $password Optional SMTP password
     * @param int $port Optional SMTP port
     * @param string $encrypt Optional SMTP encryption method
     * @throws \Exception
     */
    public function __construct(string $host = SMTP_HOST, string $username = SMTP_USER, string $password = SMTP_PASS, int $port = SMTP_PORT, string $encrypt = SMTP_ENCRYPT)
    {
        if (isset($host) && isset($username) && isset($password) && isset($port) && isset($encrypt)) {
            $this->mail = new PHPMailer(true);

            $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();

            $this->mail->Host = $host;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $username;
            $this->mail->Password = $password;
            $this->mail->SMTPSecure = $encrypt;
            $this->mail->Port = $port;
        } else {
            $backtrace = debug_backtrace()[0];
            throw new \Exception("SMTP connexion failed, check your Harps parameters" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }
    }
}
