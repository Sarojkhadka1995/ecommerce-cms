<?php

namespace App\Services\System;

use App\Exceptions\CustomGenericException;
use App\Repositories\System\PageRepository;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PageService
{
    use ImageTrait;
    public $dir = '/uploads/profilePic';

    public function __construct(PageRepository $pageRepository)
    {

        $this->pageRepository = $pageRepository;
    }

    public function getAllData($data, $selectedColumns = [], $pagination = true)
    {
        return $this->pageRepository->getAllData($data);
    }

    public function indexPageData($request)
    {
        return [
            'items' => $this->getAllData($request),
        ];
    }

    public function createPageData($request)
    {
        return [
            'status' => $this->status(),

        ];
    }

    public function store($request)
    {
        try {
            $data = $request->except('_token');
            $data['slug'] = Str::slug($request->get('slug'));
            if (isset($data['image'])) {
                $data['image'] = $this->storeImage($data['image']);
            }
            return $this->pageRepository->createPage($data);
        } catch (\Exception $e) {
            throw new CustomGenericException($e->getMessage());
        }
    }

    public function editPageData($request, $id)
    {
        $page = $this->pageRepository->itemByIdentifier($id);
        return [
            'item' => $page,
            'status' => $this->status(),
        ];
    }

    public function update($request, $id)
    {
        $page = $this->pageRepository->itemByIdentifier($id);
        $data = $request->except('_token');
        $data['slug'] = Str::slug($request->get('slug'));

        if (isset($request['image'])) {
            $this->deleteImage($page->image);
            $data['image'] = $this->storeImage($data['image']);
        }

         $this->pageRepository->updatePage($page, $data);
         return $page;
    }

    public function delete($request, $id)
    {
        return $this->pageRepository->deletePage($id);
    }

    public function changeStatus($request)
    {
        return $this->pageRepository->changeStatus($request);
    }

    public function storeImage($image)
    {
        $file = $image;
        !Storage::disk('public')->exists('uploads')
            ? \File::makeDirectory(Storage::disk('public')->makeDirectory('uploads/page'), $mode = 0755, true, true)
            : '';
        $store = Storage::disk('public')->put('uploads/page', $file);
        return basename($store);
    }

    public function deleteImage($image)
    {
        Storage::disk('public')->exists('uploads/page/' . $image)
            ? Storage::disk('public')->delete('uploads/page/' . $image)
            : '';
    }

    public function status()
    {
        return [
            ['value' => 1, 'label' => 'Active'],
            ['value' => 0, 'label' => 'Inactive'],
        ];
    }
}
