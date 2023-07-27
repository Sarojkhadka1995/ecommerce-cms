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

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }



    // Data for index page
    public function indexPageData($request)
    {
        return [
            'items' => $this->repository->getAllData($request),
        ];
    }

    // Data for create page
    public function createPageData($request)
    {
        return [
            'status' => $this->status(),
        ];
    }

    // Data for edit page
    public function editPageData($request, $id)
    {
        return [
            'item' => $this->repository->itemByIdentifier($id),
            'status' => $this->status(),
        ];
    }


    public function status()
    {
        return [
            ['value' => 1, 'label' => 'Active'],
            ['value' => 0, 'label' => 'Inactive'],
        ];
    }
}
