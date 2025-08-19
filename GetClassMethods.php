<?php
$log_directory = $_SERVER['DOCUMENT_ROOT'].'/App/controllers';
if(isset($_GET["class"]) && $filenale = $_GET["class"])
{
    $results_array = array();
    // echo "File name -> ".$filenale . '<br />';
    # The Regular Expression for Function Declarations
    $functionFinder = '/function[\s\n]+(\S+)[\s\n]*\(/';
    # Init an Array to hold the Function Names
    $functionArray = array();
    # Load the Content of the PHP File
    $fileContents = file_get_contents( $log_directory."/".$filenale.".php" );
    # Apply the Regular Expression to the PHP File Contents
    preg_match_all( $functionFinder, $fileContents, $functionArray);
    
    # If we have a Result, Tidy It Up
    if( count( $functionArray ) > 1 )
    {
        # Grab Element 1, as it has the Matches
        $functionArray = $functionArray[1];
        // print_r($functionArray);
    }
    // echo("<pre>");
    
    $option = '<option value="">#</option>';
    foreach ($functionArray AS $list) {
        $option .= '<option value="'.$list.'" >'.$list.'</option>';
    }
        $response = ['response'=>true, 'data'=> $option];
    // echo($response);
    echo json_encode($response);
    // if (is_dir($log_directory))
    // {
    //         if ($handle = opendir($log_directory))
    //         {
    //                 //Notice the parentheses I added:
    //                 while(($file = readdir($handle)) !== FALSE)
    //                 {
    //                         $results_array[] = $file;
    //                 }
    //                 closedir($handle);
    //         }
    // }
    // //Output findings
    // foreach($results_array as $filenale)
    // {
    //     if ($filenale == "." || $filenale == "..") {

    //     } 
    //     else 
    //     {
    //         if((is_dir($log_directory."/".$filenale)))
    //         {
    //             echo($filenale);echo"<br>";
    //             continue;
    //         }
    //         echo "File name -> ".$filenale . '<br />';
    //         # The Regular Expression for Function Declarations
    //         $functionFinder = '/function[\s\n]+(\S+)[\s\n]*\(/';
    //         # Init an Array to hold the Function Names
    //         $functionArray = array();
    //         # Load the Content of the PHP File
    //         $fileContents = file_get_contents( $log_directory."/".$filenale );
    //         # Apply the Regular Expression to the PHP File Contents
    //         preg_match_all( $functionFinder, $fileContents, $functionArray);
    //         # If we have a Result, Tidy It Up
    //         if( count( $functionArray ) > 1 )
    //         {
    //             # Grab Element 1, as it has the Matches
    //             $functionArray = $functionArray[1];
    //             print_r($functionArray);
    //         }
    //         echo "<br />";
    //         echo "<br />";
            
    //     }
    // }
}
?>