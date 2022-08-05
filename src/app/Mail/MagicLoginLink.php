<?php

namespace Different\DifferentCore\app\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class MagicLoginLink extends Mailable
{
    use Queueable, SerializesModels;

    public $plaintextToken;

    public $expiresAt;

    public $name;

    public function __construct($plaintextToken, $expiresAt, $name)
    {
        $this->plaintextToken = $plaintextToken;
        $this->expiresAt = $expiresAt;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject(config('app.name').' - '.__('different-core::magic-link.login'))
            ->view('different-core::emails.magic-login-link', [
                'url' => URL::temporarySignedRoute('magic-link.verify', $this->expiresAt, [
                    'token' => $this->plaintextToken,
                ]),
                'name' => $this->name,
            ]);
    }
}
