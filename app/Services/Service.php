<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Repositories\Repository;
use Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Service
{
    /**
     * Stores the model used for service.
     * @var Eloquent object
     */
    protected $model;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    // Data for index page
    public function indexPageData($request)
    {
        return $this->repository->indexPageData($request);
    }

    // Data for create page
    public function createPageData($request)
    {
        return $this->repository->createPageData($request);
    }

    // Data for edit page
    public function editPageData($request, $id)
    {
        return $this->repository->editPageData($request, $id);
    }


    public function status()
    {
        return [
            ['value' => 1, 'label' => 'Active'],
            ['value' => 0, 'label' => 'Inactive'],
        ];
    }

    public function store($request)
    {
        return $this->repository->create($request);
    }
}
