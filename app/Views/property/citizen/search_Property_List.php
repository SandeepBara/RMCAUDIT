<?= $this->include('layout_horizontal/header');?>

<!-- ======= Hero Section ======= -->

<section id="cta" class="cta">
    <img src="<?=base_url();?>/public/assets/img/gallery/thumbs/swatch-bharat2.jpg" alt="Los Angeles" width="100%" height="300px" style="margin-top: 73px;">
</section><!-- End Cta Section -->

<!-- ======= Cta Section ======= -->

<section id="contact" class="contact" style="margin-top: -205px;">
    <div class="container">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align:left;"><h5><b>Search Property List	
                    <a href="" style="float:right;color:#000;">Back</a></b></h5></div>
                <div class="panel-body">
				
                    <form action="<?php echo base_url('Home/citizen_due_details');?>" method="post" role="form" class="php-email-form">
					
                        <div class="col-md-12" style="font-size:14px;">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="exampleInputEmail1">Old Ward No. </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="asset" name="asset" class="form-control m-t-xxs">
                                            <option value="">Select</option>
                                            <?php if($ward): ?>
                                            <?php foreach($ward as $post): ?>
                                            <option value="<?php echo $post['id']; ?>"><?php echo $post['ward_no']; ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <p style="text-align:center;">OR</p>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">New Ward No.</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="asset" name="asset" class="form-control m-t-xxs">
                                            <option value="">Select</option>
                                            <?php if($ward): ?>
                                            <?php foreach($ward as $post): ?>
                                            <option value="<?php echo $post['id']; ?>"><?php echo $post['ward_no']; ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <p style="text-align:center;">AND</p>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="exampleInputEmail1">Enter Holding No. <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="old_holding" name="old_holding" class="form-control" style="height:38px;" placeholder="Enter Old Holding No." value="">
                                    </div>
                                </div>
                                <p style="text-align:center;">OR</p>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">15 Digit Unique House No. <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="new_holding" name="new_holding" class="form-control" value="" style="height:38px;" placeholder="15 Digit Unique House No.">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-12" style="text-align: center;">
                            <input type="submit" name="search_consumer_parameter" id="search_consumer_parameterr" class="btn btn-primary" style="margin-top:0px;border-radius: 5px;" value="Search Property List">
                        </div>

                    </form>
                    <form action="" method="post" role="form" class="php-email-form">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style="font-family:Arial, Helvetica, sans-serif;">
                                <tbody><tr>
                                    <th colspan="8">Consumer List : </th>
                                    </tr>
                                    <tr style="background-color:#e2b4b4;">
                                        <th>Sl No. </th>
                                        <th>Ward No </th>
                                        <th>Holding No </th>
                                        <th>Owner(s) Name </th>
                                        <th>Address </th>
                                        <th>Khata No. </th>
                                        <th>Plot No. </th>
                                        <th>Action </th>
                                    </tr>
                                    <tr>
                                        <?php if($emp_details):
                                        $i=1;  ?>
                                        <?php foreach($emp_details as $post): ?>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $post['ward_no']; ?></td>
                                        <td><?php echo $post['holding_no']; ?></td>
                                        <td><?php echo $post['owner_name']; ?></td>
                                        <td><?php echo $post['prop_address']; ?></td>
                                        <td><?php echo $post['khata_no']; ?></td>
                                        <td><?php echo $post['plot_no']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('Home/citizen_due_details/'.$post['prop_dtl_id']);?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
                                        <?php endforeach; ?>
                                            <?php endif; ?>
                                    </tr>
                                </tbody></table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section><br><br><!-- End Contact Section -->

<?= $this->include('layout_horizontal/footer');?>