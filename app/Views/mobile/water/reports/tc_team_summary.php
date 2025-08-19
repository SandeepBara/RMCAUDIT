<?=$this->include("layout_mobi/header");?>
<?php
$demand = null;
$new_connection=null;

?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
           
            <div class="panel-heading flex" style="display: flex;">
                <div style="flex:1;">
                    <h3 class="panel-title"><b style="color:white;">Collection Summary</b></h3>
                </div>
                <div style="flex:1;text-align:right"><button onclick="history.go(-1)" class="btn btn-info btn_wait_load">Back</button></div>

            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="GET">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6 pad-btm">
                            <input type="date" id="from_date" name="from_date" class="form-control"  value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                        </div>
                        <div class="col-sm-6 pad-btm">
                            <input type="date" id="to_date" name="to_date" class="form-control" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label" for="Payment Mode"><b>Ward</b><span class="text-danger">*</span> </label>
                        </div>
                        <div class="col-sm-6 pad-btm">
                            <select id="ward_id" name="ward_id" class="form-control">
                               <option value="">ALL</option> 
                                <?php foreach($wardList as $value):?>
                                <option value="<?=$value['id']?>" <?=(isset($ward_id))?($ward_id==$value["id"]?"SELECTED":""):"";?>><?=$value['ward_no'];?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-success" style="width:100%" id="btn_search" name="btn_search" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
            if(!empty($transaction))
            {
            ?>
            <!-- printable area -->
            <div class="panel panel-bordered panel-dark">                
                <div class="panel-body"> 
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="panel">
                                <div id="blutooth_printable_area">
                                    <?php 
                                    $ulb_name = $ulb_mstr_name["ulb_name"];
                                    
                                    ?>
                                    <center>
                                    <strong>Collection Summary</strong>
                                    </center><br /> 
                                    <center>
                                    <strong><?=$module??'';?></strong>
                                    </center><br />                                        
                                    <center><b><?=$ulb_name;?></b></center><br /> 
                                    <center>------------------------------------------------</center><br />
                                    Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?=date("d-m-Y H:m:s A");?><br />
                                    TC Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $emp_dtls["emp_name"]; ?><br />
                                    Mobile No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   :&nbsp;&nbsp; <?php echo $emp_dtls["personal_phone_no"]; ?><br />                           
                                
                                        
                                    <center>------------------------------------------------</center><br />
                                    SL &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Trans No.&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Date&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;Mode &nbsp;&nbsp;|&nbsp;&nbsp;Amount &nbsp;&nbsp;&nbsp;<br>
                                    <center>------------------------------------------------</center><br />
                                    <?php
                                    $i=0;
                                    $temp = "";
                                    $check = 0;
                                    $dd=0;
                                    $cash =0;
                                    $totalCash = 0;
                                    $totalCheque = 0;
                                    $totalDD = 0;
                                    foreach($transaction as $val)
                                    { 
                                        $tr_no = (substr($val['transaction_no'],strlen($val['transaction_no'])-5,5));
                                        $tr_no = str_pad($tr_no,9,"X",STR_PAD_LEFT);
                                        $sl= str_pad(++$i,2,"0",STR_PAD_LEFT);
                                        $mod = str_pad($val['payment_mode'],7," ",STR_PAD_LEFT);
                                        if(strtoupper($val['payment_mode'])=='CASH') {
                                            $totalCash += $val['paid_amount'];
                                            ++$cash;
                                        }
                                        if(strtoupper($val['payment_mode'])=='DD') {
                                            $totalDD += $val['paid_amount'];
                                            ++$dd;
                                        }
                                        if(strtoupper($val['payment_mode'])=='CHEQUE') {
                                            $totalCheque += $val['paid_amount'];
                                            ++$check;
                                        }

                                        echo( $sl."&nbsp;&nbsp;|&nbsp;".($tr_no)."|&nbsp;".$val['created_on']."&nbsp;|".$mod."|&nbsp;".$val['paid_amount']."&nbsp;"."<br/>");
                                        $temp .= "<n>".$sl." | ".$tr_no." | ".$val['created_on']." |".$mod." | ".$val['paid_amount']."</n><br />";
                                    }
                                    ?> <br><br>
                                    <strong>Cash (<?=$cash?>) >> Rs. <?=$totalCash;?></strong><br/>
                                    <strong>Cheque (<?=$check?>) >> RS. <?=$totalCheque;?></strong><br/>
                                    <strong>DD (<?=$dd?>) >> Rs. <?=$totalDD;?></strong><br/>
                                    <strong>Total Amount >> Rs. (<?=$total??0?>)</strong><br/>                                  
                                    <center>------------------------------------------------</center><br />                                
                                    <?php

                                        $txt = "";                                    
                                        $txt .= "<nc>Collection Summary</nc><br />";
                                        $txt .= "<nc>".$module??''."</nc><br />";
                                        $txt .= "<nc>".$ulb_name."</nc><br />";
                                        $txt .= "<n>-----------------------------------------</n><br />";
                                        $txt .= "<n>Date           :  ".date("d-m-Y H:m:s A")."</n><br />";
                                        $txt .= "<n>TC Name        :  ".$emp_dtls["emp_name"]."</n><br />";
                                        $txt .= "<n>Mobile No.     :  ".$emp_dtls["personal_phone_no"]."</n><br />";                                            
                                        $txt .= "<n>-----------------------------------------</n><br />";
                                        $txt .= "<n>SL | Trans No. |   Date  | Mode  | Amount </n><br />";
                                        $txt .= "<n>-----------------------------------------</n><br />";

                                        $txt .= $temp;
                                        $txt .= "<n></n><br />";
                                        $txt .= "<n></n><br />";
                                        $txt .="<n>Cash   : ".$cash." >> Rs. ".$totalCash."</n><br />";
                                        $txt .="<n>Cheque : ".$check." >> Rs. ".$totalCheque."</n><br />";
                                        $txt .="<n>DD     : ".$dd." >> Rs. ".$totalDD."</n><br />";
                                        $txt .="<n>Total Amount : Rs. ".($total??0)."</n><br />";
                                        $txt .= "<n>------------------------------------------</n><br />";
                                        
                                        $txt .= "<n>Please keep this Bill For Future Reference</n><br />";
                                        $txt .= "<n>Toll Free No. 18008904665</n><br />";
                                        $txt .= "<n></n><br />";
                                        $txt .= "<n></n><br />";
                                        $txt .= "<n></n><br />";
                                        $txt .= "<n></n><br />";
                                        $txt .= "<n></n><br />";
                                        
                                        // print_var($txt);
                                    ?>     

                                    <input type="hidden" id="bt_printer" value="<?=$txt;?>" />
                            
                                </div>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>

            <?php
            }
        ?>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<script type="text/javascript">
function bt_printer(){
    var url = document.getElementById("bt_printer").value;
    AndroidInterface.btPrinter(url);
}
bt_printer();
</script>

<?=$this->include("layout_mobi/footer");?>

<script type="text/javascript">
    $(document).ready(function(){

        $('#btn_search').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date=="")
            {
                $("#from_date").css({"border-color":"red"});
                $("#from_date").focus();
                return false;
            }
            if(to_date=="")
            {
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                return false;
            }
            if(to_date<from_date)
            {
                alert("To Date Should Be Greater Than Or Equals To From Date");
                $("#to_date").css({"border-color":"red"});
                $("#to_date").focus();
                return false;
            }else{
                $('#btn_search').html('Please Wait...');
            }
        });
        $("#from_date").change(function(){$(this).css('border-color','');});
        $("#to_date").change(function(){$(this).css('border-color','');});
    });

</script>                