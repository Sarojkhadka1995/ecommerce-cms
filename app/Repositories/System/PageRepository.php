<?php
namespace App\Repositories\System;
use App\Interfaces\System\PageRepositoryInterface;
use App\Model\Page;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Storage;

class PageRepository extends Repository implements PageRepositoryInterface
{
    protected $page;

    public function __construct(Page $page)
    {
        parent::__construct($page);
        $this->page = $page;
    }


    public function getAllData($data = null, $selectedColumns = [], $pagination = true)
    {
        $query = $this->query();

        if (isset($data->keyword) && $data->keyword !== null) {
            $query->where('title', 'LIKE', '%' . $data->keyword . '%');
        }
        if (count($selectedColumns) > 0) {
            $query->select($selectedColumns);
        }
        if ($pagination) {
            return $query->orderBy('id', 'DESC')->paginate(paginate());
        }

        return $query->orderBy('id', 'DESC')->with('role')->get();
    }


    public function createPage($request)
    {
        return $this->model->create($request);
    }

    public function updatePage($page, $data)
    {
        return  $page->update($data);
    }

    public function deletePage($id)
    {
        $item = $this->itemByIdentifier($id);
        if (isset($item->image)) {
            Storage::disk('public')->exists('uploads/page/' . $item->image)
                ? Storage::disk('public')->delete('uploads/page/' . $item->image)
                : '';
        }
        return $item->delete();
    }

    public function changeStatus($request)
    {
        $user = $this->itemByIdentifier($request->id);
        $user->status = !$user->status;
        $user->save();
    }
}
