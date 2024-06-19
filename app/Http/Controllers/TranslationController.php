<?php

namespace App\Http\Controllers;

use App\Services\TranslationService;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class TranslationController extends BaseController
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    // Detect language
    public function detect(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'text' => 'required|string'
        ], [
            'text.required' => 'The "text" parameter is required.',
            'text.string' => 'The "text" parameter must be a string.'
        ]);

        $text = $request->input('text');
        $result = $this->translationService->detectLanguage($text);

        return response()->json($result);
    }

    // Get available languages
    public function getLanguages()
    {
        $languages = $this->translationService->getLanguages();

        return response()->json($languages);
    }

    // Translate text
    public function translate(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'text' => 'required|string',
            'source' => 'required|string',
            'target' => 'required|string',
            'format' => 'string|in:text,html'
        ], [
            'text.required' => 'The "text" parameter is required.',
            'text.string' => 'The "text" parameter must be a string.',
            'source.required' => 'The "source" parameter is required.',
            'source.string' => 'The "source" parameter must be a string.',
            'target.required' => 'The "target" parameter is required.',
            'target.string' => 'The "target" parameter must be a string.',
            'format.string' => 'The "format" parameter must be a string if provided.',
            'format.in' => 'The "format" parameter must be either "text" or "html".'
        ]);

        $text = $request->input('text');
        $source = $request->input('source');
        $target = $request->input('target');
        $format = $request->input('format', 'text');

        $result = $this->translationService->translate($text, $source, $target, $format);

        return response()->json($result);
    }
}
