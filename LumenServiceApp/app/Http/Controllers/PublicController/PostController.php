<?php
namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    // authozitation
    // check if current user is authorized to do this action

    public function index(Request $request){
        $posts = Post::with('user')->OrderBy("id","DESC")->paginate(10)->toArray();
        $response = [
            "total_count" => $posts["total"],
            "limit" => $posts["per_page"],
            "pagination" => [
                "next_page" => $posts["next_page_url"],
                "current_page" => $posts["current_page"]
            ],
            "data" => $posts["data"],
        ];

        $accpetHeader = $request->header('Accept');

        if($accpetHeader === 'application/json'){
            return response()->json($response, 200);
        } else if ($accpetHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<posts/>');
            foreach ($posts['data'] as $item){
                $xmlItem = $xml->addChild('post');
                $xmlItem->addChild('id', $item['id']);
                $xmlItem->addChild('title', $item['title']);
                $xmlItem->addChild('content', $item['content']);
                $xmlItem->addChild('image', $item['image']);
                $xmlItem->addChild('video', $item['video']);
                $xmlItem->addChild('status', $item['status']);
                $xmlItem->addChild('students_id', $item['students_id']);
                $xmlItem->addChild('categories_id', $item['categories_id']);
                $xmlItem->addChild('user_id', $item['user_id']);
                $xmlItem->addChild('created_at', $item['created_at']);
                $xmlItem->addChild('updated_at', $item['updated_at']);
            }
            return $xml->asXML();
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    public function store(Request $request){
        $rules = [
            'title' => 'required|string',
            'content' => 'required|string',
        ];

        $data = $request->all();

        if ($request->hasFile('image')){
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(storage_path('uploads/image_profile'), $imageName);
            $data['image'] = $imageName;
        }

        if ($request->hasFile('video')){
            $videoName = time().'.'.$request->video->getClientOriginalExtension();
            $request->video->move(storage_path('uploads/video_profile'), $videoName);
            $data['video'] = $videoName;
        }

        $accpetHeader = $request->header('Accept');
        $contentType = $request->header('Content-Type');

        if ($accpetHeader === 'application/json' || $accpetHeader === 'application/xml'){
            if ($contentType === 'application/json'){
                $post = Post::create($data);
                return response()->json([
                    'status' => 'success',
                    'data' => $post
                ], 200);
            } else  {
                $xml = new \SimpleXMLElement($request->getContent());
            
                $post = Post::create([
                    'title' => $xml->title,
                    'content' => $xml->content,
                    'image' => $xml->image,
                    'video' => $xml->video,
                    'status' => $xml->status,
                    'students_id' => $xml->students_id,
                    'categories_id' => $xml->categories_id,
                    'user_id' => $xml->user_id,
                ]);
                return $xml->asXML();
            }
        } else {
            return response('Not Acceptable header!', 406);
        }
    }

    public function show($id){

        $post = Post::with(['user' => function($query){
            $query->select('id', 'name');
        }])->find($id);

        if (!$post){
            abort(404);
        }

        // return response xml
        $accpetHeader = request()->header('Accept');
        if ($accpetHeader === 'application/xml'){
            $xml = new \SimpleXMLElement('<post/>');
            $xml->addChild('id', $post->id);
            $xml->addChild('title', $post->title);
            $xml->addChild('content', $post->content);
            $xml->addChild('image', $post->image);
            $xml->addChild('video', $post->video);
            $xml->addChild('status', $post->status);
            $xml->addChild('students_id', $post->students_id);
            $xml->addChild('categories_id', $post->categories_id);
            $xml->addChild('user_id', $post->user_id);
            $xml->addChild('created_at', $post->created_at);
            $xml->addChild('updated_at', $post->updated_at);
            $xml->addChild('user', $post->user->name);
            return $xml->asXML();
        } else if ($accpetHeader === 'application/json'){
            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 200);
        } else {
            return response('Not Acceptable header!', 406);
        }
    }
}