<?=$this->include("layout_mobi/header");?>
<style type="text/css">
.error
{
    color:red ;
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"><b>Record your visiting reports</b></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-horizontal"  name="form" id="form" method="post" enctype="multipart/form-data" >
							<div class="col-sm-12">
								<div class="form-group" >
									<div class="col-sm-6">
										<center><b><h4 style="color:red;">
											<?php
											if(!empty($err_msg)){
												echo $err_msg;
											}
											?>
											</h4>
											</b>
                                        </center>
									</div>
								</div>
								
								<div class="form-group">
                                    <div class="col-sm-4">
                                        <select name="moduleId" id="moduleId" class="form-control" onchange="getRemarks()" required>
                                            <option value="">Select Module</option>
                                            <option value="1" <?=isset($moduleId) && $moduleId =="1"?"selected":""?>>SAF</option>
                                            <option value="2" <?=isset($moduleId) && $moduleId =="2"?"selected":""?>>PROPERTY</option>
                                            <option value="3" <?=isset($moduleId) && $moduleId =="3"?"selected":""?>>WATER CONSUMER</option>
                                            <option value="4" <?=isset($moduleId) && $moduleId =="5"?"selected":""?>>TRADE LICENSE</option>
                                        </select>
                                    </div>
									<label class="col-sm-2 control-label" for="ref_no">Reference Number<span class="text-danger">*</span></label>
									<div class="col-sm-4">
										<input type="text" placeholder="Enter Module Reference Number" id="ref_no" name="ref_no" class="form-control" value="" onblur="validateRefNo(this.value)" required/>
                                        <input type="hidden" id="ref_id" name="ref_id" value="" required/>
                                    </div>                                    
                                    <label class="col-sm-2 control-label" for="remarks_id">Remarks<span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
										<select class="form-control" name="remarks_id" id="remarks_id" onchange="openOtherRemarks()" required>
                                            <option value="">Please Select</option>
                                            <?php
                                                if(isset($remarks) && $remarks)
                                                {                                                    
                                                    foreach($remarks as $val)
                                                    {                                                          
                                                        ?>
                                                        <option value="<?=$val['id'];?>" <?= ((isset($remarks_id) || isset($_POST["remarks_id"])) && (($remarks_id??$_POST["remarks_id"])==$val["id"]) )? "selected":"";?> > <?=$val["remarks"];?> </option>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </select>
									</div>
                                    <div class="other_remark">
                                        <label class="col-sm-2 control-label" for="other_remark"> Other Remarks<span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <textarea class="form-control" name="other_remark" id="other_remark"><?=isset($other_remark)?$other_remark:"";?></textarea>                                                
                                        </div>

                                    </div>
									
								</div>
								
								<!-- <div class="form-group">
									<label class="col-sm-2 control-label" for="designf">Enter overview responce <span class="text-danger">*</span></label>
									<div class="col-sm-4">
										 <textarea type="text" placeholder="Enter overview responce" id="responce" name="responce" class="form-control"></textarea>
									</div>
								</div> -->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="designd">&nbsp;</label>
									<div class="col-sm-4">
										<button class="btn btn-success" id="btndesign" style="width:100%;" name="btndesign" type="submit" onclick="validateData()">Submit</button>
									</div>
								</div>
							</div>
							
							<?php if(isset($validation)){ ?>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>


<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">
    function validateData()
    {
        if($("#ref_id").val()=="")
        {
            return false;
        }
    }
    function openOtherRemarks()
    {
        var text = $("#remarks_id option:selected").text();
        console.log(((text).trim()).toLowerCase()=='other');
        if(((text).trim()).toLowerCase()=='other')
        {
            $(".other_remark").show();
            $("#other_remark").attr("required",true);
        }
        else{
            $(".other_remark").hide();
            $("#other_remark").attr("required",false);
        }
            
    }

    function validateRefNo(val)
    {
        // alert(val);
        if(val.trim()!="")
        {
            $.ajax({
                "type": "POST",
                "url": "<?=base_url()."/visiting_dtl/validateRefNo"?>",                       
                "dataType": "json" ,
                "data": {
                        refno : $('#ref_no').val(),
                        moduleId : $('#moduleId').val()
                    }, 
                beforeSend: function() {
                        $("#btndesign").html("LOADING ...");
                        $("#btndesign").attr("type","button");
                        $("#loadingDiv").show();
                    },            
                complete: function(){
                    $("#loadingDiv").hide();
                    $("#btndesign").html("Submit");
                    $("#btndesign").attr("type","submit");               
                },
                'success': function(response) {
                    // Here the response               
                    // console.log(response);
                    if(response.status)
                    {
                        $("#btn_search").val("SEARCH");
                        $("#loadingDiv").hide();
                        $('#ref_id').val(response.id);
                    }
                    else{
                        alert("Data Not Found");
                        $("#loadingDiv").hide();
                        $('#ref_id').val("");
                        $('#ref_no').val("");
                    }
                },
                error: function(xhr, status, error) {
                        // Handle the error
                        console.error(error);
                        $("#loadingDiv").hide();
                    }
            });
        }
    }

    function getRemarks()
    {
        validateRefNo($("#ref_no").val());
        var moduleId = $("#moduleId").val();
        if(moduleId)
        {
            $.ajax({
                "type": "POST",
                "url": "<?=base_url()."/visiting_dtl/getRemarks"?>",                       
                "dataType": "json" ,
                "data": {
                        moduleId : $('#moduleId').val()
                    }, 
                beforeSend: function() {
                        $("#btndesign").html("LOADING ...");
                        $("#btndesign").attr("type","button");
                        $("#loadingDiv").show();
                    },            
                complete: function(){
                    $("#loadingDiv").hide();   
                    $("#btndesign").html("Submit"); 
                    $("#btndesign").attr("type","submit");            
                },
                'success': function(response) {
                    // Here the response   
                    if(response.status)
                    {
                        $("#remarks_id").html(response.remarks);
                        $("#loadingDiv").hide();
                    }
                },
                error: function(xhr, status, error) {
                        // Handle the error
                        console.error(error);
                        $("#loadingDiv").hide();
                    }
            });
        }
    }

    $(document).ready( function () {        

        $('#form').validate({ 
            rules: {
                "moduleId":"required",
                "ref_no":"required",
                "ref_id":"required",
                "remarks_id":"required",
                "other_remark":"required",  
                
            },
        });

        $("#module").change(function() {
            var module = $("#module").val();
            if (module == 'Property') {
                $("#typeProperty").show();
                $("#typeWater").hide();
                $("#typeTrade").hide();
            }
            else if (module == 'Water') {
                $("#typeProperty").hide();
                $("#typeWater").show();
                $("#typeTrade").hide();
            }
            else if (module == 'Trade') {
                $("#typeProperty").hide();
                $("#typeWater").hide();
                $("#typeTrade").show();
            }
        });
        
        $("#btndesign").click(function() {
            var process = true;
            var module = $("#module").val();
            if (module == '') {
                $("#module").css({"border-color":"red"});
                $("#module").focus();
                process = false;
            }
            var type = $("#type").val();
            if (type == '') {
                $("#type").css({"border-color":"red"});
                $("#type").focus();
                process = false;
            }
            var type_no = $("#type_no").val();
            if (type_no == '') {
                $("#type_no").css({"border-color":"red"});
                $("#type_no").focus();
                process = false;
            }
            // var responce = $("#responce").val();
            //     if (responce == '') {
            //     $("#responce").css({"border-color":"red"});
            //     $("#responce").focus();
            //     process = false;
            // }
            // if(responce!="")
            // {
            //     if(responce.length < 50) {
            //     $("#responce").css({"border-color":"red"});
            //     $('#responce').focus();
            //     return false;
            //     }
            // }
            var photo = $("#photo").val();
            if (photo == '') {
                $("#photo").css({"border-color":"red"});
                $("#photo").focus();
                process = false;
            }

            return process;
        });
        $("#module").change(function(){$(this).css('border-color','');});
        $("#type").keyup(function(){$(this).css('border-color','');});
        $("#type_no").keyup(function(){$(this).css('border-color','');});
        $("#responce").keyup(function(){$(this).css('border-color','');});
        $("#photo").keyup(function(){$(this).css('border-color','');});

        <?php
        if(isset($moduleId))
        {
            echo("getRemarks()");
        }
        ?>
        openOtherRemarks();
        validateRefNo($("#ref_no").val());

    });
</script>