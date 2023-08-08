<?php

namespace App\Services\System;

use App\Exceptions\CustomGenericException;
use App\Repositories\System\CountryRepository;
use App\Repositories\System\LanguageRepository;
use App\Services\Service;
use Illuminate\Support\Facades\DB;
use Spatie\TranslationLoader\LanguageLine;
use File;

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

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $trans = [];
            $language = $this->repository->create($request);
            $filename = $language->language_code . '.json';
            $languageLineData = LanguageLine::pluck('key')->toArray();
            foreach ($languageLineData as $datum) {
                $trans[$datum] = $datum;
            }
            $jsonLanguageData = json_encode($trans);
            $filePath = resource_path('lang/' . $filename);
            File::put($filePath, $jsonLanguageData);
            DB::commit();
            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomGenericException($e->getMessage());
        }
    }

    public function delete($request, $id)
    {
        $item = $this->repository->itemByIdentifier($id);
        $filename = $item->language_code . '.json';
        $filePath = resource_path('lang/' . $filename);

        if (File::exists($filePath)) {
            File::delete($filePath);
            // File deleted successfully
        }
        return $this->repository->delete($request, $id);
    }

    public function getBackendLanguages($group)
    {
        return $this->languageRepository->getLanguages('backend');
    }
}
