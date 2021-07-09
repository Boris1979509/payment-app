<?php

namespace App\Services\Payment\PaymentCodeGenerator;
/**
 * Class FakePaymentCodeGenerator
 * @package App\Services\Payment\PaymentCodeGenerator
 */
class FakePaymentCodeGenerator implements PaymentCodeGenerator
{

    /**
     * @return string
     */
    public function generate(): string
    {
        return 'testing code';
    }
}
