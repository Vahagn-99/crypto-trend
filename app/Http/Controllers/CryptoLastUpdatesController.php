<?php

namespace App\Http\Controllers;

use App\Base\Coin\Dto\GetPriceLastUpdatesFilter;
use App\Base\Coin\Manager as CoinManager;
use App\Exceptions\RateLimitedException;
use App\Http\Resources\Coin as CoinResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CryptoLastUpdatesController extends Controller
{
    /**
     * CryptoLastUpdatesController constructor.
     *
     * @param \App\Base\Coin\Manager $manager
     */
    public function __construct(
        private readonly CoinManager $manager
    ) {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/prices",
     *     summary="Получить последние обновления цен по монетам",
     *     description="Возвращает список монет с последними снимками цены начиная с указанной даты",
     *     operationId="getCryptoLastUpdates",
     *     tags={"Crypto"},
     *     security={{"bearer": {}}},
     *
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         description="Дата начала выборки (формат: Y-m-d)",
     *         @OA\Schema(type="string", format="date", example="2025-07-01")
     *     ),
     *     @OA\Parameter(
     *          name="to",
     *          in="query",
     *          required=false,
     *          description="Дата конца выборки (формат: Y-m-d)",
     *          @OA\Schema(type="string", format="date", example="2025-07-01")
     *      ),
     *     @OA\Parameter(
     *         name="vs_currency",
     *         in="query",
     *         required=false,
     *         description="Валюта отображения цен",
     *         @OA\Schema(type="string", enum={"usd"}, default="usd")
     *     ),
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         required=false,
     *         description="Провайдер данных",
     *         @OA\Schema(type="string", enum={"coingecko"}, default="coingecko")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Количество элементов на странице",
     *         @OA\Schema(type="integer", default=50)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Номер страницы",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ со списком монет",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Coin")),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=50),
     *                 @OA\Property(property="total", type="integer", example=200)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ошибка валидации"),
     *             @OA\Property(property="errors", type="object")
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
     *         description="Критическая ошибка сервера",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Произошла ошибка сервера, дождитесь пока менеджер свяжется с вами!")
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $rate_limit_key = "get:last:updates:".auth()->id();

        try {
            if (RateLimiter::tooManyAttempts($rate_limit_key, 5)) {
                throw new RateLimitedException("Слишком много запросов, попробуйте позже", ResponseAlias::HTTP_TOO_MANY_REQUESTS);
            }

            RateLimiter::hit($rate_limit_key, 1);

            $coins = $this->manager->lastUpdates(GetPriceLastUpdatesFilter::validateAndCreate($request->all()));

            $resource = CoinResource::collection($coins);

            return response()->json([
                'data' => $resource,
                'meta' => [
                    'current_page' => $coins->currentPage(),
                    'last_page' => $coins->lastPage(),
                    'per_page' => $coins->perPage(),
                    'total' => $coins->total(),
                ],
            ]);
        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $e->errors()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        catch (RateLimitedException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
        catch (Exception $e) {
            alt_log()->file("system_level_error")->error($e);

            return response()->json([
                'message' => 'Произошла ошибка сервера, дождитесь пока менеджер свяжется с вами!'
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
