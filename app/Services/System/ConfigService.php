<?php

namespace App\Services\System;

use App\Exceptions\NotDeletableException;
use App\Repositories\System\ConfigRepository;
use App\Services\Service;
use App\Traits\ImageTrait;

class ConfigService extends Service
{
    use ImageTrait;

    public $dir = '/uploads/config';

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->configRepository->getAllData($data);
    }

    //config type key value pair
    public function configTypeOptions()
    {
        $mapped = [];
        foreach (configTypes() as $config) {
            $mapped[$config] = ucfirst($config);
        }

        return $mapped;
    }

    public function indexPageData($request)
    {
        return [
            'items' => $this->getAllData($request),
            'types' => $this->configTypeOptions()
        ];
    }

    public function store($request)
    {   
        $data = $request->except('_token');        
        if (strtolower($request->type) == 'file') {
            $data['value'] = $this->uploadImage($this->dir, 'value');
        }
        return $this->configRepository->create($data);
    }

    public function update($request, $id)
    {
        $data = $request->except('_token');
        $config = $this->configRepository->itemByIdentifier($id);
        if (strtolower($config->type) == 'file') {
            $this->removeImage($this->dir, $config->value);
            $data['value'] = $this->uploadImage($this->dir, 'value');
        }
        $this->configRepository->update($config, $data);
        setConfigCookie();

        return $config =  $this->configRepository->itemByIdentifier($id);
    }

    public function delete($request, $id)
    {
        $config =  $this->configRepository->itemByIdentifier($id);
        if (in_array($id, [1, 2, 3])) {
            throw new NotDeletableException;
        }
        if (strtolower($config->type) == 'file') {
            $this->removeImage($this->dir, $config->value);
        }
        return $this->configRepository->delete($config, $id);
    }
}
