<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Category;

class PostController extends Controller
{
    public function index(Post $post)
    {
        // クライアントインスタンス生成
        $client = new \GuzzleHttp\Client();
        
        // GET通信するURL
        $url = 'https://teratail.com/api/v1/questions';
        
        // リクエスト送信と返却データの取得
        // Bearerトークンにアクセストークンを指定して認証を行う
        $response = $client->request(
            'GET',
            $url,
            ['Bearer' => config('services.teratail.token')]
        );
        
        $questions = json_decode($response->getBody(), true);
        
        
        return view('posts.index')->with([
            'posts' => $post->getPaginateByLimit(),
            'questions' => $questions['questions'],
        ]);
       //blade内で使う変数'posts'と設定。'posts'の中身にgetを使い、インスタンス化した$postを代入。
    }
    
    public function show(Post $post)
    {
    return view('posts.show')->with(['post' => $post]);
 //'post'はbladeファイルで使う変数。中身は$postはid=1のPostインスタンス。
    }
    
    public function create(Category $category)
    {
    return view('posts.create')->with(['categories' => $category->get()]);
    }
    
    public function store(Post $post, PostRequest $request)
    {
    $input = $request['post'];
    $post->fill($input)->save();
    return redirect('/posts/' . $post->id);
    }
    
    public function edit(Post $post)
    {
    return view('posts.edit')->with(['post' => $post]);
    }
    
    public function update(PostRequest $request, Post $post)
    {
    $input_post = $request['post'];
    $post->fill($input_post)->save();

    return redirect('/posts/' . $post->id);
    }
    
    public function delete(Post $post)
    {
    $post->delete();
    return redirect('/');
    }
}
?>