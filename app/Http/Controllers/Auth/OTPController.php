<?php

namespace App\Http\Controllers\Auth;

use App\Base\Auth\Manager as AuthManager;
use App\Base\Auth\Dto\VerifyData;
use App\Base\Auth\Exceptions\OPTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Exceptions\RateLimitedException;
use Exception;

class OTPController extends Controller
{
    /**
     * OTPController constructor.
     *
     * @param \App\Base\Auth\Manager $manager
     */
    public function __construct(
        private readonly AuthManager $manager
    ) {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/otp",
     *     summary="Отправка OTP-кода на телефон",
     *     description="Отправляет одноразовый код подтверждения (OTP) на указанный номер телефона. Для локального окружения возвращает токен из переменных окружения: `AUTH_OTP_NONE_PRODUCTION_ENV_CODE` по умолчанию `123456`",
     *     operationId="sendOtpCode",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(property="phone", type="string", example="79991234567", description="Телефон в формате от 10 до 15 цифр. Для локального окружения используется по умолчанию `79991234567`")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Код успешно отправлен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Код отправлен")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации телефона",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Неверный формат телефона")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Превышен лимит запросов",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Слишком много запросов, попробуйте позже")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Произошла внутренняя ошибка сервера")
     *         )
     *     )
     * )
     */
    public function sendCode(Request $request)
    {
        $rate_limit_key = "send:otp:".$request->ip();

        try {
            if (RateLimiter::tooManyAttempts($rate_limit_key, 3)) {
                throw new RateLimitedException("Слишком много запросов, попробуйте позже", ResponseAlias::HTTP_TOO_MANY_REQUESTS);
            }

            RateLimiter::hit($rate_limit_key, 10);

            $request->validate([
                'phone' => ['required','exists:users,phone']
            ]);

            $this->manager->sendCode($request->phone);

            return response()->json(['message' => 'Код отправлен']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Неверный формат телефона'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        } catch (RateLimitedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }  catch (Exception $e) {
            alt_log()->file("system_level_error")->error($e);

            return response()->json([
                'message' => 'Произошла ошибка сервера, дождитесь пока менеджер свяжется с вами!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/otp/verify",
     *     summary="Подтверждение OTP-кода",
     *     description="Проверяет OTP-код и возвращает токен авторизации при успехе.",
     *     operationId="verifyOtpCode",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone", "code"},
     *             @OA\Property(property="phone", type="string", example="79991234567", description="Телефон"),
     *             @OA\Property(property="code", type="string", example="123456", description="OTP-код")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешная верификация",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Успешная верификация"),
     *             @OA\Property(property="access_token", type="string", example="eyJhbGciOi..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации или неверный код",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Неверный код")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Произошла ошибка сервера, дождитесь пока менеджер свяжется с вами!")
     *         )
     *     )
     * )
     */
    public function verifyCode(Request $request)
    {
        try {
            $result = $this->manager->verifyCode(VerifyData::validateAndCreate($request->all()));

            return response()->json([
                'message' => 'Успешная верификация',
                'access_token' => $result->access_token,
                'token_type' => $result->type,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->validator->errors()->first()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        } catch (OPTException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        } catch (Exception $e) {
            alt_log()->file("system_level_error")->error($e);

            return response()->json([
                'message' => 'Произошла ошибка сервера, дождитесь пока менеджер свяжется с вами!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
