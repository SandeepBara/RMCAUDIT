<?php
if(!function_exists('getDateRangeBetweenTwoDate')){
    function getDateRangeBetweenTwoDate($start, $end, $format = 'Y-m-d') { 
        // Declare an empty array 
        $array = array(); 
          
        // Variable that store the date interval 
        // of period 1 day 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        // Use loop to store date into array 
        foreach($period as $date) {                  
            $array[] = $date->format($format);  
        } 
      
        // Return the array elements 
        return $array; 
    } 
}

if(!function_exists('getFyFromFyListByFyID'))
{
    function getFyFromFyListByFyID($getFyList, $fyId) : string { 
       // print_r($getFyList);
       $fy = "not found !!!";
        foreach ($getFyList AS $list) {            
            if ($list['id']==$fyId) {
                $fy = $list['fy'];
                break;
            }
        }
        return $fy;
    } 
}

if(!function_exists('getDateFromFyAndQtr')) {
    function getDateFromFyAndQtr($fy, $qtr) : string { 
        $dateByFyQtr = "not found !!!";
        $qtrArr = [1,2,3,4];
        if(in_array($qtr, $qtrArr)) {
            $list = explode("-",$fy);
            if (sizeof($list)==2) {
                switch ($qtr) {
                    case 1:
                        $dateByFyQtr = $list[0]."-06-30";
                        break;
                    case 2:
                        $dateByFyQtr = $list[0]."-09-30";
                        break;
                    case 3:
                        $dateByFyQtr = $list[0]."-12-31";
                        break;
                    case 4:
                        $dateByFyQtr = $list[1]."-03-31";
                        break;
                }
            }
        }
        return $dateByFyQtr;
    } 
}

if(!function_exists('captcha')){
	function captcha($str){
		
		$bgColor=array('r'=>224, 'g'=>255, 'b'=>255);
		$textColor=array('r'=>255, 'g'=>0, 'b'=>0);
		
        $tmp=tempnam( sys_get_temp_dir(), 'img' );
		
		$w=100; $h=38;
        $image = imagecreate( $w, $h );
        $bck = imagecolorallocate( $image, $bgColor['r'], $bgColor['g'], $bgColor['b'] );
        $txt = imagecolorallocate( $image, $textColor['r'], $textColor['g'], $textColor['b'] );
		$linecolor = imagecolorallocate($image, 15, 191, 255);
        for($i=0; $i < 10; $i++) {
			imageline($image, 5, rand(0,50), 220, 25, $linecolor);
		}
        imagestring( $image, 5, 30, 10, $str, $txt );
        imagepng( $image, $tmp );
        imagedestroy( $image );

        $data=base64_encode( file_get_contents( $tmp ) );
        @unlink( $tmp );
        return 'data:image/png;base64,'.$data;
    }
}

// if(!function_exists('pagination'))
// {
// 	function pagination($pager, $show_data = null)
//     {
//         if(is_null($show_data)) {
//             $show_data = 10;
//         }
//         $uri_string = uri_string();
//         if (strpos($uri_string, 'page') !== false) {
//             list($lastURL, $pn) = explode("page=", $uri_string);
//             if ($pn=='clr') { $pn = 1; }
//         } else {
//             $lastURL = $uri_string."/";
//             $pn = 1;
//         }
//         $total_pages = ceil($pager/$show_data);
//         $base_url = base_url()."/".$lastURL;
        
//         $k = (($pn+4>$total_pages)?$total_pages-4:(($pn-4<1)?5:$pn));
//         //echo '<div class="row">';
//             echo '<div class="col-md-12">';
//                 echo '<div class="btn-toolbar">';
//                     echo '<div class="btn-group">';

//         if($total_pages==0 || is_null($pager)) {
//             //echo "<a class='btn btn-default btn-active-primary' href='".$base_url."page=1'> 1 </a>";
//         } else {
//             if ($pn>=2) {
//                 echo "<a class='btn btn-default btn-active-primary' href='".$base_url."page=1'> << </a>";
//                 echo "<a class='btn btn-default btn-active-primary' href='".$base_url."page=".($pn-1)."'> < </a>";
//             }
//             $pagLink = "";
//             if($total_pages>0 && $total_pages<9) {
//                 for ($i=1; $i<=$total_pages; $i++) {
//                     if($i==$pn)
//                     $pagLink .= "<a class='btn btn-default btn-active-primary active' href='".$base_url."page=".($i)."'>".($i)."</a>";
//                     else
//                     $pagLink .= "<a class='btn btn-default btn-active-primary' href='".$base_url."page=".($i)."'>".($i)."</a>";
//                 };
//             } else {
//                 for ($i=-4; $i<=4; $i++) {
//                     if($k+$i==$pn)
//                     $pagLink .= "<a class='btn btn-default btn-active-primary active' href='".$base_url."page=".($k+$i)."'>".($k+$i)."</a>";
//                     else
//                     $pagLink .= "<a class='btn btn-default btn-active-primary' href='".$base_url."page=".($k+$i)."'>".($k+$i)."</a>";  
//                 };
//             }
//             echo $pagLink;
//             if ($pn<$total_pages) {
//                 echo "<a class='btn btn-default btn-active-primary' href='".$base_url."page=".($pn+1)."'> > </a>";
//                 echo "<a class='btn btn-default btn-active-primary' href='".$base_url."page=".$total_pages."'> >> </a>";
//             }
//         }
//                     echo '</div>';
//                 echo '</div>';
//             echo '</div>';
//         //echo '</div>';
//     }
// }

