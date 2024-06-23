<?php

namespace App\Http\Controllers;
// Specifies the namespace for the current class.
// Namespaces help organize code and avoid name conflicts.

use App\Services\TranslationService;
// Imports the TranslationService class from the App\Services namespace.
// This service will be used to handle translation operations.

use Laravel\Lumen\Routing\Controller as BaseController;
// Imports the BaseController class from the Laravel\Lumen\Routing namespace.
// This is the base class for Lumen controllers.

use Illuminate\Http\Request;
// Imports the Request class from the Illuminate\Http namespace.
// This class is used to handle HTTP request data.

class TranslationController extends BaseController
// Defines the TranslationController class, which extends the BaseController class.
// This class will handle HTTP requests related to translations.

{
    protected $translationService;
    // Declares a protected property named $translationService to store an instance of TranslationService.
    // Protected visibility allows access within this class and subclasses.

    public function __construct(TranslationService $translationService)
    // Constructor method for the TranslationController class.
    // It takes an instance of TranslationService as a parameter and assigns it to the $translationService property.

    {
        $this->translationService = $translationService;
        // Assigns the provided TranslationService instance to the $translationService property of the controller.
    }

    // Detect language
    public function detect(Request $request)
    // Method to handle HTTP requests for detecting the language of a given text.
    // It accepts a Request object and returns a JSON response.

    {
        // Validate the request
        $this->validate($request, [
        // Uses the validate method to ensure the request contains valid data.

            'text' => 'required|string'
            // The 'text' parameter is required and must be a string.
        ], [
            'text.required' => 'The "text" parameter is required.',
            // Custom error message if 'text' is missing.

            'text.string' => 'The "text" parameter must be a string.'
            // Custom error message if 'text' is not a string.
        ]);

        $text = $request->input('text');
        // Retrieves the 'text' parameter from the request.
        // This represents the text for which the language is to be detected.

        $result = $this->translationService->detectLanguage($text);
        // Calls the detectLanguage method of the TranslationService instance with the text.
        // Stores the resulting data in the $result variable.

        return response()->json($result);
        // Returns the detection result in a JSON response.
    }

    // Get available languages
    public function getLanguages()
    // Method to handle HTTP requests for retrieving available languages for translation.
    // It returns a JSON response.

    {
        $languages = $this->translationService->getLanguages();
        // Calls the getLanguages method of the TranslationService instance.
        // Stores the list of available languages in the $languages variable.

        return response()->json($languages);
        // Returns the list of available languages in a JSON response.
    }

    // Translate text
    public function translate(Request $request)
    // Method to handle HTTP requests for translating a given text.
    // It accepts a Request object and returns a JSON response.

    {
        // Validate the request
        $this->validate($request, [
        // Uses the validate method to ensure the request contains valid data.

            'text' => 'required|string',
            // The 'text' parameter is required and must be a string.

            'source' => 'required|string',
            // The 'source' parameter is required and must be a string.
            // Represents the source language code.

            'target' => 'required|string',
            // The 'target' parameter is required and must be a string.
            // Represents the target language code.

            'format' => 'string|in:text,html'
            // The 'format' parameter is optional but must be a string if provided.
            // Allowed values are 'text' or 'html'.
        ], [
            'text.required' => 'The "text" parameter is required.',
            // Custom error message if 'text' is missing.

            'text.string' => 'The "text" parameter must be a string.',
            // Custom error message if 'text' is not a string.

            'source.required' => 'The "source" parameter is required.',
            // Custom error message if 'source' is missing.

            'source.string' => 'The "source" parameter must be a string.',
            // Custom error message if 'source' is not a string.

            'target.required' => 'The "target" parameter is required.',
            // Custom error message if 'target' is missing.

            'target.string' => 'The "target" parameter must be a string.',
            // Custom error message if 'target' is not a string.

            'format.string' => 'The "format" parameter must be a string if provided.',
            // Custom error message if 'format' is not a string (if provided).

            'format.in' => 'The "format" parameter must be either "text" or "html".'
            // Custom error message if 'format' is not 'text' or 'html'.
        ]);

        $text = $request->input('text');
        // Retrieves the 'text' parameter from the request.
        // This represents the text to be translated.

        $source = $request->input('source');
        // Retrieves the 'source' parameter from the request.
        // Represents the source language code.

        $target = $request->input('target');
        // Retrieves the 'target' parameter from the request.
        // Represents the target language code.

        $format = $request->input('format', 'text');
        // Retrieves the 'format' parameter from the request, with a default value of 'text'.
        // Represents the format of the text ('text' or 'html').

        $result = $this->translationService->translate($text, $source, $target, $format);
        // Calls the translate method of the TranslationService instance with the text, source, target, and format.
        // Stores the translation result in the $result variable.

        return response()->json($result);
        // Returns the translation result in a JSON response.
    }
}
