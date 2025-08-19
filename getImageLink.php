<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/app/Helpers/'."db_helper.php");
class getImageLink
{
    // for viewing or showing document

    public static function getImage($path)
    {     
        $server = (DocServer($path));
		$target_url =$server["server"] ."delete";
		$drive = $server["drive"];
        $full_path = $server["server"] .'read';
        $full_path2 = $server["server"] .'mim-type';
        $postData = ["targetPath"=>"uploads/".$path,"drive"=>$drive];
        $curl = curl_init();		
		curl_setopt($curl, CURLOPT_URL, $full_path2);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $response =  json_decode(curl_exec($curl),true)??"";
       
       $mim = $response["mime"]??"";
       $size = $response["size"]??"";
       curl_close($curl);
        if(!$mim)
        {
            die("File not available."); 
        }
		$curl = curl_init();		
		curl_setopt($curl, CURLOPT_URL, $full_path);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// $response = json_decode(curl_exec($curl),true);
        
        
        header('Content-type: ' . $mim);        
        header('Content-Length: ' . $size);
        header('Cache-Control: no-cache');
        header('Content-Transfer-Encoding: binary'); 
        header('Accept-Ranges: bytes');
		echo ((curl_exec($curl)));
        curl_close($curl);
        die;
    }
}



getImageLink::getImage($_GET["path"]);