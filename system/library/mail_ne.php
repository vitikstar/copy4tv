<?php
class Mail_NE {
    protected $to;
    protected $from;
    protected $return;
    protected $sender;
    protected $subject;
    protected $text;
    protected $html;
    protected $attachments = array();

    public $protocol = 'mail';
    public $hostname;
    public $username;
    public $password;
    public $port = 25;

    public function setTo($to) {
        $this->to = $to;
    }

    public function setFrom($from) {
        $this->from = $from;
    }

    public function setReturn($return) {
        $this->return = $return;
    }

    public function setSender($sender) {
        $this->sender = $sender;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setHtml($html) {
        $this->html = $html;
    }

    public function addAttachment($filename) {
        $this->attachments[] = $filename;
    }

    public function send() {
        if (!$this->to) {
            trigger_error('MAIL ERROR: E-Mail to required!');
            return;
        }

        if (!$this->from) {
            trigger_error('MAIL ERROR: E-Mail from required!');
            return;
        }

        if (!$this->sender) {
            trigger_error('MAIL ERROR: E-Mail sender required!');
            return;
        }

        if (!$this->subject) {
            trigger_error('MAIL ERROR: E-Mail subject required!');
            return;
        }

        if (!$this->text && !$this->html) {
            trigger_error('MAIL ERROR: E-Mail message required!');
            return;
        }

        require_once(DIR_SYSTEM . 'library/phpmailer/class.phpmailer.php');

        if ($this->protocol == 'mailgun') {
            return $this->send_mailgun();
        } else {
            return $this->send_phpmailer();
        }
    }

    private function send_phpmailer() {
        $mail  = new PHPMailer();
        $mail->CharSet = 'UTF-8';

        if (is_array($this->to)) {
            foreach ($this->to as $_to) {
                $mail->AddAddress($_to);
            }
        } else {
            $mail->AddAddress($this->to);
        }

        $mail->Subject = $this->subject;

        $mail->SetFrom($this->from, $this->sender);
        $mail->AddReplyTo($this->from, $this->sender);

        if (!$this->html) {
            $mail->Body = $this->text;
        } else {
            $mail->MsgHTML($this->html);
            if ($this->text) {
                $mail->AltBody = $this->text;
            } else {
                $mail->AltBody = 'This is a HTML email and your email client software does not support HTML email!';
            }
        }

        foreach ($this->attachments as $attachment) {
            if (file_exists($attachment)) {
                $mail->AddAttachment($attachment);
            }
        }

        if ($this->protocol == 'smtp') {
            $mail->IsSMTP();
            $mail->Host = $this->hostname;
            $mail->Port = $this->port;

            if ($this->port == '587') {
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "tls";
            } elseif ($this->port == '465') {
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "ssl";
            }

            if (!empty($this->username) && !empty($this->password)) {
                $mail->SMTPAuth = true;
                $mail->Username = $this->username;
                $mail->Password = $this->password;
            }
        }

        $ok = $mail->Send();

        if (!$ok && $mail->ErrorInfo) {
            trigger_error($mail->ErrorInfo);
        }

        return $ok;
    }

    private function send_mailgun() {
        $mail  = new PHPMailer();
        $mail->CharSet = 'UTF-8';

        if (is_array($this->to)) {
            foreach ($this->to as $_to) {
                $mail->AddAddress($_to);
            }
        } else {
            $mail->AddAddress($this->to);
        }

        $mail->Subject = $this->subject;

        $mail->SetFrom($this->from, $this->sender);
        $mail->AddReplyTo($this->from, $this->sender);

        if (!$this->html) {
            $mail->Body = $this->text;
        } else {
            $mail->MsgHTML($this->html);
            if ($this->text) {
                $mail->AltBody = $this->text;
            } else {
                $mail->AltBody = 'This is a HTML email and your email client software does not support HTML email!';
            }
        }

        foreach ($this->attachments as $attachment) {
            if (file_exists($attachment)) {
                $mail->AddAttachment($attachment);
            }
        }

        $mail->IsSMTP();
        $mail->Host = 'smtp.mailgun.org';
        $mail->Port = '587';

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        $mail->Username = $this->username;
        $mail->Password = $this->password;

        $ok = $mail->Send();

        if (!$ok && $mail->ErrorInfo) {
            trigger_error('MAIL ERROR: ' . $mail->ErrorInfo);
        }

        return $ok;
    }
}