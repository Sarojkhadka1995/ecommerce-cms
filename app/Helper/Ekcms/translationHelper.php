<?php

use App\Model\Language;
use Illuminate\Support\Facades\Cookie;

function translate($content, $data = [])
{
    $key = strtolower(trim(str_replace('.', '', $content)));

    if ($key !== '') {
        if (! in_array($key, 'en')) {
            $check = \App\Model\Locale::where('key', $key)->exists();
            if ($check) {
                return trans($key, $data);
            } else {
                if ($key !== '') {
                    \App\Model\Locale::create([
                        'key' => $key,
                        'text' => insertText($content),
                    ]);

                    return $content;
                }
            }
        } else {
            $trans = trans($key, $data);
            if ($trans == $key) {
                return $content;
            } else {
                return $trans;
            }
        }
    } else {
        return $key;
    }
}

function insertText($content)
{
    $languages = Language::orderBy('group', 'ASC')->pluck('language_code');
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
