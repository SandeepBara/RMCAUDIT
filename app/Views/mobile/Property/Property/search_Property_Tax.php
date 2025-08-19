<?=$this->include("layout_mobi/header");?>
<style>
		.buttonA {
  border: none;
  color: white;
  padding: 10px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
}
	.buttonx{
width:250px;
height:45px;
border:none;
outline:none;
box-shadow:-4px 4px 5px 0 #46403a;
color:#fff;
font-size:14px;
text-shadow:0 1px rgba(0,0,0,0.4);
background-color:#31a69a;
border-radius:3px;
font-weight:700
}
.buttonx:hover{
background-color:#FF8000;
color:#fff;
cursor:pointer
}
.buttonx:active{
margin-left:-4px;
margin-bottom:-4px;
padding-top:2px;
box-shadow:none
}


</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-mint">
                <div class="panel-heading">
                    <h3 class="panel-title">Search Property </h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="col-md-12">
                    <form action="<?php echo base_url('mobi/search_Property_Tax');?>" method="post">
                        <div class="col-md-12" style="font-size:12px;">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="exampleInputEmail1">Ward No. </label>
                                </div>
                                <div class="col-md-3">
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
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="exampleInputEmail1">Enter Holding No. <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="holding_no" name="holding_no" class="form-control" placeholder="Enter Holding No." value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel">
							<div class="panel-body text-center">
								<input type="submit" name="search_consumer_parameter" id="search_consumer_parameterr" class="buttonA buttonx" value="Search Property ">
							</div>
                        </div>

                    </form>
					</div>
					
                </div>
            </div>
            
            
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>