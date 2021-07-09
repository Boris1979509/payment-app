<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait GetUser
{
    /**
     * @return Collection|Model
     */
    private function getUser(): Model
    {
        return User::factory()->create([
            'name'     => 'Boris',
            'email'    => 'test@mail.ru',
            'password' => 'secret',
        ]);
    }
}
