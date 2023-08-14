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

    $translations = array_keys(Locale::getTranslations(Cookie::get('lang') ?? 'en'));

    $lang = app()->getLocale();
    $localFilePath = resource_path("lang/{$lang}.json");
    $languageFiles = Language::distinct('language_code')->get();

//    if ($key !== "") {
//        // Check if the key exists in the translations
//        if (!in_array($key, $translations)) {
//            $check = Locale::where('key', $key)->exists();
//            if (!$check) {
//                Locale::create([
//                    'key' => $key,
//                    'text' => insertText($content),
//                ]);
//
//                foreach ($languageFiles as $name) {
//                    // Check if the file exists
//
//                    $translationFilePath = resource_path("lang/{$name}.json");
//                    $insertTranslation[$key] = $content;
//                    if (File::exists($translationFilePath)) {
//                        // Write the JSON data to the file
//                        $updatedJson = json_encode($insertTranslation, JSON_PRETTY_PRINT);
//                        file_put_contents($translationFilePath, $updatedJson);
//                    } else {
//                        dd($translationFilePath);
//                        file_put_contents($translationFilePath, json_decode('{asd}'));
//                    }
//                }
//            }
//        } else {
//            $translations = json_decode(file_get_contents($localFilePath), true);
//            if ($translations != null && array_key_exists($key, $translations)) {
//                return $translations[$key];
//            } else {
//                return $key;
//            }
//        }
//    } else {
//        return $key;
//    }

   return $key;

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
