<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function getRequestJson(Request $request)
    {
        $url = 'http://localhost:8000/public/post';
        $header = ['Accept: application/json'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        echo"<pre>";print_r($response);die();
        return view('posts/getRequestJson', ['results' => $response]);
    }

    // public function getRequestXml(Request $request)
    // {
    //     $url = 'http://localhost:8000/public/post';
    //     $header = ['Accept: application/xml'];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //     $result = curl_exec($ch);
    //     curl_close($ch);
    //     $response = simplexml_load_string($result);
    //     // echo"<pre>";print_r($response);die();
    //     return view('posts/getRequestXml', ['results' => $response]);
    // }

    public function postRequestJson(Request $request)
    {
        $url='http://localhost:8000/public/post';
        $headers = ['Accept: application/json','Content-Type: application/json'];
        $data = [
                "user_id" => "1",
                "title" => "Post Title",
                "content" => "Post Content",
                "status" => "1",
                "image" => "Post Image",
                "video" => "Post Video",
                "categories_id" => "1",
                "students_id" => "1"
            
        ];


        $dataJSON = json_encode($data);

        // initiate curl
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // set url
        curl_setopt($ch, CURLOPT_URL,$url);
        // set headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // set posst data
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJSON);
        // execute
        $result = curl_exec($ch);
        // closing
        curl_close($ch);

        // parse json response from request to be php object
        $response = json_decode($result, true);

        if ($response === null) {
            // Handle JSON decoding error
            return response()->json(['error' => 'Failed to decode JSON response'], 500);
        }
    

        // echo"<pre>";print_r($response);die();
        // dd($response);
        // return response()->json($response, 200);
        return view('posts/postRequestJson',['result'=> $response]);
    }
}