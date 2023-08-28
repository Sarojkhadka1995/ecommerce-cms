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
                                LanguageRepository    $languageRepository)
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

    public function update($request, $id)
    {
        $currentLocale = app()->getLocale();
        $data = $this->repository->itemByIdentifier($id);
        $currentTextArray = $data->text;
        $currentText = $data->text[$currentLocale];
        $currentKey = $data->key;

        $jsonFileName = "{$currentLocale}.json";
        $jsonFilePath = resource_path('lang') . '/' . $jsonFileName;

        if (file_exists($jsonFilePath)) {
            $existingContent = file_get_contents($jsonFilePath);
            $existingTranslations = json_decode($existingContent, true);

            $filteredData[$currentKey] = $currentText;

            $mergedTranslations = array_merge($existingTranslations, $filteredData);

            $jsonContentString = json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($jsonFilePath, $jsonContentString);
        }

        if (in_array($request->locale, array_keys($currentTextArray))) {
            unset($currentTextArray[$request->locale]);
            $updatedTextArray = array_merge($currentTextArray, [$request->locale => $request->text]);
        } else {
            $updatedTextArray = array_merge($currentTextArray, [$request->locale => $request->text]);
        }
        $data->update(['text' => $updatedTextArray]);

        return $data;
    }

    public function delete($request, $id)
    {
        return $this->repository->delete($request, $id);
    }
}
