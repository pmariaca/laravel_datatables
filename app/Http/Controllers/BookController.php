<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use DataTables;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Book::query()->with('categories')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('delete_url', function ($data) {
                    return route('books.destroy', $data->id);
                })
                ->editColumn('image_url', function ($data) {
                    if ($data->image_url != '') {
                        return Storage::url($data->image_url);
                    }
                    return '';
                })
                ->addColumn('categorias', function ($data) {
                    return $data->categories;
                })
                ->make(true);
        }

        $categories = Category::all()->sortDesc();
        return view('books.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): mixed
    {
        $rules = [
            'name' => 'required|max:255',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'category_id' => 'required',
            'published_at' => 'required|date',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        #===========================
        $filePath = null;
        $file = $request->image_url;
        if ($request->hasFile('image_url')) {
            $filePath = $file->storeAs('images', time() . '' . $file->getClientOriginalName(), 'public');
        }
        $form_data = [
            'name' => $request->name,
            'image_url' => $filePath,
            'description' => $request->description,
            'published_at' => $request->published_at,
        ];
        $book = Book::create($form_data);
        $id = $book->id;
        foreach ($request->category_id as $ids) {
            BookCategory::create([
                'book_id' => $id,
                'category_id' => $ids
            ]);
        }
        return response()->json(['success' => 'data add successfully ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $post)
    {
        if (request()->ajax()) {
            $post = Book::with('categories')->findOrFail($post);
            return response()->json(['result' => $post]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'category_id' => 'required',
            'published_at' => 'required|date',
        ]);

        $filePath = null;

        $file = $request->image_url;
        if ($request->hasFile('image_url')) {
            $filePath = time() . '' . $file->getClientOriginalName();
        }

        $book = Book::find($request->hidden_id);
        $book->name = $request->name;
        $book->image_url = $filePath;
        $book->description = $request->description;
        $book->published_at = $request->published_at;
        

        if ($book->isDirty()) {
            if ($book->isDirty('image_url')) {
                if($book->getOriginal('image_url') !=null && Storage::disk('public')->exists($book->getOriginal('image_url')) ){
                    Storage::disk('public')->delete($book->getOriginal('image_url'));
                }
                if ($request->hasFile('image_url')) {
                    $filePath = $file->storeAs('images', $filePath, 'public');
                }
            }
            $book->image_url = $filePath;
            $book->update();
        }
        //=============================

        $cat_id = $cat_id_o = [];
        $cat = BookCategory::where('book_id', $book->id)->get();
        foreach ($cat as $ids) {
            $cat_id_o[$ids->category_id] = $ids->category_id;
        }
        foreach ($request->category_id as $ids) {
            $cat_id[$ids] = $ids;
        }
        $delete = array_diff($cat_id_o, $cat_id);
        $new = array_diff($cat_id, $cat_id_o);
        if (!empty($delete)) {
            $cats = BookCategory::where('book_id', $book->id)
                ->whereIn('category_id', $delete);
            $cats->delete();
        }
        foreach ($new as $ids) {
            BookCategory::create([
                'book_id' => $book->id,
                'category_id' => $ids
            ]);
        }
        return response()->json(['success' => 'data edit successfully ']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $book)
    {
        $book = Book::find($book);
        if($book->image_url!=null && Storage::disk('public')->exists($book->image_url) ){
            Storage::disk('public')->delete($book->image_url);
        }
        $book->delete();
        return response()->json(['success' => 'data delete successfully ']);
    }
}
