<?php

namespace App\Mailer;

class Email
{
    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $fromName;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $to;

    /**
     * @var array
     */
    public $recipients = [];

    /**
     * @var array
     */
    public $attachments = [];

    /**
     * @var array
     */
    public $vars = [];

    /**
     * @var array
     */
    public $metadata = [];

    /**
     * @param string $template
     * @param string $locale
     * @param string $to
     * @param string $name
     *
     * @return self
     */
    public static function createInstance(string $template, string $locale, string $to, string $name): self
    {
        $email = new self();
        $email->template = $template;
        $email->locale = $locale;
        $email->to($to, $name);

        return $email;
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return self
     */
    public function from(string $email, string $name): self
    {
        $this->fromEmail = $email;
        $this->fromName = $name;

        return $this;
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return self
     */
    public function to(string $email, string $name): self
    {
        return $this->addRecipient($email, $name, 'to');
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return self
     */
    public function cc(string $email, string $name): self
    {
        return $this->addRecipient($email, $name, 'cc');
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return self
     */
    public function bcc(string $email, string $name): self
    {
        return $this->addRecipient($email, $name, 'bcc');
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $type
     *
     * @return self
     */
    public function addRecipient(string $email, string $name, string $type): self
    {
        $this->recipients[] = [
            'email' => $email,
            'name' => $name,
            'type' => $type,
        ];

        return $this;
    }

    /**
     * @param string $content
     * @param string $name
     *
     * @return self
     */
    public function attachCalendar(string $content, string $name = 'calendar.ics'): self
    {
        return $this->addAttachment('text/calendar', base64_encode($content), $name);
    }

    /**
     * @param string $type
     * @param string $content
     * @param string $name
     *
     * @return self
     */
    public function addAttachment(string $type, string $content, string $name): self
    {
        $this->attachments[] = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
        ];

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $variable
     *
     * @return self
     */
    public function setVar(string $name, $variable): self
    {
        $this->vars[$name] = $variable;

        return $this;
    }
}
