<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Category;

class CategoryControllerAPI extends Controller
{
    public function categoriesNames()
    {
        return Category::get('name');
    }
}
