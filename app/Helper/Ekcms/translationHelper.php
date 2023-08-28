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

    $directory = resource_path('lang');
    if (!is_dir($directory)) {
        \File::makeDirectory($directory, $mode = 0755, true);
    }

    createOrReplaceTranslationContent($key, $content, $directory);

    $locale = app()->getLocale();
    $jsonFileName = "{$locale}.json";
    $jsonFilePath = "{$directory}/{$jsonFileName}";
    $translationsJSON = file_get_contents($jsonFilePath);

    // Convert JSON to an associative array
    $translations = json_decode($translationsJSON, true);

    if ($translations === null) {
        return $key;
    }
    return $translations[$key];
}

function createOrReplaceTranslationContent($keyword, $content, $directory)
{
    $langShortCodes = Language::pluck('language_code')->toArray();

    foreach ($langShortCodes as $lang) {

        $jsonFileName = "{$lang}.json";
        $jsonFilePath = "{$directory}/{$jsonFileName}";

        if (file_exists($jsonFilePath)) {
            $existingContent = file_get_contents($jsonFilePath);
            $existingTranslations = json_decode($existingContent, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Check if the translation key already exists
                if (!isset($existingTranslations[$keyword])) {
                    $newTranslations = [
                        $keyword => $content,
                    ];

                    $mergedTranslations = array_merge($existingTranslations, $newTranslations);

                    $jsonContentString = json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    file_put_contents($jsonFilePath, $jsonContentString);
                }
            }
        } else {
            // Create a new JSON file with initial translations
            $initialTranslations = [
                $keyword => $content,
            ];

            $jsonContentString = json_encode($initialTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($jsonFilePath, $jsonContentString);
        }
    }
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


