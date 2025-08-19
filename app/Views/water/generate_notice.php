<?= $this->include('layout_vertical/header'); ?>
    <style>
        #data_table_view, #data_table_view th, #data_table_view td {
            border: 1px solid black !important;
            border-collapse: collapse !important;
        }
        #data_table_view td{
            padding : 10px;
        }
        #data_table_view_first_row td{
            font-weight: 600;
        }
    </style>
    <!--CONTENT CONTAINER-->
    <div id="content-container">
        <!--Page content-->
        <div id="page-content" id="divIdPDF">

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Generate Notice
                        <span><?= $consumer_dtl['id']?></span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 pad-btm">Consumer No</div>
                        <div class="col-md-3 pad-btm"><?=$consumer_dtl['consumer_no'] ?? 'NA';?></div>
                        <div class="col-md-3 pad-btm">Category</div>
                        <div class="col-md-3 pad-btm"><?=$consumer_dtl['category'] ?? 'NA';?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pad-btm">Address</div>
                        <div class="col-md-9 pad-btm"><?=$consumer_dtl['address'] ?? 'NA';?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pad-btm">Connection Type</div>

                        <div class="col-md-3 pad-btm">
                            <?php
                            $meter_no="N/A";
                            if(in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                            {
                                $connection_type = "Meter/Fixed";
                                $meter_no=$connection_dtls['meter_no'];
                            }
                            elseif($connection_dtls['connection_type']==1)
                            {
                                $connection_type='Meter';
                                $meter_no=$connection_dtls['meter_no'];
                            }
                            else if($connection_dtls['connection_type']==2)
                            {
                                $connection_type='Gallon';
                            }
                            else
                            {
                                $connection_type='Fixed';
                            }
                            ?>
                            <?php  echo $connection_type;?>
                        </div>
                        <div class="col-md-3 pad-btm">Meter No</div>
                        <div class="col-md-3 pad-btm"><?=$consumer_dtl['meter_no'] ?? 'NA';?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 pad-btm">Owner Name</div>
                        <div class="col-md-3 pad-btm"><?=$consumer_dtl['applicant_name'] ?? 'NA';?></div>
                        <div class="col-md-3 pad-btm">Mobile No</div>
                        <div class="col-md-3 pad-btm"><?=$consumer_dtl['mobile_no'] ?? 'NA';?></div>
                    </div>

                </div>
            </div>

            <?php if($due_summary['balance_amount'] > 0){?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Demand Details
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table id="data_table_view" style="width: 100%; font-weight: 300;margin-top:15px;">
                            <tr id="data_table_view_first_row">
                                <td >
                                    Demand From
                                </td>
                                <td >
                                    Demand Upto
                                </td>
                                <td >
                                    Demand (In Rs.)
                                </td>
                                <td >
                                    Penalty (In Rs.)
                                </td>

                                <td >
                                    Total (In Rs.)
                                </td>
                            </tr>
                            <tr >
                                <td>
                                    <?= $due_summary['demand_from']?>
                                </td>
                                <td>
                                    <?= $due_summary['demand_upto']?>
                                </td>

                                <td >
                                    <?=$due_summary['amount'];?>
                                </td>
                                <td >
                                    <?=$due_summary['penalty'] ?? 'NA';?>
                                </td>

                                <td >
                                    <?=$due_summary['balance_amount'];?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size: 16px; text-align: right;font-weight:600">Total</td>
                                <td style="font-weight:600"><?=$due_summary['balance_amount'];?></td>
                                <td style="font-weight:600"><?=$due_summary['penalty'];?></td>

                                <td style="font-weight:600"><?=$due_summary['balance_amount'];?></td>
                            </tr>

                            <tr style="font-weight:600">
                                <td colspan="2" >Total Payable</td>
                                <td colspan="4" >Rs. <?=round(($due_summary['balance_amount']));?>.00</td>
                            </tr>
                            <tr style="font-weight:600">
                                <td colspan="2" >Total Demand (in words)</td>
                                <td colspan="4" ><?=ucwords(getIndianCurrency(round(($due_summary['balance_amount']))));?> Only.</td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php } ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Notice Form</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Display the error message if available -->
                            <?php if (session()->has('error')) : ?>
                                <p><?= session('error') ?></p>
                            <?php endif; ?>
                            <form method="post" id="form_water_demand" name="form_water_demand" action="">
                                <div class="row">

                                    <div class="col-md-2 col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">Notice Date <span class="text-danger">*</span></label>
                                            <input type="date" id="notice_date" name="notice_date" class="form-control notice_date" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">Notice Type</label>
                                            <select id="notice_type" name="notice_type" class="form-control demand_notice" required="required">
                                                <option value="">SELECT</option>
                                                <option VALUE="Meter">METER</option>
                                                <option VALUE="Non_Meter">NON METER</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-offset-1 col-md-5 col-xs-6">
                                        <div class="form-group">
                                            <label class="col-md-12 control-label" style="margin-top:16px;"></label>
                                            <button type="submit" id="gen_notice" name="gen_notice" class="btn btn-primary">Generate Notice</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br />
                            <br />
                            <br />
                            <br />
                            <?php if(isset($notice_dtl) && !empty($notice_dtl)){ ?>
                                <div class="">
                                    <table class="table table-bordered" with="100%">
                                        <thead>
                                        <tr>
                                            <th>Notice No.</th>
                                            <th>Notice Date</th>
                                            <th>Type</th>
                                            <th>Generate Date</th>
                                            <th>View</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($notice_dtl as $notice){ ?>
                                            <tr>
                                                <td>NOTICE/<?=$notice['notice_no'];?></td>
                                                <td><?=date('d-m-Y', strtotime($notice['notice_date']));?></td>
                                                <td>
                                                    <?= ($notice['notice_type'] == 'Meter') ? 'Meter' : 'Non Meter'; ?> Notice
                                                </td>

                                                <td><?=date('d-m-Y', strtotime($notice['created_on']));?></td>
                                                <td><a onClick="PopupCenter('<?= base_url('WaterViewConsumerDetails/GeneratedNotice/' . md5($notice['id'])); ?>', 'Notice', 1024, 786)" id="customer_view_detail" class="btn btn-primary">View</a></td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End page content-->
    </div>
    <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>