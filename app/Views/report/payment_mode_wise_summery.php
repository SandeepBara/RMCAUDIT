<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->

<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint no-print">
            <div class="panel-heading">
                <h5 class="panel-title">Payment Mode Wise Collection Summery</h5>
            </div>
            <div class="panel-body">
                <form method="GET" action="">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold">From Date</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?=($from_date??""=="")?date('Y-m-d'):$from_date;?>" />
                            </div>
                            <label class="col-md-2 text-bold">Upto Date</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=($upto_date??""=="")?date('Y-m-d'):$upto_date;?>" />
                            </div>
                            <div class="col-md-2 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-dark <?=!isset($result)?"hidden":""?> panel-bordered">
            <div class="panel-heading">
                <div class="panel-control">
                    <button class="btn btn-mint btn-icon" onclick="print();"><i class="demo-pli-printer icon-lg"></i></button>
                </div>
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <?php
                    $session = session();
                    $ulb_dtl = $session->get("ulb_dtl");
                    $totalCash = 0;
                    $totalCheque = 0;
                    $totalDD = 0;
                    $totalOnline = 0;
                    $totalCard = 0;
                    $totalNeft = 0;
                    $totalRtgs = 0;
                    $totalUpi = 0;
                    $totalCollection = 0;
                    if (isset($result)) {
                        foreach ($result AS $key => $resultData) {
                            $totalCash += $resultData["cash"];
                            $totalCheque += $resultData["cheque"];
                            $totalDD += $resultData["dd"];
                            $totalOnline += $resultData["online"];
                            $totalCard += $resultData["card"];
                            $totalNeft += $resultData["neft"];
                            $totalRtgs += $resultData["rtgs"];
                            $totalUpi += $resultData["upi"];
                        }
                    }
                    $totalCollection = $totalCash+$totalCheque+$totalDD+$totalOnline+$totalCard+$totalNeft+$totalRtgs+$totalUpi;
                ?>
                <div class="row">
                    <div class="col-sm-12 text-center text-2x text-bold mar-btm">
                        <u><?=$ulb_dtl["ulb_name"];?></u>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center text-bold mar-btm">
                        Collection Report From <?=$from_date??date('Y-m-d');?> TO <?=$upto_date??date('Y-m-d');?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center  text-2x text-bold mar-btm">
                        Total Collection: <?=$totalCollection;?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered text-sm text-bold">
                            <tr>
                                <td>Total Cash Collection: <?=$totalCash;?></td>
                                <td>Total Cheque Collection: <?=$totalCheque;?></td>
                            </tr>
                            <tr>
                                <td>Total DD Collection: <?=$totalDD;?></td>
                                <td>Total Card Collection: <?=$totalCard;?></td>
                            </tr>
                            <tr>
                                <td>Total Online Collection: <?=$totalOnline;?></td>
								<td>Total Neft Collection: <?=$totalNeft;?></td>
                            </tr>
                            <tr>
                                <td>Total Rtgs Collection: <?=$totalRtgs;?></td>
                                <td>Total UPI Collection: <?=$totalUpi;?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php 
                    if (isset($result)) {
                        foreach ($result AS $resultData) {
                        $headerTitle = "";
                        if ($resultData["mode_type"]) {
                            $headerTitle = "Holding Collection Description";
                        } else if ($resultData["mode_type"]) {
                            $headerTitle = "SAF Collection Description";
                        } else if ($resultData["mode_type"]) {
                            $headerTitle = "Water Collection Description";
                        } else if ($resultData["mode_type"]) {
                            $headerTitle = "Trade Collection Description";
                        }
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered text-sm">
                            <thead>
                                <tr>
                                    <th colspan="3"><?=$resultData["mode_type"];?> Collection Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Description</td>
                                    <td>Holding</td>
                                    <td>Transaction</td>
                                </tr>
                                <tr>
                                    <td>Cash Payment</td>
                                    <td><?=$resultData["cash_count"]?></td>
                                    <td><?=$resultData["cash"]?></td>
                                </tr>
                                <tr>
                                    <td>Cheque Payment</td>
                                    <td><?=$resultData["cheque_count"]?></td>
                                    <td><?=$resultData["cheque"]?></td>
                                </tr>
                                <tr>
                                    <td>DD Payment</td>
                                    <td><?=$resultData["dd_count"]?></td>
                                    <td><?=$resultData["dd"]?></td>
                                </tr>
                                <tr>
                                    <td>Online Payment</td>
                                    <td><?=$resultData["online_count"]?></td>
                                    <td><?=$resultData["online"]?></td>
                                </tr>
                                <tr>
                                    <td>Card Payment</td>
                                    <td><?=$resultData["card_count"]?></td>
                                    <td><?=$resultData["card"]?></td>
                                </tr>
                                <tr>
                                    <td>Neft Payment</td>
                                    <td><?=$resultData["neft_count"]?></td>
                                    <td><?=$resultData["neft"]?></td>
                                </tr>
                                <tr>
                                    <td>Rtgs Payment</td>
                                    <td><?=$resultData["rtgs_count"]?></td>
                                    <td><?=$resultData["rtgs"]?></td>
                                </tr>
                                <tr>
                                    <td>UPI Payment</td>
                                    <td><?=$resultData["upi_count"]?></td>
                                    <td><?=$resultData["upi"]?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th><?=$resultData["cash_count"]+$resultData["cheque_count"]+$resultData["dd_count"]+$resultData["online_count"]+$resultData["card_count"]+$resultData["neft_count"]+$resultData["upi_count"];?></th>
                                    <th><?=$resultData["cash"]+$resultData["cheque"]+$resultData["dd"]+$resultData["online"]+$resultData["card"]+$resultData["neft"]+$resultData["upi"];?></th>
                                </tr>
                            <tfoot>
                        </table>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>

