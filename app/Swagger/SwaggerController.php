<?php

namespace DaaluPay\Swagger;

use DaaluPay\Http\Controllers\BaseController;

class SwaggerController extends BaseController
{
    /**
     * @OA\Info(
     *     title="Daalupay API",
     *     version="1.0.0",
     *     description="API documentation for Daalupay application.",
     *
     *     @OA\Contact(
     *         email="support@daalupay.internal"
     *     ),
     *
     *     @OA\License(
     *         name="Apache 2.0",
     *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *     )
     * )
     *
     * @OA\Server(
     *     url=L5_SWAGGER_CONST_HOST,
     *     description="API Server"
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )
     */
    public function __construct()
    {
        parent::__construct();
    }
}
