<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentStoreRequest;
use App\Models\Payment;
use App\Services\Payment\PaymentCodeGenerator\PaymentCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * @return View
     */
    public function create(): View
    {
        return view('payment.create');
    }

    /**
     * @param PaymentStoreRequest $request
     * @return void
     */
    public function store(PaymentStoreRequest $request): void
    {
        $data = [
            'amount'      => $request->amount,
            'code'        => app(PaymentCodeGenerator::class)->generate(),
            'currency'    => $request->currency,
            'user_id'     => $request->user_id,
            'description' => $request->description,
            'message'     => $request->message,
        ];
        $request->user()->payments()->create($data);
    }

}
