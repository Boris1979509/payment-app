<?php

namespace App\Services\Payment\PaymentCodeGenerator;

use Illuminate\Support\Str;

class UniquePaymentCodeGenerator implements PaymentCodeGenerator
{

    /**
     * @return string
     */
    public function generate(): string
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 16)), 0, 16);
    }
}
