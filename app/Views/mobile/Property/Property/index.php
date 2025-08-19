<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Hello</title>

  </head>
  <body>
      <h1>Print Page</h1>
	  <br/>
	  <br/><br/>
	  <a href="index2.php">Go to second Page</a>
	  <br />
	  <br/><br/>
<?php
$txt = "";
	$txt .= "<bc>KODERMA MSWM</bc><br />";
	$txt .= "<bc>Private Limited</bc><br />";
	$txt .= "<bc>KODARMA NAGAR PANCHAYAT</bc><br />";
	$txt .= "<nc>Citizen Copy</nc><br />";
	$txt .= "<nc>Payment Receipt</nc><br />";
	$txt .= "<n>-----------------------------------------</n><br />";
	$txt .= "<n>Date           :  2020-08-02</n><br />";
	$txt .= "<n>POS ID         :  XXXXXXXXXXX</n><br />";
	$txt .= "<n>Ward No.       :  11</n><br />";
	$txt .= "<n>Transaction No.:  43302082020408</n><br />";
	$txt .= "<n>Consumer No.   :  111040000372</n><br />";
	$txt .= "<n>Holding No.    :  </n><br />";
	$txt .= "<n>Consumer Name  :  RAMESH  SINGH</n><br />";
	$txt .= "<n>Address        :  BARSOTIYABAR</n><br />";
	$txt .= "<n>-----------------------------------------</n><br />";
	$txt .= "<n>Monthly Fee   :  30.00</n><br />";
	$txt .= "<n>Amount Paid   :  90.00</n><br />";
	$txt .= "<n>Paid  Upto    :  Apr 2020 - Jun 2020</n><br />";
	$txt .= "<n>Payment Mode  :  Cash</n><br />";
	$txt .= "<n>------------------------------------------</n><br />";
	$txt .= "<n>TC Name        :   Vivek kumar </n><br />";
	$txt .= "<n>Mobile No.     :   9955003314</n><br />";
	$txt .= "<n>Please keep this Bill For Future Reference</n><br />";
	$txt .= "<n></n><br />";
	$txt .= "<n></n><br />";
	$txt .= "<n></n><br />";
	$txt .= "<n></n><br />";
	$txt .= "<n></n><br />";

?>
	  <input type="text" id="bt_printer" value="<?=$txt;?>" />
	  <br/ >
	  <a href="https://google.com">Google</a>
      <script type="text/javascript">
		function bt_printer(){
			var url = document.getElementById("bt_printer").value;
			AndroidInterface.btPrinter(url);
		}
		bt_printer();
      </script>
  </body>
</html>