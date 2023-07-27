<?php

namespace App\Services\System;


use App\Repositories\System\EmailRepository;
use App\Services\Service;

class EmailTemplateService extends Service
{
    public function __construct(EmailRepository $emailRepository)
    {
        $this->emailRepository = $emailRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->emailRepository->getAllData($data);
    }

    public function indexPageData($request)
    {
        return [
            'items' => $this->getAllData($request)
        ];
    }

    public function store($request)
    {     
        return $this->emailRepository->create($request);
    }

    public function editPageData($request, $id)
    {
        $email = $this->emailRepository->itemByIdentifier($id);
        return [
            'item' => $email,
        ];
    }

    public function update($request, $id)
    {       
        return $this->emailRepository->update($request, $id);
    }
    public function delete($request, $id)
    {       
        return $this->emailRepository->delete($request,$id);
    }
}
