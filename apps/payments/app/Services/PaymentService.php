<?php

namespace App\Services;

class PaymentService
{
    public function payWithCard($number, $cvc, $expiry, $amount) {
        // Implement payment functionality

        echo "Processing payment...\n";

        if ($number == "3232323232323232") {
            return false;
        }
        if ($number == "4242424242424242") {
            sleep(3);
            return false;
        }
        if ($number == "5252525252525252") {
            sleep(3);
        }

        echo "Payment has been completed\n";

        return true;
    }
}
