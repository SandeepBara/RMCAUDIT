<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Form Receive Preview <a href="<?php echo base_url('safdistribution/receive_form_search') ?>" class="btn btn-danger" style="float:right;"> Back </a></h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-sm-2">SAF No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['saf_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Ward No:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $ward['ward_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Owner Name:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['owner_name'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Phone No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['phone_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Owner Address:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?php echo $form['owner_address'] ?></b>
                        </div>
                    </div>
                </div>
            </div>
            <!-------Transfer Mode-------->
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Transfer Mode</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div>
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Transfer Mode</th>
                                                <th>Document Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tr_tbody">
                                    <?php
                                    if(isset($transfer_mode)):
                                          if(empty($transfer_mode)):
                                    ?>
                                            <tr>
                                                <td colspan="2" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($transfer_mode as $value):
                                            $i++;
                                            //print_r($value['doc_name']);
                                    ?>
                                            <tr>
                                                <td> <?=$value["transfer_mode"];?></td>
                                                <td> <?=$value["doc_name"];?></td>

                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                        </div>
                </div>
            </div>
            <!-------Propery Type-------->
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Type</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div>
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Property Type</th>
                                                <th>Document Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(isset($property_type)):
                                          if(empty($property_type)):
                                    ?>
                                            <tr>
                                                <td colspan="3" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($property_type as $value):
                                            $i++;
                                            //print_r($value['doc_name']);
                                    ?>
                                            <tr>
                                                <td> <?=$value["property_type"];?></td>
                                                <td> <?=$value["doc_name"];?></td>
                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                        </div>
                </div>
            </div>
        <!-------Others-------->
            <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Others</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div>
                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Others Documents</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                    <?php
                                    if(isset($other_doc)):
                                          if(empty($other_doc)):
                                    ?>
                                            <tr>
                                                <td colspan="3" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($other_doc as $value):
                                            $i++;
                                            //print_r($value['doc_name']);
                                    ?>
                                            <tr>
                                                <td> <?=$value["doc_name"];?></td>
                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                        </div>
                </div>
            </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>