<?php

namespace Tests\Unit\Payment;

use App\Services\Payment\PaymentCodeGenerator\UniquePaymentCodeGenerator;
use PHPUnit\Framework\TestCase;

class UniquePaymentCodeGeneratorTest extends TestCase
{
    /**
     * Код должен быть длинной в 16 символов
     * @test
     * @return void
     */
    public function the_code_must_be_16_characters_long(): void
    {
        $code = $this->getCode();
        $this->assertEquals(16, strlen($code));
    }

    /**
     * Код должен содержать
     * только заглавные буквы и цифры
     * @test
     * @return void
     */
    public function it_can_only_contain_uppercase_letters_and_numbers(): void
    {
        $code = $this->getCode();
        $this->assertMatchesRegularExpression('/^[A-Z0-9]+$/', $code);
    }

    /**
     * Код должен быть уникальным
     * @test
     * @return void
     */
    public function code_must_be_unique(): void
    {
        $codes = collect();
        $i = 0;
        while ($i <= 1000) {
            $codes->push($this->getCode());
            $i++;
        }
        $this->assertEquals($codes->count(), $codes->unique()->count());
    }

    /**
     * @return string
     */
    private function getCode(): string
    {
        return (new UniquePaymentCodeGenerator())->generate();
    }
}
