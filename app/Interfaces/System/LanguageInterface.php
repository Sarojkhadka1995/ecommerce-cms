<?php

namespace App\Interfaces\System;

interface LanguageInterface
{
    public function getAllData($data, $selectedColumns = [], $pagination = true);

    public function create($request);

    public function update($id, $data);

    public function delete($request, $id);
    
    public function getBackendLanguages();
    
    public function getFrontendLanguages();
}
