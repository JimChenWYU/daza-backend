<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\Article;

use Illuminate\Http\Request;

use App\Http\Requests;

class CategoryController extends Controller
{

    public function __construct()
    {
        // 执行 auth 认证
        $this->middleware('auth:api', [
            'except' => [
                'index',
                'show',
                'topics',
                'articles',
            ]
        ]);
    }

    public function index(Request $request)
    {
        $query = Category::orderBy('created_at', 'asc');
        return $this->pagination($query->paginate());
    }

    public function store(Request $request)
    {
        return $this->failure();
    }

    public function show(Request $request, $id)
    {
        $request->merge(['category' => $id]);
        $this->validate($request, ['category' => 'exists:categories,id']);

        $data = Category::find($id);
        return $this->success($data);
    }

    public function update(Request $request)
    {
        return $this->failure();
    }

    public function destroy(Request $request)
    {
        return $this->failure();
    }

    public function topics(Request $request, $id)
    {
        $query = Topic::where('category_id', $id);

        return $this->pagination($query->paginate());
    }

    public function articles(Request $request, $id)
    {
        $columns = [
            'articles.id',
            'articles.user_id',
            'articles.topic_id',
            'articles.title',
            'articles.summary',
            'articles.image_url',
            'articles.view_count',
            'articles.like_count',
            'articles.comment_count',
            'articles.published_at',
        ];

        $query = Article::select($columns)
            ->with(['user', 'topic'])
            ->leftJoin('topics', 'articles.topic_id', '=', 'topics.id')
            ->where('topics.category_id', $id)
            ->orderBy('published_at', 'desc');

        return $this->pagination($query->paginate());
    }

}
