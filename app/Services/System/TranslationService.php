<?php

namespace App\Services\System;

use App\Model\Language;
use App\Repositories\System\LanguageRepository;
use App\Repositories\System\TranslationRepository;
use App\Services\Service;

class TranslationService extends Service
{
    protected $translationRepository;
    protected $languageRepository;

    public function __construct(TranslationRepository $translationRepository,
                                LanguageRepository $languageRepository)
    {
        $this->repository = $translationRepository;
        $this->languageRepository = $languageRepository;
    }

    public function indexPageData($request)
    {
        $languages = $this->languageRepository->getAllData($request, ['name', 'language_code'], false);

        return [
            'items' => $this->repository->getAllData($request),
            'locales' => $this->languageRepository->getKeyValuePair($languages),
        ];
    }
    public function inserttext($content, $group)
    {
        $languages = Language::orderBy('group', 'ASC')->pluck('language_code');
        $text = [];
        foreach ($languages as $language) {
            $text[$language] = $content;
        }
        return $text;
    }


    public function update($request, $id)
    {
        $data = $this->translationRepository->itemByIdentifier($id);
        $currentTextArray = $data->text;
        if (in_array($request->locale, array_keys($currentTextArray))) {
            unset($currentTextArray[$request->locale]);
            $updatedTextArray = array_merge($currentTextArray, [$request->locale => $request->text]);
        } else {
            $updatedTextArray = array_merge($currentTextArray, [$request->locale => $request->text]);
        }
        $this->translationRepository->update($request, $updatedTextArray);
        return $data;
    }

    public function delete($request, $id)
    {
        return $this->translationRepository->delete($request, $id);
    }
}
