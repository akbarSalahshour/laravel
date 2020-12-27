<?php

namespace App\Http\Controllers\api\v1;

use App\Article;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Category as CategoryResource;
use App\Http\Resources\v1\CategoryCollection;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $categories=Category::all();
        return new CategoryCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:6',
        ]);
        Article::create([
            $validated,
            'user_id' => auth()->user()->id,
        ]);
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'دسته بندی مورد نظر با موفقیت ایجاد شد.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if($category->user_id == auth()->user()->id){
            $validated = $request->validate([
                'title' => 'required|min:6',
            ]);
            $category->title=$validated['title'];
            $category->save();
        }
        throw new UnauthorizedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->user_id == auth()->user()->id){
            $category->delete();
        }
        throw new UnauthorizedException();
    }
}
