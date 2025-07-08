<?php

namespace App\Mail;

use App\Models\AllowanceHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManualAllowanceUsageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AllowanceHistory $record) {}

    public function build(): ManualAllowanceUsageMail
    {
        return $this->subject('Manual Allowance Credit')
            ->view('emails.manual-allowance-usage');
    }
}
