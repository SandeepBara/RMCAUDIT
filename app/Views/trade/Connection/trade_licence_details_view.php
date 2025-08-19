<?= $this->include('layout_vertical/header');?>



<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">

    <!--Page content-->   
    <div id="page-content">
        <form id="formname" name="form" method="post" >
                <?php if(isset($validation)){ ?>
                    <?= $validation->listErrors(); ?>
                <?php } ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Licence Details </h3>
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-bordered panel-dark" style="padding: 10px;">
                            <span class="text text-center" style="font-weight: bold; font-size: 23px; text-align: center; color: black; font-family: font-family Verdana, Arial, Helvetica, sans-serif
    Verdana, Arial, Helvetica, sans-serif;">
                                Your applied application no. is 
                                    <span style="color: #ff6a00"><?php echo $licencee['application_no'];?></span>. 
                                    You can use this application no. for future reference.
                            </span>
                            <br>
                            <br>
                            <div style="font-weight: bold; font-size: 20px; text-align:center; color:#0033CC">
                                Current Status : <span style="color:#009900"> <?php echo $application_status;?></span>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Application Type <span class="text-danger">*</span></label>
                            <div class="col-md-3 control-label text-semibold">
                                <?php if($licencee['application_type_id']==1)
                                {
                                    echo 'NEW LICENCE' ;
                                }
                                elseif($licencee['application_type_id']==2)
                                {
                                    echo 'RENEW' ;
                                }
                                elseif($licencee['application_type_id']==3)
                                {
                                    echo 'AMENDMENT' ;
                                }
                                else
                                {
                                    echo 'SURRENDER' ;
                                } ?>
                            </div>
                            <label class="col-md-3">Firm Type <span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm text-bold"><?php echo $firm_type['firm_type'];?>  <?php if($licencee['firm_type_id'] == 5) {echo "- " .$licencee['otherfirmtype'];} ?>                               
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Type of Ownership of Business Premises<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm text-bold">
                            <?php echo $ownershiptype['ownership_type'];?>
                            </div>

                            <label class="col-md-3">Category Type<span class="text-danger">*</span></label>
                            <div class="col-md-3 pad-btm text-bold">
                            <?php echo $categoryTypeDetails['category_type'];?>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-md-3">Licence No.</label>
                            <div class="col-md-3 pad-btm text-bold">
                            <?php echo $licencee['license_no'];?>
                            </div>
                        </div>
                    </div>
                </div>


            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Holding / SAF No. <span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm text-bold"><?php echo $holding_no;?>
                       </div>
                        <label class="col-md-3">Ward No. <span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm text-bold">
                        <?php echo $ward_no;?>                      
                       </div>
                    </div>   
                  
                    <div class="row">
                        <label class="col-md-3">Firm Name<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm text-bold">
                        <?php echo $licencee['firm_name'];?>
                       </div>
                       <label class="col-md-3">Total Area(in Sq. Ft) <span class="text-danger">*</span></label>
                       <?php echo $licencee['area_in_sqft'];?>
                    </div>

                    <div class="row">
                        <label class="col-md-3">Firm Establishment Date<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php echo date_format(date_create($licencee['establishment_date']),"d-m-Y");?>
                            
                        </div>

                        <label class="col-md-3">Licence For<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php if(isset($licencee['licence_for_years'])){echo $licencee['licence_for_years'].' YEARS';}?>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-3">Address<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?php echo $licencee['address'];?>
                        </div>

                        <label class="col-md-3">Landmark</label>
                        <div class="col-md-3 pad-btm text-bold">
                        <?=$licencee['landmark'];?>
                        </div>
                    </div>

                  <div class="row">
                  <label class="col-md-3">Pin<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm text-bold">
                           <?php echo $licencee['pin_code'];?>
                       </div>

                       <label class="col-md-3">New Ward No.<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm text-bold">
                           <?php echo $new_ward_no['ward_no'];?>
                       </div>
                  </div>  
                  
                  <div class="row">
                  <label class="col-md-3">Owner of Business Premises :<span class="text-danger">*</span></label>
                       <div class="col-md-3 pad-btm text-bold">
                           <?php echo $licencee['premises_owner_name'];?>
                       </div>
                  </div> 
                  <div class="row">
                  <label class="col-md-3">Business Description :<span class="text-danger"></span></label>
                       <div class="col-md-3 pad-btm text-bold">
                           <?=isset($licencee['brife_desp_firm'])?$licencee['brife_desp_firm']:'N/A';?>
                       </div>
                  </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name <span class="text-danger">*</span></th>
                                            <th>Guardian Name</th>                                            
                                            <th>Mobile No <span class="text-danger">*</span></th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                      
                                <?php 
                                
                               if(isset($firm_owner)){
                                if(!empty($firm_owner)){
                                  foreach ($firm_owner as  $value) {                            
                                ?>
                                        <tr>
                                            <td><?=$value["owner_name"];?></td>        
                                            <td><?=$value["guardian_name"]?></td>
                                            <td><?=$value["mobile"]?></td>
                                            <td><?=$value["address"]?$value["address"]:"N/A"?></td>                                            
                                        </tr>
                                  <?php }
                                }
                              } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
           <?php if($licencee['application_type_id']!=4){?>
           <div class="panel panel-bordered panel-dark" >
                <div class="panel-heading">
                    <h3 class="panel-title">Items of Trade</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Trade Code</th>
                                            <th>Trade Item</th>                                       
                                        </tr>
                                    </thead>
                                    
                                <?php 
                                
                               if(isset($trade_items)){
                                if(!empty($trade_items)){
                                  foreach ($trade_items as  $valueitem) {                            
                                ?>
                                        <tr>                                            
                                            <td><?=$valueitem["trade_code"]?></td>
                                            
                                            <td><?=$valueitem["trade_item"]?></td>
                                        </tr>
                                  <?php }
                                }
                              } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
           

           

            <?php if($licencee['application_type_id']!=4){?> 
            <div class="panel panel-bordered panel-dark">
               <div class="panel-heading">
                    <h3 class="panel-title">Transaction Details</h3>
                </div>
                <?php 
                unset($_SESSION['url']);
                $_SESSION["url"]=$_SERVER['REQUEST_URI'];
                ?>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Transaction Date</th>
                                            <th>Transaction No.</th>
                                            <th>Mode</th>    
                                            <th>Amount</th>  
                                            <th>View</th>                                   
                                        </tr>
                                    </thead>
                                    
                                <?php 
                                
                               if(isset($trans_detail)){
                                if(!empty($trans_detail)){
                                  foreach ($trans_detail as  $valuetran) {                            
                                ?>
                                        <tr>                                            
                                            <td><?=$valuetran["transaction_date"]?></td>
                                            
                                            <td><?=$valuetran["transaction_no"]?></td>
                                            <td><?=$valuetran["payment_mode"]?></td>
                                            
                                            <td><?=$valuetran["paid_amount"]?></td>
                                            <td><a href="<?php echo base_url('tradeapplylicence/view_transaction_receipt/'.md5($valuetran['related_id']).'/'.md5($valuetran['id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
                                        </tr>
                                  <?php }
                                }
                              } ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            
            <div class="panel">
            <div class="panel-body text-center">
            <?php 
            
            // Approved && surrender
            if($licencee['pending_status']==5 && $licencee['application_type_id']!=4)
            {
                ?>
                <a href="<?=base_url('Trade_DA/municipal_licence/'.$linkId);?>" class="btn btn-primary"> Trade License </a>
                </div>
                <?php 
            }
            // NotApproved && surrender
            elseif($licencee['pending_status']!=5 && $licencee['application_type_id']!=4)
            {
                ?>
                
                <a href="<?=base_url('tradeapplylicence/provisional/'.$linkId);?>" class="btn btn-primary"> Provisional License </a>
                <?php 
            }
            ?>
            </div>
            </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
