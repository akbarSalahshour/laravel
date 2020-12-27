<?php

namespace App\Http\Controllers\api\v1;

use App\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ArticleCollection;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\v1\Article as ArticleResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ArticleResource
     */
    public function index(Request $request): ArticleResource
    {
        $where='1=1';
        if($request->filled('user_id'))
            $where.=' and user_id='.$request->user_id;
        if($request->filled('category_id'))
            $where.=' and category_id='.$request->category_id;
        if($request->filled('title'))
            $where.=" and title LIKE '%".$request->title."%'";
        if($request->filled('description'))
            $where.=" and description LIKE '%".$request->description."%'";
        $articles = DB::select('select * from articles  where '.$where);
        return new ArticleResource($articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:category',
            'title' => 'required|min:6',
            'description' => 'required',
        ]);
        Article::create([
            $validated,
            'user_id' => auth()->user()->id,
        ]);
        return \response()->json([
            'data' => [],
            'status' => 'success',
            'message' => 'مقاله مورد نظر با موفقیت ایجاد شد.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        $article->visited+=1;
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Article $request, Article $article)
    {
        if($article->user_id == auth()->user()->id){
            $validated = $request->validate([
                'category_id' => 'required|exists:category',
                'title' => 'required|min:6',
                'description' => 'required',
            ]);
            $article->category_id=$validated['category_id'];
            $article->title=$validated['title'];
            $article->description=$validated['description'];
            $article->save();
        }
        throw new UnauthorizedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if($article->user_id == auth()->user()->id){
            $article->delete();
        }
        throw new UnauthorizedException();
    }
}
