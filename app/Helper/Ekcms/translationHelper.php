<?php

use App\Model\Language;
use Illuminate\Support\Facades\Cookie;
use App\Model\Locale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

function translate($content, $data = [])
{
    $key = strtolower(trim(str_replace(".", "", $content)));

    $translations = array_keys(Locale::getTranslations(Cookie::get('lang') ?? 'en'));

    $locale = app()->getLocale();
    $translationFilePath = resource_path("lang/{$locale}.json");

    if ($key !== "") {
        // Check if the key exists in the translations
        if (!in_array($key, $translations)) {
            $check = Locale::where('key', $key)->exists();
            if (!$check) {
                Locale::create([
                    'key' => $key,
                    'text' => insertText($content),
                ]);

//                $insertTranslation[$key] = $content;
//
//                // Encode the updated $translations array back to JSON format
//                $updatedJson = json_encode($insertTranslation, JSON_PRETTY_PRINT);
//
//                // Write the JSON data back to the file
//                file_put_contents($translationFilePath, $updatedJson);
            }
        } else {
            $translations = json_decode(file_get_contents($translationFilePath), true);
            if ($translations != null && array_key_exists($key, $translations)) {
                return $translations[$key];
            } else {
                return $key;
            }
        }
    } else {
        return $key;
    }


//    $keys = Locale::whereJsonContains('text', $locale)
//        ->pluck('key')
//        ->toArray();
//    dd($keys);

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
