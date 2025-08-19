<?= $this->include("layout_mobi/header"); ?>
<div id="content-container">
    <div id="page-content">
        <div class="panel panel-bordered panel-mint">
            <div class="panel-heading ">
                <strong class='panel-title'>Demand Receipt </strong>
                <a href="<?= base_url().'/WaterViewConsumerMobile/view/'.$consumer_dtl['id'];?>" class="btn btn-dark pull-right">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>Back
                </a>
                
                
            </div>
        </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel" style="border: #39a9b0 solid 2px;">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="panel">
                            <div id="blutooth_printable_area">
                                <?php
                                //$title='SOLID WASTE MANAGEMENT';
                                $ulb_name = $ulb_mstr_name["ulb_name"]??null;

                                ?>

                                <center><b><?= $ulb_name; ?></b></center><br />

                                <center>
                                    Water Consumer Demand Receipt
                                </center><br />
                                <center>------------------------------------------------</center><br />
                                Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; <?= date("d-m-Y h:i:s A"); ?><br />
                                TC Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <?php echo $emp_dtls["emp_name"]??null; ?><br />
                                Mobile No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; <?php echo $emp_dtls["personal_phone_no"]??null; ?><br />
                                Consumer No. &nbsp;&nbsp; :&nbsp;&nbsp;<?= $consumer_dtl["consumer_no"]??null; ?> <br />
                                Ward No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<?= $consumer_dtl["ward_no"]??null; ?> <br />
                                Citizen Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<?= $consumer_dtl["owner_name"]??null; ?> <br />                              

                                <center>------------------------------------------------</center><br />

                                Demand Period. &nbsp;&nbsp; :&nbsp;&nbsp;<?= $demand_dtl["demand_from"]??null; ?> to <?= $demand_dtl["demand_upto"]??null; ?><br />
                                Demand Amount &nbsp;&nbsp; :&nbsp;&nbsp;<?= number_format(($demand_dtl["amount"]??0),2,'.',','); ?><br />
                                Penalty &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; :&nbsp;&nbsp;<?= number_format(($demand_dtl["penalty"]??0),2,'.',','); ?><br />

                                Amount Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp; <?= number_format((($demand_dtl["amount"]??0)+($demand_dtl["penalty"])),2,'.',','); ?><br />
                                <?php 
                                    $type_of_connection = "";
                                    $connectio_date = isset($current_meter_status['connection_date'])&&!empty($current_meter_status['connection_date'])?date('d-m-Y',strtotime($current_meter_status['connection_date'])):'N/A';
                                    if($current_meter_status)
                                    {
                                        if(isset($current_meter_status["connection_type"]) && $current_meter_status["connection_type"]==3)
                                        {
                                            $type_of_connection = "Fixed";
                                        }
                                        elseif(isset($current_meter_status["connection_type"]) && $current_meter_status["connection_type"]==2)
                                        {
                                            $type_of_connection = "Gallon";
                                        }
                                        else
                                        {
                                            $type_of_connection = "Meter";
                                        }
                                    }
                                ?>
                                Connection Type &nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<?= $type_of_connection; ?><br />
                                Connection Date &nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<?=$connectio_date ; ?><br />
                                <?php 
                                if ($type_of_connection=='Meter') 
                                { ?>
                                    Current Reading &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<?= $meter_last_reading["initial_reading"]??0.00; ?><br />
                                    Previous Reading &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<?= $priv_meter_reading["initial_reading"]??0.00; ?><br />
                                    Meter No. &nbsp;&nbsp; &nbsp;&nbsp; :&nbsp;&nbsp;<?= $current_meter_status["meter_no"]??null; ?><br />
                                    <?php 
                                } ?>
                                <center>------------------------------------------------</center><br />



                                <?php

                                $txt = "";                                                             
                                $txt .= "<nc>Water Consumer Demand Receipt</nc><br />";
                                $txt .= "<n>-----------------------------------------</n><br />";
                                $txt .= "<n>Date           :  " .date("d-m-Y h:i:s A")."</n><br />";
                                $txt .= "<n>TC Name        :  " . $emp_dtls["emp_name"]."</n><br />";
                                $txt .= "<n>Mobile No.     :  " . $emp_dtls["personal_phone_no"]. "</n><br />";
                                $txt .= "<n>Consumer No.   :  " . $consumer_dtl["consumer_no"]. "</n><br />";
                                $txt .= "<n>Ward No.       :  " . $consumer_dtl["ward_no"]. "</n><br />";
                                $txt .= "<n>Citizen Name   :  " . $consumer_dtl["owner_name"]. "</n><br />";
                                $txt .= "<n>-----------------------------------------</n><br />";
                                $txt .= "<n>Demand Period.  :  " . $demand_dtl["demand_from"].' to '.$demand_dtl["demand_upto"] . "</n><br />";
                                $txt .= "<n>Demand Amount   :  " . number_format($demand_dtl["amount"],2,'.',',') . "</n><br />";
                                $txt .= "<n>Penalty         :  " . number_format($demand_dtl["penalty"],2,'.',',') . "</n><br />";
                                $txt .= "<n>Amount Paid     :  " . number_format((($demand_dtl["amount"])+($demand_dtl["penalty"])),2,'.',',') . "</n><br />";

                                $txt .= "<n>Connection Type :  " . $type_of_connection . "</n><br />";
                                //$txt .= "<n>Connection Date :  " . $connectio_date . "</n><br />";
                                if ($type_of_connection=='Meter') 
                                { 
                                    $txt .= "<n>Current Reading     :  " . $meter_last_reading["initial_reading"]. "</n><br />";
                                    $txt .= "<n>Previous Reading	   :  " . $priv_meter_reading["initial_reading"]. "</n><br />";
                                    $txt .= "<n>Meter No.    :  " . $current_meter_status["meter_no"]. "</n><br />";                                      
                                }                                
                                $txt .= "<n>------------------------------------------</n><br />";                                
                                $txt .= "<n>Please keep this Bill For Future Reference</n><br />";
                                $txt .= "<n>Toll Free No. 18008904665</n><br />";
                                $txt .= "<n></n><br />";
                                $txt .= "<n></n><br />";
                                $txt .= "<n></n><br />";
                                $txt .= "<n></n><br />";
                                $txt .= "<n></n><br />";




                                for ($i = 1; $i <= 1; $i++) {
                                    $txt1 = NULL;
                                    $txt1 = $txt;
                                    if ($i == 1) {
                                        $copyCT = '<bc>' . $ulb_name . '</bc><br />' . '<nc>Citizen Copy</nc>';
                                        $copyTC = '<bc>' . $ulb_name . '</bc><br />' . '<nc>TC Copy</nc>';
                                        $copyPT = '<bc>' . $ulb_name . '</bc><br />' . '<nc>ULB Copy</nc>';
                                    }
                                    $txt1 = $copyCT . '<br />' . $txt1 . '<br />' . $copyTC . '<br />' . $txt1 . '<br />' ;//. $copyPT . '<br />' . $txt1
                                    //$txt1.= PHP_EOL. PHP_EOL. PHP_EOL. PHP_EOL;

                                }
                                //print($txt1);
                                ?>

                                <input type="hidden" id="bt_printer" value="<?= $txt1; ?>" />

                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div style="text-align: center;">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function bt_printer() {
        var url = document.getElementById("bt_printer").value;
        AndroidInterface.btPrinter(url);
    }
    bt_printer();
</script>
<?= $this->include("layout_mobi/footer"); ?>