<?php

namespace Tests\Feature;

use App\Base\Auth\Dto\VerifyData;
use App\Models\{
    User as UserModel,
    PhoneOtp as PhoneOtpModel,
};
use App\Base\Auth\Dto\AuthResult;
use App\Base\Auth\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OtpAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create([
            'phone' => '79991234567'
        ]);

        $this->otp = PhoneOtpModel::factory()->create([
            'phone' => $this->user->phone
        ]);
    }

    public function test_send_otp_code_successfully()
    {
        $mock = Mockery::mock(Manager::class);

        $mock->shouldReceive('sendCode')
            ->once()
            ->with('79991234567');

        $this->app->instance(Manager::class, $mock);

        $response = $this->postJson(route('api.v1.auth.otp.send'), [
            'phone' => $this->user->phone
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Код отправлен']);
    }

    public function test_verify_otp_code_successfully()
    {
        $fake_data = AuthResult::from([
            'access_token' => 'eyJhbGciOi...',
            'token_type' => 'Bearer',
            'expires_in' => now()->addMinutes(config('auth.otp.expiry_in_minutes', 5)),
        ]);

        $mock = Mockery::mock(Manager::class);

        $mock->shouldReceive('verifyCode')
            ->once()
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof \App\Base\Auth\Dto\VerifyData
                    && $arg->phone === $this->user->phone
                    && $arg->code === (string)$this->otp->code;
            }))
            ->andReturn($fake_data);

        $this->app->instance(Manager::class, $mock);

        $response = $this->postJson(route('api.v1.auth.otp.verify'), [
            'phone' => $this->user->phone,
            'code' => (string)$this->otp->code
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Успешная верификация',
                'access_token' => 'eyJhbGciOi...',
                'token_type' => 'Bearer',
            ]);
    }}
