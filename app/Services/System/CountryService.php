<?php

namespace App\Services\System;

use App\Repositories\System\CountryRepository;
use App\Services\Service;

class CountryService extends Service
{
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function extractKeyValuePair($countries, $key = 'id', $value = 'name')
    {
        $countriesPair = [];
        foreach ($countries as $country) {
            $countriesPair[$country[$key]] = $country[$value];
        }

        return $countriesPair;
    }
}
