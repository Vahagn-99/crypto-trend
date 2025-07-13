<?php

declare(strict_types=1);


namespace App\Base\Auth;

use App\Base\Auth\Dto\AuthResult;
use App\Base\Auth\Dto\VerifyData;
use App\Base\Auth\Events\Created as OTPCreatedEvent;
use App\Base\Auth\Exceptions\OPTException;
use App\Base\Auth\Generators\ICodeGenerator;
use App\Models\PhoneOtp as PhoneOtpModel;
use App\Base\Auth\Repository\IOTPRepository;

class Manager
{
    /**
     * Manager constructor.
     *
     * @param \App\Base\Auth\Repository\IOTPRepository $repository
     * @param \App\Base\Auth\Generators\ICodeGenerator $code_generator
     */
    public function __construct(
        private readonly IOTPRepository $repository,
        private readonly ICodeGenerator $code_generator,
    ) {
        //
    }

    /**
     * Отправка кода авторизации
     *
     * @param string $phone
     * @return void
     */
    public function sendCode(string $phone) : void
    {
        $code = $this->code_generator->generate();

        $item = $this->repository->getByPhone($phone);

        if (empty($item)) {
            $item = new PhoneOtpModel();

            $item->phone = $phone;
        }

        $item->code = $code;
        $item->expires_at = now()->addMinutes(config('auth.otp.expiry_in_minutes', 5));

        $item->save();

        OTPCreatedEvent::dispatch($item);
    }

    /**
     * @throws \App\Base\Auth\Exceptions\OPTException
     */
    public function verifyCode(VerifyData $data)
    {
        $otp = $this->repository->getByPhone($data->phone, $data->code);

        if (! $otp) {
            throw OPTException::not_valid($data->code);
        }

        $user = $otp->user;

        $otp->delete();

        $token = $user->createToken('opt_auth_token')->plainTextToken;

        return AuthResult::from([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $otp->expires_at,
        ]);
    }
}
