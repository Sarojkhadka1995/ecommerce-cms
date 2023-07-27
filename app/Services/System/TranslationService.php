<?php

namespace App\Services\System;

use App\Model\Language;
use App\Repositories\System\TranslationRepository;
use App\Services\Service;

class TranslationService extends Service
{
    public function __construct(TranslationRepository $translationRepository, LanguageService $languageService)
    {
        $this->translationRepository = $translationRepository;
        $this->languageService = $languageService;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->translationRepository->getAllData($data);
    }

    public function indexPageData($request)
    {
        $languages = $this->languageService->getAllData($request->only('group'), ['name', 'language_code'], false);

        return [
            'items' => $this->getAllData($request),
            'groups' => $this->languageService->defaultLanguageGroups(),
            'locales' => $this->languageService->getKeyValuePair($languages),
        ];
    }

    public function store($request)
    {
        return $this->translationRepository->create($request);
    }
    public function inserttext($content, $group)
    {
        $languages = Language::where('group', $group)->orderBy('group', 'ASC')->pluck('language_code');
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
