<?php

namespace App\Services\Payment\PaymentCodeGenerator;

interface PaymentCodeGenerator
{
    /**
     * @return string
     */
    public function generate(): string;
}
