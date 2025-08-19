<?php
// $servername = "localhost";
// $username = "root";
// $password = "smrt@wste2021";
// $dbname = "db_dmc_property";

// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

   $host        = "host = 127.0.0.1";
   $port        = "port = 5433";
   $dbname      = "dbname = db_rmc_property";
   $credentials = "user = postgres password=aadrika#123";

   $db = pg_connect( "$host $port $dbname $credentials"  );
   if(!$db) {
      echo "Error : Unable to open database\n";
   } else {
      echo "Opened database successfully\n";
   }

   

$sql = "SELECT * FROM tbl_capital_value_rate_raw";
// $sql = "SELECT count(*) FROM tbl_dmc_capital_rate_raw";
// $sql = "SELECT * FROM tbl_swm_consumer_data where apt_code !='' limit 10";
$ret = pg_query($db, $sql);

if($ret) {
//    echo pg_last_error($db);
//    exit;
// } 
$rows = pg_num_rows($ret);
// $row = pg_fetch_row($ret);
echo "number of rows <br/>";
print_r($rows); echo "<br/>";
// echo "no of raws ";
// die;
// $DD=0;
$sr = 1;
while($row = pg_fetch_assoc($ret)) {

    //data to push at tbl_capital_value_rate at rmc
    //property_type
    // ward_no
    // road_type_mstr_id
    // usage_type
    // rate
    // effect_from
    // status
    
    $ward_no=$row['ward_no'];



        
        $i;
        for($i=1;$i<17;$i++){
            if($i==1){
                $rate=$row['res_vaccant_main'];
                $road_type_mstr_id=1;

            }
            if($i==2){
                $rate=$row['com_vaccant_main'];
                $road_type_mstr_id=1;
            }
            if($i==3){
                $rate=$row['res_vaccant_other'];
                $road_type_mstr_id=0;
            }
            if($i==4){
                $rate=$row['com_vaccant_other'];
                $road_type_mstr_id=0;
            }

            if($i==5){
                $rate=$row['res_apt_main'];
                $road_type_mstr_id=1;
            }
            if($i==6){
                $rate=$row['com_apt_main'];
                $road_type_mstr_id=1;
            }
            if($i==7){
                $rate=$row['res_apt_other'];
                $road_type_mstr_id=0;
            }
            if($i==8){
                $rate=$row['com_apt_other'];
                $road_type_mstr_id=0;
            }

            if($i==9){
                $rate=$row['res_pakka_main'];
                $road_type_mstr_id=1;
            }
            if($i==10){
                $rate=$row['com_pakka_main'];
                $road_type_mstr_id=1;
            }
            if($i==11){
                $rate=$row['res_pukka_other'];
                $road_type_mstr_id=0;
            }
            if($i==12){
                $rate=$row['com_pakka_other'];
                $road_type_mstr_id=0;
            }

            if($i==13){
                $rate=$row['res_kuccha_main'];
                $road_type_mstr_id=1;
            }
            if($i==14){
                $rate=$row['com_kuccha_main'];
                $road_type_mstr_id=1;
            }
            if($i==15){
                $rate=$row['res_kuccha_other'];
                $road_type_mstr_id=0;
            }
            if($i==16){
                $rate=$row['com_kuccha_other'];
                $road_type_mstr_id=0;
            }
            

            if(($i%2)==0){
                $usage_type="COMMERCIAL";

            }else{
                $usage_type="RESIDENTIAL";

            }

            if($i<5){
                $property_type="VACCANT_LAND";
            }
            else if($i>4 && $i<9){
                $property_type="DLX_APARTMENT";
            }
            else if($i>8 && $i<13){
                $property_type="BUILDING_PAKKA";
            }
        else{
                $property_type="BUILDING_KACCHA";
            }
            $sql = "insert into tbl_capital_value_rate
             (property_type,ward_no,road_type_mstr_id,usage_type,rate,effect_from,status) values('$property_type','$ward_no','$road_type_mstr_id','$usage_type','$rate','2022-04-01',1)";
                // $result = $conn->query($sql);
                $result=pg_query($sql);
                // $dlc_rate_id = $conn-> insert_id;
                $dlc_rate_id = pg_last_oid($result);
                echo "inserted id ".$dlc_rate_id;
                echo "<br/>";

                
        }
        $i=1;
        echo "<br/>bottom i= ".$i;
    
       
        
        
        

   
      
       

  $sr++;
      }

      echo "<h1>All data has been inserted !!</h1>";
}
else{
    echo "no data found !!";
}

   

