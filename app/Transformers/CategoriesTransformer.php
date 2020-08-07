<?php
namespace App\Transformers;

use App\Model\Category;
use League\Fractal\TransformerAbstract;

class CategoriesTransformer extends TransformerAbstract
{
  public function transform(Category $category)
  {
    return [
      'id' => $category->id,
      'category_name' => $category->name,
      'category_attribute' => $category->attributes,
    ];
  }
}