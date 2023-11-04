<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all()->sortDesc();
        return view('books.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): mixed
    {
        $rules =[
            'title' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        
        $cat = new Category;
        $cat->title = $request->title;
        $cat->save();
        //Category::create($request->all());
        $cat = Category::all()->sortDesc();
        return response()->json(['success' => 'ok', 'categories' => $cat]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $cat = Category::find($request->hidden_idcat);
        $rules =[
            'title' => 'required|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $cat->title = $request->title;
        $cat->update();
        $cat = Category::all()->sortDesc();
        return response()->json(['success' => 'ok', 'categories' => $cat]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $category)
    {
        if(Category::find($category)->books()->get()->count()>0){
            return response()->json(['error' => 'It cannot be deleted, it is in use.']);
        }

        $cat = Category::find($category);
        $cat->delete();
        $cat = Category::all()->sortDesc();
        return response()->json(['success' => 'ok', 'categories' => $cat]);
    }
}
