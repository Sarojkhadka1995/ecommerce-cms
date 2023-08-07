<?php

namespace App\Services\System;

use App\Repositories\System\CountryRepository;
use App\Repositories\System\LanguageRepository;
use App\Services\Service;

class LanguageService extends Service
{
    protected $languageRepository;
    protected $countryRepository;
    protected $countryService;

    public function __construct(LanguageRepository $languageRepository,
                                CountryService     $countryService,
                                CountryRepository  $countryRepository)
    {
        $this->repository = $languageRepository;
        $this->countryRepository = $countryRepository;
        $this->countryService = $countryService;
    }

    public function createPageData($request)
    {
        $countries = $this->countryRepository->getAllData($request, ['id', 'name', 'languages', 'flag'], false);
        return [
            'countriesOptions' => $this->countryService->extractKeyValuePair($countries),
        ];
    }
    public function getBackendLanguages($group)
    {
        return $this->languageRepository->getLanguages('backend');
    }
}
