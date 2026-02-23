<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API dokumentacija za RunAPP aplikaciju",
    title: "RunAPP API",
    contact: new OA\Contact(
        name: "API Support",
        url: "https://example.com/support",
        email: "support@example.com"
    )
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local development server"
)]
abstract class Controller
{
    //
}
