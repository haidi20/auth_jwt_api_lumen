<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
class PostController extends Controller
{
    protected $pathUpload = "image_profile/";

    public function __construct()
    {
        app('Illuminate\Http\Request');
    }

    public function index() {
        $paged = request("paged", 1);
        $per_page = request("per_page", 10);

        $link = "https://youlead.id/wp-json/barav/v1/posts?paged=".$paged."&per_page=".$per_page;
        $posts = $this->api($link)->original;

        return $this->responseWithSuccess($posts);
    }

    public function search() {
      $search = request("s");
      $search = str_replace(' ', '%20', $search);
      $link = "https://youlead.id/wp-json/barav/v1/search?s=".$search;

      $posts = $this->api($link)->original;

      return $this->responseWithSuccess($posts);
    }

    public function uploadPhoto(Request $request) {
      $token  = $request->bearerToken();
      $data   = User::where('token', $token)->first();     


      if(request()->hasFile('file')){
          @unlink(public_path($this->pathUpload.$data->photo_file_path));
          $file       = request()->file('file');
          $extension  = $file->getClientOriginalExtension();
          $fileName   = str_random(8) . '.' . $extension;
          $file->move($this->pathUpload, $fileName);
          
          // if($file){
              try {
                  $user = User::where('token', $token);
                  $user->update(["photo_file_path" => $fileName]);
              } catch (\Exception $e) {
                  return $this->responseWithError("failed upload image");
              }
          // }

          return $this->responseWithSuccess($fileName);
      }

      return $this->responseWithError("not found file");
    }

    private function api($link){
        $curl = curl_init();
        curl_setopt_array($curl,array(
          CURLOPT_URL => $link,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => false,
          // CURLOPT_ENCODING => "",
          CURLOPT_TIMEOUT => 300,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array (
            'Content-Type : application/json',
            // 'Authorization :'.$this->token(),
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "error = ".$err ;
        }else{
          $data = json_decode($response, true);
          return response()->json($data);
        }
    }
}