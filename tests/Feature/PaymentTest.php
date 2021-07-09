<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\Payment\PaymentCodeGenerator\FakePaymentCodeGenerator;
use App\Services\Payment\PaymentCodeGenerator\PaymentCodeGenerator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase, GetUser;

    /**
     * Покупатель может видеть форму
     * для создания иового счёта на оплату
     * * * * *
     * @test
     */
    public function customer_can_see_a_form_for_creating_new_payment(): void
    {
        /* @var $user User */
        $this->withoutExceptionHandling(); // Позволяет увидеть более развёрнутый вывод ошибки
        $user = User::factory()->make(); // Create a single instance dont save to database
        $this->actingAs($user) // Действуем в качестве аутенфицированного пользователя
        ->get('new-payment')
            ->assertStatus(200)
            ->assertSee('Create new payment');
    }

    /**
     * Не аутенфицированные пользователи
     * не могут создать новый счёт на оплату
     * @test
     */
    public function not_authenticated_users_cant_create_a_new_payment(): void
    {
        /* @var $user User */
        $this->withoutExceptionHandling([AuthenticationException::class]); // Позволяет увидеть более развёрнутый вывод ошибки
        $user = User::factory()->make();
        $response = $this->get('new-payment');
        $response->assertStatus(302)
            ->assertRedirect('login');
    }

    /**
     * Пользователь не может создать
     * новый счёт на оплату
     * @test
     * @return void
     */
    public function user_cant_creat_a_new_payment(): void
    {
        $this->withoutExceptionHandling([AuthenticationException::class]); // Позволяет увидеть более развёрнутый вывод ошибки
        $response = $this->json('post', 'payment', [
            'amount'      => 5000, // $50
            'currency'    => 'USD',
            'description' => 'New payment',
            'message'     => 'Some message',
        ]);
        $response->assertStatus(401);
        $this->assertEquals(0, Payment::count());
    }

    /**
     * Авторизованный
     * пользователь может создать
     * новый счёт на оплату
     * @test
     * @return void
     */
    public function auth_user_can_creat_a_new_payment(): void
    {
        $this->withoutExceptionHandling([AuthenticationException::class]); // Позволяет увидеть более развёрнутый вывод ошибки
        /* @var $user User */
        $user = $this->getUser();
        $fakePaymentCode = new FakePaymentCodeGenerator();
        $this->app->instance(PaymentCodeGenerator::class, $fakePaymentCode);
        $response = $this->actingAs($user)->json('post', 'payment', [
            'amount'      => 5000, // $50
            'currency'    => 'USD',
            'user_id'     => $user->id,
            'description' => 'New payment',
            'message'     => 'Some message',
        ]);
        $response->assertStatus(200);
        $this->assertEquals(1, Payment::count());
        tap(Payment::first(), function ($payment) use ($user) {
            $this->assertEquals($user->id, $payment->user->id);
            $this->assertEquals('test@mail.ru', $payment->user->email);
            $this->assertEquals('testing code', $payment->code);
        });
    }

    /**
     * Поле amount
     * обязательно для заполнения
     * нового счёта на оплату
     * @test
     * @return void
     */
    public function amount_field_is_required_to_creat_a_new_payment(): void
    {
        $this->withoutExceptionHandling([ValidationException::class]); // Позволяет увидеть более развёрнутый вывод ошибки
        /* @var $user User */
        $user = $this->getUser();
        $response = $this->actingAs($user)->json('post', 'payment', [
            //'amount'      => '',
            'code'        => 'test code',
            'currency'    => 'USD',
            'user_id'     => $user->id,
            'description' => 'New payment',
            'message'     => 'Some message',
        ]);
        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
        $response->assertJsonValidationErrors('amount');
    }

    /**
     * Поле amount
     * должно быть целым числом
     * @test
     * @return void
     */
    public function amount_field_is_integer_to_creat_a_new_payment(): void
    {
        $this->withoutExceptionHandling([ValidationException::class]); // Позволяет увидеть более развёрнутый вывод ошибки
        /* @var $user User */
        $user = $this->getUser();
        $response = $this->actingAs($user)->json('post', 'payment', [
            'amount'      => 'amount',
            'currency'    => 'USD',
            'user_id'     => $user->id,
            'description' => 'New payment',
            'message'     => 'Some message',
        ]);
        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
        $response->assertJsonValidationErrors('amount');
    }
}
