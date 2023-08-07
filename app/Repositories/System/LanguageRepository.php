<?php

namespace App\Repositories\System;

use App\Exceptions\NotDeletableException;
use App\Interfaces\System\LanguageInterface;
use App\Model\Language;
use App\Repositories\Repository;

class LanguageRepository extends Repository implements LanguageInterface
{
    protected $countryRepository;

    public function __construct(Language $language, CountryRepository $countryRepository)
    {
        parent::__construct($language);
        $this->countryRepository = $countryRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        $query = $this->query();
        if (isset($data->keyword) && $data->keyword !== null) {
            $query->where(function ($q) use ($data) {
                $q->where('name', 'LIKE', '%' . $data->keyword . '%')->orWhere('language_code', 'LIKE', '%' . $data->keyword . '%');
            });
        }
            $query->where('group', 'backend');
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }
        if ($pagination) {
            return $query->orderBy('id', 'ASC')->paginate(PAGINATE);
        }

        return $query->orderBy('id', 'ASC')->get();
    }

    public function create($request)
    {
        $country = $this->countryRepository->itemByIdentifier($request->get('country_id'));
        $languages = json_decode($country->languages);
        $name = '';
        $language_code = '';
        foreach ($languages as $language) {
            if ($language->iso639_1 == $request->get('language_code')) {
                $name = $language->name;
                $language_code = $language->iso639_1;
                break;
            }
        }
        return $this->model->create([
            'name' => $name,
            'language_code' => $language_code,
            'group' => 'backend',
        ]);
    }

    public function delete($request, $id)
    {
        $language = $this->itemByIdentifier($id);
        if ($language->isDefault()) {
            throw new NotDeletableException();
        }
        return $language->delete();
    }

    public function getLanguages($group)
    {
        return $this->model->where('group', $group)->get();
    }
}
