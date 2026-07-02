<?php

namespace App\Http\Controllers;

use App\Services\JsTranslationService;
use Illuminate\Http\Response;

class TranslationAssetController extends Controller
{
    public function __invoke(JsTranslationService $translations): Response
    {
        return response($translations->getJavaScript(), 200, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
