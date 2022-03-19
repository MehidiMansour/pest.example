<?php

use App\Models\User;

it('can not login with wrong credentials', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $this->postJson('api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong_password',
    ])
    ->assertStatus(422)
    ->assertJsonValidationErrors(['email']);
});