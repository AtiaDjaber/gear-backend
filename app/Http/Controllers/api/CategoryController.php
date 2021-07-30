<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public  function index()
    {
        $cateroies = Category::get();
        return response()->json($cateroies);;
    }
    public  function delete($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            $category->delete();
            return response()->json("deleted success");
        }
        // Category::where('id', $id)->delete();
        return response()->json("deleted failed");
    }

    public function add(Request $requset)
    {
        // $category = new Category();
        // $category->id = $requset->id;
        // $category->name = $requset->name;
        $category = Category::create($requset->all());
        return response()->json($category, 201);
    }
}
