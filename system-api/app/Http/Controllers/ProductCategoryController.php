<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    /**
     * The class name of the model.
     *
     * @var string
     */
    protected $model = ProductCategory::class;

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
