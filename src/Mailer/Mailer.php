<?php
/**
 * Created by PhpStorm.
 * User: phungduong
 * Date: 2020-01-02
 * Time: 14:52
 */

namespace App\Mailer;

use SendGrid;
use SendGrid\Mail\Mail;


class Mailer
{
    const DEFAULT_FROM_EMAIL = 'admin@laptravels.com';
    const DEFAULT_FROM_NAME = 'LAPTravels System';

    /**
     * @var SendGrid
     */
    private $sendGrid;

    /**
     * Mailer constructor.
     * @param SendGrid $sendGrid
     */
    public function __construct(SendGrid $sendGrid)
    {
        $this->sendGrid = $sendGrid;
    }

    public function send(Email $email)
    {
        $emailParam = $this->emailToParams($email);
        $this->sendGrid->send($emailParam);
    }

    /**
     * @param Email $email
     * @return SendGrid\Mail\Mail
     * @throws SendGrid\Mail\TypeException
     */
    private function emailToParams(Email $email)
    {
        $mail =  new Mail();
        $mail->setFrom($email->fromEmail ?? self::DEFAULT_FROM_EMAIL, $email->fromName ?? self::DEFAULT_FROM_NAME);
        $mail->setSubject($email->subject);
        $mail->addTo($email->to);
        $mail->setTemplateId($email->template);

        return $mail;
    }

}