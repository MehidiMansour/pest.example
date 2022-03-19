<?php

use App\Models\User;
beforeEach(function () {
    $this->payload = [
        'name' => 'mansour mehidi',
        'email' => 'test@email.com',
        'password' => 'password',
    ];
});
it('has users page', function () {
    $this->get('/')->assertStatus(200);
});

it('can register a user with valid details', function () {
    $this->postJson('api/v1/auth/register', $this->payload)
    ->assertStatus(200);
});

it('can not create user with an existing email', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $payload = array_merge($this->payload, ['email' => $user->email]);
    $this->postJson('api/v1/auth/register', $payload)
    ->assertStatus(422)->assertJsonValidationErrors(['email']);
});

it('should not create a verified user after registration', function () {
    $this->postJson('api/v1/auth/register', $this->payload)
    ->assertStatus(200);
    $user = User::where('email', $this->payload['email'])->first();

    $this->assertFalse($user->hasVerifiedEmail());
});
it('should login user after registration', function () {
    $this->postJson('api/v1/auth/register', $this->payload)
    ->assertStatus(200)
    ->assertCookie('access_token');
});
