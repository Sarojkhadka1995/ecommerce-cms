<?php

use App\Model\Language;
use Illuminate\Support\Facades\Cookie;
use App\Model\Locale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;

function translate($content, $data = [])
{
    $key = strtolower(trim(str_replace(".", "", $content)));

    //$translations = array_keys(Locale::getTranslations(Cookie::get('lang') ?? 'en'));

    $lang = app()->getLocale();

    $directory = resource_path('lang');
    if (!is_dir($directory)) {
        \File::makeDirectory($directory, $mode = 0755, true);
    }

    $jsonFileName = "{$lang}.json";
    $jsonFilePath = "{$directory}/{$jsonFileName}";

    if (file_exists($jsonFilePath)) {
        $existingContent = file_get_contents($jsonFilePath);
        $existingTranslations = json_decode($existingContent, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Check if the translation key already exists
            if (!isset($existingTranslations[$key])) {
                $newTranslations = [
                    $key => $content,
                ];

                $mergedTranslations = array_merge($existingTranslations, $newTranslations);

                $jsonContentString = json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($jsonFilePath, $jsonContentString);
            }
        } else {
            // Handle JSON decoding error if necessary
        }
    } else {
        // Create a new JSON file with initial translations
        $initialTranslations = [
            $key => $content,
        ];

        $jsonContentString = json_encode($initialTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($jsonFilePath, $jsonContentString);
    }

    $translationsJSON = file_get_contents($jsonFilePath);

// Convert JSON to an associative array
    $translations = json_decode($translationsJSON, true);

    if ($translations === null) {
        return $key;
    }

    return $translations[$key];
}

function insertText($content)
{
    $languages = Language::pluck('language_code');
    $text = [];
    foreach ($languages as $language) {
        $text[$language] = $content;
    }
    return $text;
}

function translateValidationErrorsOfApi($content, $data = [])
{
    return translate($content, $data);
}

//frontend tranalation function

function frontTrans($details, $data = [])
{
    return translate($details, $data);
}
