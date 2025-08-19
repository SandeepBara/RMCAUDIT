<?=$this->include('layout_vertical/header');?>

<style type="text/css">
    .error
    {
        color:red ;
    }
</style>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="#" class="active">Consumer Request</a></li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <?php 
                if(isset($validation)){ 
                    ?>
                    <?= $validation->listErrors(); ?>
                    <?php 
                } 
            ?>
            <div class="panel-heading">
                <h3 class="panel-title"> Water Connection Details </h3>
            </div>
            <div class="panel-body">  
                <div class="row">
                    <label class="col-md-2 bolder">Consumer No.</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['consumer_no']??""; ?></b>
                    </div>
                    <label class="col-md-2 bolder">Ward No.</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['ward_no']??""; ?></b>
                    </div>                    
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Type of Connection <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['connection_type']??""; ?></b>
                    </div>
                    <label class="col-md-2 bolder">Category <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['category']??""; ?></b> 
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Property Type <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['property_type']??""; ?></b> 
                    </div>
                        <label class="col-md-2 bolder">Pipeline Type <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['pipeline_type']??""; ?></b> 
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Apply From <span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['apply_from']??""; ?></b> 
                    </div>
                    <label class="col-md-2 bolder">Consumer Connection Date<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=date('d-m-Y',strtotime($consumer_details['created_on']))??"";?></b> 
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Holding No<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <input type="hidden" id= "holding_no" value="<?=$consumer_details['holding_no']??"";?>">
                        <b><?=$consumer_details['holding_no']??""; ?></b> 
                    </div>
                    <label class="col-md-2 bolder">Address<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$consumer_details['address']??""; ?></b> 
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title"><span id="priv_owners" style="display:none;" >Previous</span> Owner Details</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-responsive">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th class="bolder">Owner Name</th>
                            <th class="bolder">Guardian Name</th>
                            <th class="bolder">Mobile No.</th>
                            <th class="bolder">Email ID</th>
                            <th class="bolder">State</th>
                            <th class="bolder">District</th>
                            <th class="bolder">City</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($consumer_owner_details??false){
                            foreach($consumer_owner_details as $val){
                            ?>
                            <tr>
                                <td><?=isset($val['applicant_name'])? $val['applicant_name'] :'';?></td>
                                <td><?=isset($val['father_name'])? $val['father_name'] :'';?></td>
                                <td><?=isset($val['mobile_no'])? $val['mobile_no'] :'';?></td>
                                <td><?=isset($val['email_id'])? $val['email_id'] :'';?></td>
                                <td><?=isset($val['state'])? $val['state'] :'';?></td>
                                <td><?=isset($val['district'])? $val['district'] :'';?></td>
                                <td><?=isset($val['city'])? $val['city'] :'';?></td>
                            </tr>
                            <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class = "panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title" id="priv_owners">Request Form</h3>
            </div>
            <div class="panel-body">
                <form id="form" name="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <label class="col-md-2">Request Type<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <select name="request_type" id="request_type" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                    if($request_type??false){
                                        foreach($request_type as $key=>$val){
                                            ?>
                                            <option value="<?=$val['id'];?>"> <?=$val["request_type"];?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <!-- <label class="col-md-2">Supporting Doc<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="file" class="form-control" id= "doc" name="doc" accept=".jpg,.png,.jpeg,.pdf">
                        </div> -->
                    </div>
                    <!-- ----------------new owner ---------------- -->
                    <div class="panel panel-bordered panel-dark" id = "newOwnerTbl">
                        <div class="panel-heading">
                            <h3 class="panel-title">Applicant Details</h3>
                        </div>                    
                        <div class="panel-body table-responsive">
                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>Owner Name<span class="text-danger">*</span></th>
                                        <th>Guardian Name</th>
                                        <th>Mobile No.<span class="text-danger">*</span></th>
                                        <th>Email ID</th>                                        
                                        <th colspan="2" id="owner_add">Add</th>
                                    </tr>
                                </thead>                              

                                <?php                            
                                if(!isset($owner_name))
                                {
                                    ?>
                                    <tbody id="owner_dtl">
                                        <tr>
                                            <td><input type="text" name="owner_name[]"  id="owner_name1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name"></td>
                                            <td><input type="text" name="guardian_name[]" id="guardian_name1" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name"></td>
                                            <td><input type="text" name="mobile_no[]" id="mobile_no1" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No."></td>
                                            <td><input type="email" name="email_id[]" id="email_id1" class="form-control"  placeholder="Email ID"></td>                                        
                                            <input type="hidden" name="count" id="count" value="1">
                                            <td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
                                        </tr>
                                    </tbody>
                                    <?php
                                }
                                else
                                {
                                    for($i=0; $i < sizeof($owner_name); $i++)
                                    {
                                        ?>
                                        <tbody id="owner_dtl2">
                                            <tr>
                                                <td><input type="text" name="owner_name[]" id="owner_name<?=$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Owner Name" value="<?php echo $owner_name[$i];?>" /></td>
                                                <td><input type="text" name="guardian_name[]" id="guardian_name<?=$i;?>" class="form-control"  onkeypress="return isAlpha(event);"  placeholder="Guardian Name" value="<?php echo $guardian_name[$i];?>" /></td>
                                                <td><input type="text" name="mobile_no[]" id="mobile_no<?=$i;?>" class="form-control" maxlength=10 minlength=10 onkeypress="return isNum(event);"  placeholder="Mobile No." value="<?php echo $mobile_no[$i];?>" /></td>
                                                <td><input type="email" name="email_id[]" id="email_id<?=$i;?>" class="form-control"  placeholder="Email ID" value="<?php echo $email_id[$i];?>" /></td>
                                                
                                                <input type="hidden" name="count" id="count" value="1">
                                                <td onclick="add_owner()" colspan="2"><i class="form-control fa fa-plus-square" style="cursor: pointer;"></i></td>
                                            </tr>

                                        </tbody>
                                        <?php	
                                    }
                                }
                                ?>
                            </table>
                        </div>
                        <div id="owner_append">                            
                        </div>                        
                    </div>
                    <!-- ----------------end new owner ------------ -->

                    <div class="row">  
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" id="submit" name="submit" type="submit">Submit</button>
                            
                        </div>
                        <div class="panel panel-dark" style="display:none;" id="due_notify">
                            <div class="panel-body">
                                <div class="alert alert-info">
                                    <strong>Notice!</strong> Payment is not clear.<span class="text-danger"><?=$balance_amount??0;?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
           
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?php echo $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
    function ownerDivToggle(){
        var request_type = $("#request_type").val();
        validate_holding();
        if(<?=$balance_amount;?> > 0 && request_type==3){
            $("#submit").hide();
            $("#due_notify").show();
        }else{
            $("#submit").show();            
            $("#due_notify").hide();
        }

        if(request_type==1){            
            $("#newOwnerTbl").show();
            $("#priv_owners").show();
        }else{            
            $("#newOwnerTbl").hide();
            $("#priv_owners").hide();
        }
    }

    function add_owner()
    {
        var count=$("#count").val();
        var count=parseInt(count)+1;
        
        $("#count").val(count);

        var tbody = document.getElementById('owner_dtl');
        var tr = document.createElement('tr');
        tr.id='del'+count;
        var td = document.createElement('td');
        var input = document.createElement('input');
        input.classList='form-control';

        var td2 = document.createElement('td');
        var input2 = document.createElement('input');
        input2.classList='form-control';

        var td3 = document.createElement('td');
        var input3 = document.createElement('input');
        input3.classList='form-control';

        var td4 = document.createElement('td');
        var input4 = document.createElement('input');
        input4.classList='form-control';

        var td5 = document.createElement('td');
        var i = document.createElement('i');
        var i2 = document.createElement('i');
        i.classList=' fa fa-plus-square';
        i.style='margin-right:1rem; cursor:pointer';
        i2.classList=' fa fa-window-close';
        i2.style='cursor:pointer';
        i.setAttribute('onclick','add_owner()');
        i2.setAttribute('onclick','delete_owner('+count+')');

        input.name='owner_name[]';
        input.required=true;
        input.id='owner_name'+count;
        input.type='text';        
        input.placeholder='Owner Name';
        input.setAttribute('onkeypress','return isAlpha(event)');
        td.append(input);
        tr.append(td);
        
        input2.name='guardian_name[]';
        input2.id='guardian_name'+count;
        input2.type='text';        
        input2.placeholder='Guardian Name';
        input2.setAttribute('onkeypress','return isAlpha(event)');
        td2.append(input2);
        tr.append(td2);

        input3.name='mobile_no[]';
        input3.id='mobile_no'+count;
        input3.type='text';
        input3.required=true;        
        input3.placeholder='Mobile No.';
        input3.setAttribute('onkeypress','return isNum(event)');
        input3.setAttribute('maxlength','10');
        input3.setAttribute('minlength','10');
        td3.append(input3);
        tr.append(td3);

        input4.name='email_id[]';
        input4.id='email_id'+count;
        input4.type='email';      
        input4.placeholder='Email ID';
        td4.append(input4);
        tr.append(td4);

        
        td5.append(i);
        td5.append(i2);
        tr.append(td5);

       tbody.appendChild(tr);
    }

    function delete_owner(count)
    {
        var count=count;
        var element_id="del"+count;
        $("#del"+count).remove();
    }

    function validate_holding()
    {
		var oldData = $("#owner_dtl").html();
		var holding_no=$("#holding_no").val();        
		if(holding_no && $("#request_type").val()==1)
        {
            $("#owner_dtl").html("");
            $.ajax({
                type:"POST",
                url: '<?php echo base_url("WaterApplyNewConnectionCitizen/validate_holding_no");?>',
                dataType: "json",
                data: {
                        "holding_no":holding_no
                },
				beforeSend: function() {
					$("#loadingDiv").show();
                    $("#submit").attr('disabled', true);
				},
                success:function(data){
					$("#loadingDiv").hide();
                    $("#submit").attr('disabled', false);
                    console.log(data);
                    if (data.response==true){
                        var tbody="";
                        var i=1;
                        var count = $("#count").val();
                        for(var k in data.dd) {                            
                            tbody+="<tr id ='del"+i+"'>";
                            var prop_id=data.dd[k]['id'];
                            var ward_mstr_id=data.dd[k]['ward_mstr_id'];
                            var ward_no=data.dd[k]['ward_no'];
                            var area_sqft=data.dd[k]['total_area_sqft'];
                            var elect_consumer_no=data.dd[k]['elect_consumer_no'];
                            var elect_acc_no=data.dd[k]['elect_acc_no'];
                            var elect_bind_book_no=data.dd[k]['elect_bind_book_no'];
                            var elect_cons_category=data.dd[k]['elect_cons_category'];
                            var prop_pin_code=data.dd[k]['prop_pin_code'];
                            var prop_address=data.dd[k]['prop_address'];
                            tbody+='<td><input type="text" name="owner_name[]"  class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['owner_name']+'"  placeholder="Owner Name" ></td>';

                            tbody+='<td><input type="text" name="guardian_name[]" id="guardian_name" class="form-control" onkeypress="return isAlpha(event);" value="'+data.dd[k]['guardian_name']+'"  placeholder="Guardian Name"></td>';

                            tbody+='<td><input type="text" name="mobile_no[]" id="mobile_no" class="form-control" onkeypress="return isNum(event);" value="'+data.dd[k]['mobile_no']+'"  maxlength=10 minlength=10  placeholder="Mobile No."></td>';

                            tbody+='<td><input type="email" name="email_id[]" id="email_id" class="form-control" " value="'+data.dd[k]['email']+'"  placeholder="Email ID" ></td>';
                            tbody+='<td><i class=" fa fa-plus-square" onclick="add_owner()" style="margin-right: 1rem; cursor: pointer;"></i>'+(i>1 ?'<i class=" fa fa-window-close" onclick="delete_owner('+i+')" style="cursor: pointer;"></i>' :"")+'</td>';
                            if(i==1){
                                tbody+='<input type="hidden" name="count" id="count" value="1">';
                            }
                            tbody+="</tr>";
                            i++;

                        }
                        count +=i;
                        $("#count").val(count);

                        $("#owner_dtl").html(tbody);

                        $("#prop_id").val(prop_id);
                        $("#count").val(i);
                        $("#ward_id").val(ward_mstr_id);
                        $("#ward_id").prop("readonly",true);
                        $("#area_in_sqft").val(area_sqft);                     
                        $("#area_in_sqft").prop("readonly",true);
                        $("#hidden_area_in_sqft").val(area_sqft);                     
                    
                        $("#elec_k_no").val(elect_consumer_no);
                        $("#elect_acc_no").val(elect_acc_no);
                        $("#elect_bind_book_no").val(elect_bind_book_no);
                        $("#elect_cons_category").val(elect_cons_category);
                        $("#address").val(prop_address);
                        $("#pin").val(prop_pin_code);
                        $("#address").prop("readonly",true);
                        $("#pin").prop("readonly",true);
                    }
                    else{
                        alert(data.dd.message);
                        $("#owner_dtl").html(oldData);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    $("#owner_dtl").html(oldData);
                }
            });

        }
        
    }

    $("document").ready(function(){alert();
        ownerDivToggle();
        validate_holding();
        $("#request_type").on("change",function(){
            ownerDivToggle();
        });
        $('#form').validate({ 
            debugger:true,
            rules: {
                "request_type":"required",
                "owner_name[]": {
                    "required":true,
                },
                "mobile_no[]": {
                    "required": true,
                    "digits": true,
                    "minlength": 10,
                    "maxlength": 10,
                },
                messages: {
                    "doc": {
                        extension: "Please select a file with a valid extension (png, jpg, jpeg, gif)",
                        filesize_max: "File size exceeds 2MB limit"
                    }
                }
                
            
            
            }
        });

        // Custom method to check file size
        $.validator.addMethod('filesize_max', function(value, element, param) {
            var fileSize = element.files[0].size; // Size in bytes
            return fileSize <= param;
        }, 'File size must be less than {0} bytes.');

        // Custom method to check file extension
        $.validator.addMethod("extension", function(value, element, param) {
            param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpg|jpeg|pdf";
            return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
        }, "Please specify a valid file format[png,jpg,jpeg,pdf].");

    });
</script>
