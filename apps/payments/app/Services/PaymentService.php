<?php

namespace App\Services;

class PaymentService
{
    public function payWithCard($number, $cvc, $expiry) {
        // Implement payment functionality
        echo "Processing payment...\n";
        // sleep(rand(100, 400));
        echo "Payment has been completed\n";
        return true;
    }
}
