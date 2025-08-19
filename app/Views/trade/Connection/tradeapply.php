<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Apply Trade Licence</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form method="post" action="<?=base_url('tradeapplylicence/index');?>">
            
            <?php
            if(isset($validation)){
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10 text-danger">
                        <?php 
                        foreach ($validation as $errMsg) {
                            echo $errMsg; echo ".<br />";
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">                   
                    <h3 class="panel-title">Apply Trade Licence</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-1">Apply For</label>
                        <div class="col-md-4 pad-btm">
                            <?php if(isset($applicationType)){
                                    if(!empty($applicationType)){                                    
                                ?>
                            <select id="application_type" name="application_type" class="form-control" onchange="getlicencedetails(),show_hide_licence_box(this.value)">
                                <option value="">--SELECT--</option>
                                <?php foreach ($applicationType as  $value) {                                    
                                 ?>
                                <option value="<?=$value["id"]?>"><?=$value["application_type"]?></option>
                            <?php }?>
                            </select>
                        <?php 
                                }else{
                                echo "Please Contact to software team ! Error No. 1001"; 
                                 }
                            }
                            else{
                                echo "Please Contact to software team ! Error No. 1002"; 
                            } ?>
                        </div>
                         <label class="col-md-2 searchlicence" style="display: none;">Licence No.<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                           <input type="text" name="licenceno" id="licenceno" onblur="getlicencedetails()" class="form-control searchlicence" style="display: none;">
                        </div>
                    </div>
                 </div>
            </div>
       
            <div class="panel panel-bordered panel-dark clslicencedtl" style="display: none;">
                <div class="panel-heading">
                    <h3 class="panel-title">Licence Details List</h3>
                </div>
                <div class="panel-body">
                    <div id="saf_distributed_dtl_hide_show">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-trans-dark text-dark">
                                            <tr>
                                                <th>Licence No.</th>
                                                <th>Ward No</th>
                                                <th>Firm Name</th>
                                                <th>Firm Owner Name</th>
                                                <th>Phone No</th>
                                                <th>Firm Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_licence_dtl">
                                   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
        <div class="panel panel-bordered panel-dark clproceed" style="display: none;">
            
            <div class="panel-body" style="text-align: center;">
               <a href="<?=base_url('');?>/tradeapplylicence/applynewlicence/<?=md5(1);?>" class="btn btn-primary">Proceed</a>
            </div>
        </div>
        </form>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function(){
        $("#formname").validate({
            rules:{
                application_type:{
                    required:true
                },
                licenceno:{
                    required:true
                }               
            },
            messages:{
                application_type:{
                    required:"Please select"
                },
                licenceno:{
                    required:"Please select Ownership Type"
                }               
            }
        });
    });

    function show_hide_licence_box(str){       
      
        if(str>1){
            $(".searchlicence").show();
            $(".clslicencedtl").show();            
            $(".clproceed").hide();
        }else{
           $(".searchlicence").hide();
           $(".clslicencedtl").hide(); 
           $(".clproceed").show();
        }

    
    }

    function getlicencedetails()
    {

         var licenceno=$("#licenceno").val();         
         var application_type=$("#application_type").val();
        //var owner_type=$("#ownership_type_id").val();
        
         if(licenceno==""){
          // $("#owner_dtl_append").html(appendData);
            
            }
            else
            { 

              $.ajax({
                type:"POST",
                url: '<?php echo base_url("tradeapplylicence/getlicencedetails");?>',
                dataType: "json",
                data: {
                        "licence_no":licenceno,"application_type":application_type
                },
               
                success:function(data){
                  console.log(data);
                 // alert(data.payment_status);

                   if (data.response==true) {

                    var tbody="";
                        var i=1;
                        var app=1;
                        var st= data.dd['status'];                        
                        if(application_type==3){
                            if(st==2){
                                var app=1;
                            }else{
                              var app=2;  
                            }
                        }else{
                            if(st==2){
                                var app=0;
                            }else{
                              var app=1;  
                            }
                        }
                       
                        if(app==1){                            
                              // console.log(k, data.dd['owner_name']);
                               /*var payment_status=data.dd['payment_status'];
                                var prop_dtl_id=data.dd['prop_dtl_id'];*/

                                tbody+="<tr>";
                                
                                
                            //   $("#owner_name").val( data.dd['owner_name']);
                               tbody+='<td>'+data.dd['licence_no']+'</td>';

                               tbody+='<td>'+data.dd['ward_no']+'</td>';

                                tbody+='<td>'+data.dd['firm_name']+'</td>';
                                tbody+='<td>'+data.dd['applicant_name']+'</td>';
                                tbody+='<td>'+data.dd['mobile_no']+'</td>';

                                tbody+='<td>'+data.dd['firm_address']+'</td>';

                                tbody+='<td><a href="<?=base_url('');?>/tradeapplylicence/applynewlicence/'+data.at+'/'+data.dd['mdid']+'" class="btn btn-primary">Proceed</a></td>';

                                 tbody+='<td></td>';

                               tbody+="</tr>";
                               i++;

                                                     
                            
                        }else if(app==2){   
                            tbody+="<tr>"; 
                           tbody+='<td colspan="7" style="text-align: center;">Licence No. '+licenceno+'  Is Not Surrendered! Please Surrender First.</td>'; 
                           tbody+="</tr>";
                        }else{
                            tbody+="<tr>"; 
                           tbody+='<td colspan="7" style="text-align: center;">Licence No. '+licenceno+'  is surrendered!</td>'; 
                           tbody+="</tr>";
                        }
                    
                    
                   } else { 

                            tbody+="<tr>"; 
                           tbody+='<td colspan="7" style="text-align: center;">Data Not Available!!</td>'; 
                           tbody+="</tr>";

                      /*alert('SAF No. not Found');
                      $("#saf_no").val("");
                      $("#saf_id").val("");
                      $("#ward_id").val("");
                      $("#ward_no").val("");
                      $("#firmaddress").val("");
                      $("#pin").val("");  */

                   }                   
                   $("#tbody_licence_dtl").html(tbody);
                   //
                },
                error: function(jqXHR, textStatus, errorThrown) {

                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
          }

    }
</script>