function buildQueryString(array $data, string $prefix = ''): string {
    $query = [];

    foreach ($data as $key => $value) {
        // Format key for nested arrays (e.g., user[name] or items[0][id])
        $fullKey = $prefix === '' ? $key : $prefix . '[' . $key . ']';

        if (is_array($value)) {
            $query[] = buildQueryString($value, $fullKey); // recursion
        } else {
            $query[] = urlencode($fullKey) . '=' . urlencode($value);
        }
    }

    return implode('&', $query);
}

if(!function_exists('pagination'))
{
    function pagination($totalRecord, $limit=NULL)
    {
        $pagination = NULL;
        if($limit==NULL)
            $limit = limitInPagination();
        $adjacents = 3;
        $page = 0;
        $counter = 0;
        
        $targetpage=base_url().'/'.parse_url(uri_string(), PHP_URL_PATH);
        $get=$_GET;
        if(isset($get["page"]))
        unset($get["page"]);
        
        //print_r($get);
        $join='?';
        if(!empty($get))
        // foreach($get as $key=>$value)
        // $join.=$key.'='.urlencode($value).'&';
        foreach($get as $key=>$value){
            if(is_array($value)){
                $join.=buildQueryString($value,$key)."&";
            }else{
                $join.=$key.'='.urlencode($value).'&';
            }
        }
        $targetpage.=$join;
        
        
        if(isset($_GET["page"]) && $_GET["page"]!=NULL && is_numeric($_GET["page"]))
            $page = intval($_REQUEST["page"]);
        if ($page <= 0)
            $page = 1;
        $prev = $page - 1;
        $next = $page + 1;
      
        $lastpage = ceil($totalRecord / $limit);
        $lpm1 = $lastpage - 1;
        if ($lastpage > 1)
        {
            $pagination .= '
            <ul class="pagination pagination-sm no-margin pull-right">';
            if ($page > 1)
                $pagination.= "<li><a href=\"$targetpage"."page=$prev\">Previous</a></li>";
            else
                $pagination.= "<li class='disabled'><a href='#'>Previous </a></li>";
            if ($lastpage < 7 + ($adjacents * 2))
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.='<li class="active"><a href="#"><span>'.$counter.'</span></a></li>';
                    else
                        $pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
                }
            }
            else if ($lastpage > 5 + ($adjacents * 2))
            {
                if ($page < 1 + ($adjacents * 2))
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.='<li class="active"><a href="#">'.$counter.'  </a></li>';
                        else
                            $pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
                    }
                    $pagination.= "<li class='disabled'><a href='#'>... </a></li>";
                    $pagination.= "<li><a href=\"$targetpage"."page=$lpm1\">$lpm1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage"."page=$lastpage\">$lastpage</a></li>";
                }
                else if ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<li><a href=\"$targetpage"."page=1\">1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage"."page=2\">2</a></li>";
    
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.='<li class="active"><a href="#">'.$counter.'  </a></li>';
                        else
                            $pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
                    }
                    $pagination.= "<li><a href=\"$targetpage"."page=$lpm1\">$lpm1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage"."page=$lastpage\">$lastpage</a></li>";
                }
                else
                {
                    $pagination.= "<li><a href=\"$targetpage"."page=1\">1</a></li>";
                    $pagination.= "<li><a href=\"$targetpage"."page=2\">2</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.='<li class="active"><a href="#">'.$counter.'  </a></li>';
                        else
                            $pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
    
                    }
                }
            }
            if ($page < $counter - 1)
                $pagination.= "<li><a href=\"$targetpage"."page=$next\">Next</a></li>";
            else
                $pagination.= "<li class='disabled'><a href='#'>Next</a></li>";
            $pagination.= "</ul>";
        }
        return $pagination;
    }

    if(!function_exists('number_format_ind')) {
        function number_format_ind($number){
            if ($number==0) {
                return number_format($number, 2);
            }
            $decimal = (string)($number - floor($number));
            $money = floor($number);
            $length = strlen($money);
            $delimiter = '';
            $money = strrev($money);

            for($i=0;$i<$length;$i++){
                if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                    $delimiter .=',';
                }
                $delimiter .=$money[$i];
            }

            $result = strrev($delimiter);
            $decimal = preg_replace("/0\./i", ".", $decimal);
            $decimal = substr($decimal, 0, 3);

            if( $decimal != '0'){
                $result = $result.$decimal;
            }

            return $result;
        }
    }

    if(!function_exists('multiExplode')) {
        function multiExplode($delimiters, $string) {
            $phase = str_replace($delimiters, $delimiters[0], $string);
            $processed = explode($delimiters[0], $phase);
            return  $processed;
          }
    }
    
    if(!function_exists('getDiffMonth')) {
        function getDiffMonth($date1, $date2) {
            $begin = new DateTime($date1);
            $end = new DateTime($date2);
            $end = $end->modify('+1 month');
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($begin, $interval, $end);
            $counter = 0;
            foreach($period as $dt) {
                $counter++;
            }
            return $counter;
        }
    }

}