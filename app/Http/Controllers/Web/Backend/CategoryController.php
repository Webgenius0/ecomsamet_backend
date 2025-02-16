<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Category;

class CategoryController extends Controller
{
  public function index()
  {

    $categorys = Category::all();
    return view('backend.layouts.category.index', compact('categorys'));
  }

  public function create()
  {
    return view('backend.layouts.category.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'description' => 'nullable',
    ]);

    $categories = Category::create([
      'name' => $request->name,
      'description' => $request->description,
    ]);

    return redirect()->route('category.index');
}

public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('backend.layouts.category.edit', compact('category'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'description' => 'nullable',
    ]);

    $category = Category::findOrFail($id);
    $category->name = $request->name;
    $category->description = $request->description;
    $category->save();

    return redirect()->route('category.index');
}

}
