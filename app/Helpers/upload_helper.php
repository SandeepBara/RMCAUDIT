<?php
  // Simple page redirect
/**
 * 
 */

function uploader($file, $path, $fileName = null, $extensions = [], $fileSize = null){

	if($fileSize==null){
		$fileSize = 5242880; // File Size 5 MB
	}

	if(empty($extensions)){
		$extensions = array("jpeg","jpg","png","gif",'pdf','doc','docs');
	}

  	if(isset($file) && $path!=null){
      $errors = array();
      $file_name = $file['name'];
      $file_size = $file['size'];
      $file_tmp = $file['tmp_name'];
      $file_type = $file['type'];
      $tmp = explode('.', $file_name);
	  $file_ext = strtolower(end($tmp));
      
      if($fileName==null){
			$fileName = $file_name;
      }
      $path = 'writable/uploads/'.$path;
      if (!is_dir($path)) {
      	$errors[]='Path not exist.';
      }
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="Extension not allowed.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size not allowed.';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp, $path."/".$fileName.'.'.$file_ext);
         if(file_exists($path."/".$fileName.'.'.$file_ext)) {
         	return true;
         }else{
         	throw new Exception("File (".$file_name.") Not Uploaded! (Something wrong.)");
         }
      }else{
      	throw new Exception($errors[0]);
      }
   }
}

function Base64ImageEncoder($file){
   if(isset($file)){
      if($file['error'] == 4){
         return false;
      }else{
         $check = getimagesize($file["tmp_name"]);
         if($check !== false) {
            $data = base64_encode(file_get_contents($file["tmp_name"]));
            return "data:".$check["mime"].";base64,".$data;
         } else {
            return false;
         }
      }
   }
}
function Base64FileUpload($data, $path, $newFileName, $extensions = [], $fileSize = null){
   $errors = array();
   if(isset($data) && $path!=null && $newFileName!=null){
      $type = explode(';', $data)[0];
      $data = explode(';', $data)[1];
      list(, $data)      = explode(',', $data);
      $file_ext = explode("/",$type)[1];

      if(empty($extensions)){
         $extensions = array("jpeg","jpg","png","gif",'pdf','doc','docs');
      }
      if($fileSize==null){
         $fileSize = 1048576; // 1 MB
      }

      if(in_array($file_ext, $extensions)=== false){
         $errors[]="Extension not allowed.";
      }
      if(strlen(base64_decode($data)) > $fileSize){
         $errors[]="File size must be excately 1 MB.";
      }
      $path = "uploads/".$path."/";
      if (!is_dir($path)) {
         $errors[]='Path not exist.';
      }
      if(empty($errors)==true){
         $data = base64_decode($data);
         $fileName = $newFileName.".".$file_ext;  
         file_put_contents($path.$fileName, $data);
         if(file_exists($path.$fileName)) {
            return $fileName;
         }else{
            throw new Exception("File (".$newFileName.") Not Uploaded! (Something wrong.)");
         }
      }else{
         throw new Exception($errors[0]);
      }
   }else{
      throw new Exception("File Not Uploaded! (Something wrong.)");
   }
}

  			