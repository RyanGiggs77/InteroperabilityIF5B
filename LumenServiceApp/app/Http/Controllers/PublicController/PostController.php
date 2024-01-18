<?php
namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations\PathItem;


class PostController extends Controller
{
     /**
      * @OA\Post(
        *     path="/public/post",
        *     summary="Create a new post",
        *     @OA\RequestBody(
        *         @OA\MediaType(
        *             mediaType="application/json",
        *             @OA\Schema(
        *                 @OA\Property(
        *                     property="title",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="content",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="image",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="video",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="status",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="students_id",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="categories_id",
        *                     type="string"
        *                 ),
        *                 @OA\Property(
        *                     property="user_id",
        *                     type="string"
        *                 ),
        *                 example={"title": "Post Title", "content": "Post Content", "image": "Post Image", "video": "Post Video", "status": "draft", "students_id": "1", "categories_id": "1", "user_id": "1"}
        *             )
        *         )
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="OK"
        *     )
        * ),
        * @OA\Get(
        *     path="/public/post",
        *     summary = "Get Public posts",
        *     @OA\Parameter(name="page",
        *       in="query",
        *       required=false,
        *       @OA\Schema(type="number")
        *       ),
        *     @OA\Response(
        *        response=200,
        *        description="OK"
        *     )
        * ),
        * @OA\Get(
        *     path="/public/post/{id}",
        *     summary = "Get Public post by id",
        *     @OA\Parameter(name="id",
        *       in="path",
        *       required=true,
        *       @OA\Schema(type="number")
        *       ),
        *     @OA\Response(
        *        response=200,
        *        description="OK"
        *     )
        * ),
        */
     
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

        return response()->json($response, 200);

        // $accpetHeader = $request->header('Accept');

        // if($accpetHeader === 'application/json'){
        //     return response()->json($response, 200);
        // } else if ($accpetHeader === 'application/xml'){
        //     $xml = new \SimpleXMLElement('<posts/>');
        //     foreach ($posts['data'] as $item){
        //         $xmlItem = $xml->addChild('post');
        //         $xmlItem->addChild('id', $item['id']);
        //         $xmlItem->addChild('title', $item['title']);
        //         $xmlItem->addChild('content', $item['content']);
        //         $xmlItem->addChild('image', $item['image']);
        //         $xmlItem->addChild('video', $item['video']);
        //         $xmlItem->addChild('status', $item['status']);
        //         $xmlItem->addChild('students_id', $item['students_id']);
        //         $xmlItem->addChild('categories_id', $item['categories_id']);
        //         $xmlItem->addChild('user_id', $item['user_id']);
        //         $xmlItem->addChild('created_at', $item['created_at']);
        //         $xmlItem->addChild('updated_at', $item['updated_at']);
        //     }
        //     return $xml->asXML();
        // } else {
        //     return response('Not Acceptable!', 406);
        // }
    }

    public function store(Request $request){

        $data = $request->all();

        $accpetHeader = $request->header('Accept');
        $contentType = $request->header('Content-Type');

        // if ($accpetHeader === 'application/json' || $accpetHeader === 'application/xml'){
        //     if ($contentType === 'application/json'){
                $post = Post::create($data);
                return response()->json([
                    'status' => 'success',
                    'data' => $post
                ], 200);
        //     } else  {
        //         $xml = new \SimpleXMLElement($request->getContent());
            
        //         $post = Post::create([
        //             'title' => $xml->title,
        //             'content' => $xml->content,
        //             'image' => $xml->image,
        //             'video' => $xml->video,
        //             'status' => $xml->status,
        //             'students_id' => $xml->students_id,
        //             'categories_id' => $xml->categories_id,
        //             'user_id' => $xml->user_id,
        //         ]);
        //         return $xml->asXML();
        //     }
        // } 
        // else {
        //     return response('Not Acceptable header!', 406);
        // }
    }

    public function show($id){

        $post = Post::with(['user' => function($query){
            $query->select('id', 'name');
        }])->find($id);

        if (!$post){
            abort(404);
        }

        // return response xml
        // $accpetHeader = request()->header('Accept');
        // if ($accpetHeader === 'application/xml'){
        //     $xml = new \SimpleXMLElement('<post/>');
        //     $xml->addChild('id', $post->id);
        //     $xml->addChild('title', $post->title);
        //     $xml->addChild('content', $post->content);
        //     $xml->addChild('image', $post->image);
        //     $xml->addChild('video', $post->video);
        //     $xml->addChild('status', $post->status);
        //     $xml->addChild('students_id', $post->students_id);
        //     $xml->addChild('categories_id', $post->categories_id);
        //     $xml->addChild('user_id', $post->user_id);
        //     $xml->addChild('created_at', $post->created_at);
        //     $xml->addChild('updated_at', $post->updated_at);
        //     $xml->addChild('user', $post->user->name);
        //     return $xml->asXML();
        // } else if ($accpetHeader === 'application/json'){
            return response()->json([
                'status' => 'success',
                'data' => $post
            ], 200);
        // } else {
        //     return response('Not Acceptable header!', 406);
        // }
    }
}