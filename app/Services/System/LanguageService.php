<?php

namespace App\Services\System;

use App\Repositories\System\CountryRepository;
use App\Repositories\System\LanguageRepository;
use App\Services\Service;

class LanguageService extends Service
{
    public function __construct(LanguageRepository $languageRepository, CountryService $countryService, CountryRepository $countryRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->countryRepository = $countryRepository;
        $this->countryService = $countryService;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->languageRepository->getAllData($data);
    }

    public function indexPageData($request)
    {
        return [
            'items' => $this->getAllData($request),
            'groups' => $this->defaultLanguageGroups(),
        ];
    }

    public function createPageData($request)
    {
        $countries = $this->countryRepository->getAllData($request, ['id', 'name', 'languages', 'flag'], false);
        return [
            'countriesOptions' => $this->countryService->extractKeyValuePair($countries),
            'groups' => $this->defaultLanguageGroups(),
        ];
    }

    public function store($request)
    {
        return $this->languageRepository->create($request);
    }

    public function delete($request, $id)
    {
        return $this->languageRepository->delete($request, $id);
    }

    public function defaultLanguageGroups()
    {
        return
            [
                'backend' => 'Backend',
                'frontend' => 'Frontend',
            ];
    }

    public function getKeyValuePair($languages, $key = 'language_code', $value = 'name')
    {
        $options = [];
        foreach ($languages as $language) {
            $options[$language[$key]] = $language[$value];
        }

        return $options;
    }

    public function getBackendLanguages()
    {
        return $this->languageRepository->getBackendLanguages();
    }

    public function getFrontendLanguages()
    {
        return $this->languageRepository->getFrontendLanguages();
    }
}
