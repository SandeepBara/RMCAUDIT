
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
                <div class="panel-heading" style="text-align:left;"><h5><b>Search Property	
                    <a href="" style="float:right;color:#000;">Back</a></b></h5>
                </div>
                <div class="panel-body">
                    <form action="<?php echo base_url('Home/pay_Property_Tax');?>" method="post" role="form" class="php-email-form">
                        <div class="col-md-7" style="float:left;font-size:14px;line-height:0px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Select Old Ward No. </label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="previous_ward_mstr_id" name="previous_ward_mstr_id" class="form-control m-t-xxs">
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
                            <p style="text-align:center;">OR</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Select New Ward No.</label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
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
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Enter Holding No. <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="holding_no" name="holding_no" class="form-control" value="" style="height:38px;" placeholder="Enter Old Holding No.">
                                    </div>
                                </div>
                            </div>
                            <p style="text-align:center;">OR</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">15 Digit Unique House No. <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="house_no" name="house_no" class="form-control" value="" style="height:38px;" placeholder="15 Digit Unique House No.">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-5" style="float:right;">
                            <img src="<?=base_url();?>/public/assets/img/gallery/thumbs/ss.jpg" alt="Los Angeles" width="100%" height="350px">
                        </div>
                        <div class="text-center"><button type="submit">Search</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </section><br><br><!-- End Contact Section -->

    <?= $this->include('layout_horizontal/footer');?>

