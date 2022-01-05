<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CloudinaryStorage extends Controller
{

   public static function path($path){
       return pathinfo($path, PATHINFO_FILENAME);
   }

   public static function upload($image, $fileName, $folder){
       $newFilename = str_replace(' ', '-',$fileName);
       $public_id = date('Y-m-d_His').'_'.$newFilename;
       $result = cloudinary()->upload($image, [
           "public_id" => self::path($public_id),
           "folder" => $folder
       ])->getSecurePath();

       return $result;
   }

   public static function replace($path, $image, $fileName, $folder){
       self::delete($path, $folder);
       return self::upload($image, $fileName, $folder);
   }
    
   public static function delete($path, $folder){
       $public_id = $folder.'/'.self::path($path);
       return cloudinary()->destroy($public_id);
   }
}
