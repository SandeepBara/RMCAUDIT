
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
background-color:#25476a;
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
<!--DataTables [ OPTIONAL ]-->
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Property</a></li>
					<li class="active">Search Property</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>



<!-- ======= Cta Section ======= -->

<div id="page-content">


	<div class="row">
		<div class="col-sm-12">
			<div class="panel">
				<div class="panel-heading">
					<h5 class="panel-title">Search Property</h5>
				</div>
                <div class="panel-body">
                    <form action="<?php echo base_url('mobisafDemandPayment/saf_Property_Tax');?>" method="post" role="form" class="form-horizontal">
                        <div class="col-md-7">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="new_ward">Select Ward No.<span class="text-danger">*</span></label>
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
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="saf_no">Enter Application No.<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" id="saf_no" name="saf_no" class="form-control" value="" placeholder="Enter Application No.">
                                    </div>
                                </div>
                            </div>
                            
							<div class="col-md-4"></div>
							<div class="col-md-6"><button class="buttonA buttonx" type="submit">Search</button></div>
                        </div>
                    </form>
					
                </div>
            </div>
        </div>
    </div>
        </div><br><br><!-- End Contact Section -->

  <?=$this->include("layout_mobi/footer");?>

