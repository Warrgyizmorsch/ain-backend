<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $requestDetails;

    /**
     * Create a new message instance.
     *
     * @param array $requestDetails
     * @return void
     */
    public function __construct($requestDetails)
    {
        $this->requestDetails = $requestDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     return $this->view('order_confirmation')
    //                 ->with('details', $this->requestDetails);
    // }

    public function build()
    {
        return $this->view('order_confirmation') // Ensure karein ki view name sahi hai
                    ->subject('Your Assignment is Ready – Complete Your Order & Save More')
                    ->with('OrderData', $this->requestDetails); 
                    // Note: Maine 'OrderData' use kiya hai kyunki email template mein humne wahi variable use kiya hai.
    }
}
