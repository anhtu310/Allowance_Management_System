<?php
namespace App\Mail;

use App\Models\AllowanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AllowanceRequestResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AllowanceRequest $request)
    {
        $this->request->load([
            'histories.voucher',
            'customer',
        ]);
    }

    public function build(): self
    {
        return $this->subject('Your allowance request has been processed')
            ->view('emails.allowance-request-result');
    }
}
