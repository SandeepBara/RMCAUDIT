<?= $this->include('layout_vertical/header'); ?>
<style>
    .o {
        height: 50px;
        border-bottom: 1px solid #ddd;
    }

    /*#nav_box li a{padding-bottom: 5px;}*/
    #nav_box a:hover {
        background-color: none;
    }

    .box {
        padding: 2px;
        border-radius: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .box div {
        text-align: center;
        flex: 1;
    }

    .box div+div {
        border-left: 1px solid rgba(255, 255, 255, 0.5);
    }
    .box-vertical{
        padding:  2px;
        /* margin: 5px; */
        border-radius: 5px;        
        /* justify-content: space-between; */
        align-items: center;
    }
    .box-vertical div {
        text-align: center;
        line-height: 4rem;
        padding-top:  2rem;
        padding-bottom:  2rem;
    }

    .box-vertical div+div {
        border-top: 1px solid rgba(255, 255, 255, 0.5);
    }
</style>
<link href="<?= base_url(); ?>/public/assets/plugins/morris-js/morris.min.css" rel="stylesheet">
<!--Spinkit [ OPTIONAL ]-->
<link href="<?= base_url(); ?>/public/assets/plugins/spinkit/css/spinkit.min.css" rel="stylesheet">

<div id="page-head" style="background-color: #25476a; color: #fff; margin-top: 3%;">
    <div class="pad-all text-center">
        <h3><?= $ulb_mstr["ulb_name"]; ?></h3>
    </div>
</div>
<!--Page content-->
<div id="page-content">
    <div class="panel" style="margin-left: 50px;">
        <div class="panel-heading">
            <h3 class="panel-title">
                <div class="col-md-8 col-xs-8">
                    <div class="col-md-2 col-xs-2">
                        <i class="fa fa-inbox"></i> Dashboard
                    </div>
                    <form id ="myForm" action="">
                        <div class="col-md-3 col-xs-3">
                            <label for="formDate">Start Date</label>
                            <input type="date" id="fromDate" name="fromDate" max="<?= date("Y-m-d") ?>" />
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <label for="uptoDate">End Date</label>
                            <input type="date" id="uptoDate" name="uptoDate" max="<?= date("Y-m-d") ?>" />
                        </div>
                        <div class="col-md-3 col-xs-3">
                            <input class="btn btn-warning" type="button" id="search_btn" name="search_btn" value="Search" />
                        </div>
                    </form>
                </div>
                <ul class="nav nav-pills" id="nav_box" >
                    <li class="" style="margin-left: 140px;">
                        <div class="col-md-4 col-xs-4" id="front_show_date_time">Sun Mar 13 2022 12:36:48 PM</div>
                    </li>
                </ul>
            </h3>
        </div>        
        <!-- tab content -->
        <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading" style="background-color:black;">
                    <button class="panel-control  btn" style="background-color: white; color:black;" onclick="refreshDashboard()">Refresh</button>
                    <h5 class="panel-title">Municipal License Application Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="tab-content">
                            <div id="menu1" class="tab-pane fade in active">
                                <h3> TRADE</h3>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="panel panel-warning panel-colorful media middle " style="padding: 5px; background-color:#574a5cbd">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="new_application">0</p>
                                                <p class="mar-no">New License Request</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-word icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-info panel-colorful media middle" style="padding: 5px; background-color: #54e165f2;">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="renewal_application">0</p>
                                                <p class="mar-no">Renewal License Request</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="fa fa-refresh fa-2x icon-3x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-mint panel-colorful media middle" style="padding: 5px; background-color: #86bce2f2;">

                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="amendment_application">0</p>
                                                <p class="mar-no">Amendment License Request</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="fa fa-pencil-square-o fa-2x icon-3x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-primary panel-colorful media middle" style="padding: 5px; background-color: #0b95f6ab">

                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="surrender_application">0</p>
                                                <p class="mar-no">Surrender License Request</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-zip icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-danger panel-colorful media middle" style="padding: 5px; background-color: #f60b737d">

                                            <div class="media-body">
                                                <div class="box">
                                                    <div>
                                                        <p class="text-2x mar-no text-semibold" id="approved_license">0</p>
                                                        <p class="mar-no">Normal Approved</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-2x mar-no text-semibold" id="deemed_approved">0</p>
                                                        <p class="mar-no">Deemed Approved</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-info panel-colorful media middle" style="padding: 5px; background-color: #0754f4f2">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="rejected_license">0</p>
                                                <p class="mar-no">Total Rejected Form</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-zip icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="panel panel-info panel-colorful media middle" style="padding: 5px; background-color: #24ebe5c7">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="pending_at_level">0</p>
                                                <p class="mar-no">Pending At Level</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-zip icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="panel panel-info panel-colorful media middle" style="padding: 5px;">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="back_to_citizen">0</p>
                                                <p class="mar-no">Back To Citizen</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-zip icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-info panel-colorful media middle" style="padding: 5px; background-color: #f76c05de;">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="pending_at_jsk">0</p>
                                                <p class="mar-no">Pending At JSK</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-zip icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-info panel-colorful media middle" style="padding: 5px; background-color: #1ee7aac9;">
                                            <div class="media-body">
                                                <p class="text-2x mar-no text-semibold" id="deemed_license">0</p>
                                                <p class="mar-no">Deemed Application</p>
                                            </div>
                                            <div class="media-left">
                                                <div class="pad-hor">
                                                    <i class="demo-pli-file-zip icon-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="panel panel-danger panel-colorful media middle" style="padding: 5px;">

                                            <div class="media-body">
                                                <div class="box">
                                                    <div>
                                                        <p class="text-2x mar-no text-semibold" id="provisional_license">0</p>
                                                        <p class="mar-no">Provisional</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-2x mar-no text-semibold" id="final_license">0</p>
                                                        <p class="mar-no">Final License</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading" style="background-color:black;">
                    <button class="panel-control  btn" style="background-color: white; color:black;" onclick="refreshCollectionDashboard()">Refresh</button>
                    <h5 class="panel-title">Municipal License Collection Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel panel-primary panel-colorful media middle" style="padding: 5px;">
                                <div class="media-body">
                                    <div class="box-vertical">
                                        <div>
                                            <p class="mar-no">New License</p>
                                            <p class="text-2x mar-no text-semibold" id="new_application_collection">0</p>
                                        </div>
                                        <div>
                                            <p class="mar-no">No Of Application</p>
                                            <p class="text-2x mar-no text-semibold" id="total_new_application">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-success panel-colorful media middle" style="padding: 5px;">
                                <div class="media-body">
                                    <div class="box-vertical">
                                        <div>
                                            <p class="mar-no">Renewal License</p>
                                            <p class="text-2x mar-no text-semibold" id="renewal_application_collection">0</p>
                                        </div>
                                        <div>
                                            <p class="mar-no">No Of Application</p>
                                            <p class="text-2x mar-no text-semibold" id="total_renewal_application">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-mint panel-colorful media middle" style="padding: 5px;">
                                <div class="media-body">
                                    <div class="box-vertical">
                                        <div>
                                            <p class="mar-no">Amendment License</p>
                                            <p class="text-2x mar-no text-semibold" id="amendment_application_collection">0</p>
                                        </div>
                                        <div>
                                            <p class="mar-no">No Of Application</p>
                                            <p class="text-2x mar-no text-semibold" id="total_amendment_application">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-warning panel-colorful media middle" style="padding: 5px;">
                                <div class="media-body">
                                    <div class="box-vertical">
                                        <div>
                                            <p class="mar-no">Surrender License</p>
                                            <p class="text-2x mar-no text-semibold" id="surrender_application_collection">0</p>
                                        </div>
                                        <div>
                                            <p class="mar-no">No Of Application</p>
                                            <p class="text-2x mar-no text-semibold" id="total_surrender_application">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- tab content end -->
        <!-- <div class="panel-body"> -->


    </div>

</div>
<!--===================================================-->
<!--End page content-->

<?= $this->include('layout_vertical/footer'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="<?= base_url(); ?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    // Collapse Menu Automatically
    $("#container").removeClass("effect aside-float aside-bright mainnav-lg");
    $("#container").addClass("effect aside-float aside-bright mainnav-sm");

        
    function formatAMPM() {
        var d = new Date(),
        seconds = d.getSeconds().toString().length == 1 ? '0'+d.getSeconds() : d.getSeconds(),
        minutes = d.getMinutes().toString().length == 1 ? '0'+d.getMinutes() : d.getMinutes(),
        hours = d.getHours().toString().length == 1 ? '0'+d.getHours() : d.getHours(),
        ampm = d.getHours() >= 12 ? 'PM' : 'AM',
        months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        return days[d.getDay()]+' '+months[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear()+' '+hours+':'+minutes+':'+seconds+' '+ampm;
    }
    $("front_show_date_time").html(formatAMPM());
    window.setInterval(function(){ document.getElementById("front_show_date_time").innerHTML = formatAMPM(); }, 1000)
    
    $("#search_btn").on("click",function(){
        refreshDashboard();
        refreshCollectionDashboard();
    });
    function refreshDashboard() {

        var form = document.getElementById('myForm');
        var formData = new FormData(form); 
        formData.append('ajax', true);
        var url1 = "<?=base_url().'/NewDashboardTrade/tradeLicenseStatusDtl?'?>";               
        for (var [name, value] of formData.entries()) {
            console.log(name, value);
            url1 += (name+'='+value)+"&";
            
        }

        $.ajax({
            url: "<?=base_url('/NewDashboardTrade/tradeLicenseStatusAjax');?>", // Replace with your server URL
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false,  
            beforeSend: function() {
                $('#new_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#renewal_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#amendment_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#surrender_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');

                $('#approved_license').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#deemed_approved').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');

                $('#rejected_license').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#pending_at_level').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#back_to_citizen').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');

                $('#pending_at_jsk').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#deemed_license').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#provisional_license').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#final_license').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                
                
            },
            success: function(response){                
                console.log("response:",response);
                response = JSON.parse(response);                
                if(response.status){ 
                    var result = response.result;
                    $('#new_application').html(result?.new_license);
                    $('#new_application').css("cursor","pointer");
                    $('#new_application').attr("onclick","myPopup('"+url1+"type=New Apply&app_type=new_license')");
                    
                    $('#renewal_application').html(result?.renewal_license);
                    $('#renewal_application').css("cursor","pointer");
                    $('#renewal_application').attr("onclick","myPopup('"+url1+"type=Renewal Apply&app_type=renewal_license')");
                    
                    $('#amendment_application').html(result?.amendment_license);
                    $('#amendment_application').css("cursor","pointer");
                    $('#amendment_application').attr("onclick","myPopup('"+url1+"type=Amendment Apply&app_type=amendment_license')");
                    
                    $('#surrender_application').html(result?.surender_license);
                    $('#surrender_application').css("cursor","pointer");
                    $('#surrender_application').attr("onclick","myPopup('"+url1+"type=Surrender Apply&app_type=surender_license')");

                    $('#approved_license').html(result?.normal_approved);
                    $('#approved_license').css("cursor","pointer");
                    $('#approved_license').attr("onclick","myPopup('"+url1+"type=Normal Approved&app_type=normal_approved')");

                    $('#deemed_approved').html(result?.deemed_approved);
                    $('#deemed_approved').css("cursor","pointer");
                    $('#deemed_approved').attr("onclick","myPopup('"+url1+"type=Deemed Approved&app_type=deemed_approved')");

                    $('#rejected_license').html(result?.total_rejected);
                    $('#rejected_license').css("cursor","pointer");
                    $('#rejected_license').attr("onclick","myPopup('"+url1+"type=Rejected&app_type=total_rejected')");

                    $('#pending_at_level').html(result?.pending_at_level);
                    $('#pending_at_level').css("cursor","pointer");
                    $('#pending_at_level').attr("onclick","myPopup('"+url1+"type=Pending App&app_type=pending_at_level')");

                    $('#back_to_citizen').html(result?.back_to_citizen);
                    $('#back_to_citizen').css("cursor","pointer");
                    $('#back_to_citizen').attr("onclick","myPopup('"+url1+"type=Back To Citizen&app_type=back_to_citizen')");
                    
                    $('#pending_at_jsk').html(result?.pending_at_jsk);
                    $('#pending_at_jsk').css("cursor","pointer");
                    $('#pending_at_jsk').attr("onclick","myPopup('"+url1+"type=Pending At JSK&app_type=pending_at_jsk')");

                    $('#deemed_license').html(result?.deemed_license);
                    $('#deemed_license').css("cursor","pointer");
                    $('#deemed_license').attr("onclick","myPopup('"+url1+"type=Pending At JSK&app_type=deemed_license')");

                    $('#provisional_license').html(result?.provisanl_license);
                    $('#provisional_license').css("cursor","pointer");
                    $('#provisional_license').attr("onclick","myPopup('"+url1+"type=Provisional&app_type=provisanl_license')");

                    $('#final_license').html(result?.total_approved);
                    $('#final_license').css("cursor","pointer");
                    $('#final_license').attr("onclick","myPopup('"+url1+"type=Final License&app_type=total_approved')");

                }
                else{
                    $('#new_application').html(0);
                    $('#renewal_application').html(0);
                    $('#amendment_application').html(0);
                    $('#surrender_application').html(0);
                    $('#approved_license').html(0);
                    $('#rejected_license').html(0);
                    $('#pending_at_level').html(0);
                    $('#back_to_citizen').html(0);
                    
                    $('#pending_at_jsk').html(0);
                    $('#provisional_license').html(0);
                    $('#final_license').html(0);
                }
                
            },
            error: function(xhr, status, error){
                console.error('File upload failed:', status, error);
            }
        });
        
    }
    
    function refreshCollectionDashboard(){
        var form = document.getElementById('myForm');
        var formData = new FormData(form); 
        formData.append('ajax', true);
        for (var [name, value] of formData.entries()) {
            console.log(name, value);
            
        }

        $.ajax({
            url: "<?=base_url('/NewDashboardTrade/tradeLicenseCollectionAjax');?>", // Replace with your server URL
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false,  
            beforeSend: function() {

                $('#new_application_collection').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#total_new_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                
                $('#renewal_application_collection').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#total_renewal_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                
                $('#amendment_application_collection').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#total_amendment_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                
                $('#surrender_application_collection').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                $('#total_surrender_application').html('<div class="sk-three-bounce" style="margin:0;height:30px;"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>');
                
                
                
            },
            success: function(response){                
                console.log("response:",response);
                response = JSON.parse(response);
                if(response.status){ 
                    var result = response.result; 
                    $('#new_application_collection').html(result?.new_application_collection);
                    $('#total_new_application').html(result?.total_new_application);
                    
                    $('#renewal_application_collection').html(result?.renewal_application_collection);
                    $('#total_renewal_application').html(result?.total_renewal_application);
                    
                    $('#amendment_application_collection').html(result?.amendment_application_collection);
                    $('#total_amendment_application').html(result?.total_amendment_application);
                    
                    $('#surrender_application_collection').html(result?.surrender_application_collection);
                    $('#total_surrender_application').html(result?.total_surrender_application);
                }
                else{
                    $('#new_application').html(0);
                    $('#renewal_application').html(0);
                    $('#amendment_application').html(0);
                    $('#surrender_application').html(0);
                    $('#approved_license').html(0);
                    $('#rejected_license').html(0);
                    $('#pending_at_level').html(0);
                    $('#back_to_citizen').html(0);
                    
                    $('#pending_at_jsk').html(0);
                    $('#provisional_license').html(0);
                    $('#final_license').html(0);
                }
                
            },
            error: function(xhr, status, error){
                console.error('File upload failed:', status, error);
            }
        });
    }

    function myPopup(myURL, title = "xtf", myWidth =900, myHeight =700)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
</script>