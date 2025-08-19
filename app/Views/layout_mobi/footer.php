        </div>
        <!-- FOOTER -->
        <footer id="footer">
            <div class="show-fixed pad-rgt pull-right">
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
            </div>
            <div class="hide-fixed pull-right pad-rgt">
                Developed by SPS PVT LTD
            </div>
           
        </footer>
        <!-- END FOOTER -->
        <!-- SCROLL PAGE BUTTON -->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
    </div>
    <!-- END OF CONTAINER -->
</body>
</html>
<!--JAVASCRIPT-->
<!--jQuery [ REQUIRED ]-->
<script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>
<!--BootstrapJS [ RECOMMENDED ]-->
<script src="<?=base_url();?>/public/assets/js/bootstrap.min.js"></script>
<!--NiftyJS [ RECOMMENDED ]-->
<script src="<?=base_url();?>/public/assets/js/nifty.min.js"></script>
<!--[ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/plugins/masked-input/jquery.maskedinput.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?=base_url();?>/public/assets/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#loadingDiv").hide();
});

function modelInfo(msg)
{
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php 
if($result = flashToast('message'))
{
    ?>
    
    modelInfo('<?=$result;?>');
    <?php 
}
?>
$(".btn_wait_load").click(function(){
    $(this).html("Wait...");
});
</script>

<?php
    $empDtl=session()->get("emp_details");
    $uri = clone \Config\Services::request()->uri;
    $uriarr = explode("/", $uri->getPath());
    $className = strtoupper($uriarr[0]);
    $function = strtoupper($uriarr[1] ?? "");
    if(isset($empDtl["user_type_mstr_id"]) && $empDtl["user_type_mstr_id"]==5 && (strtoupper($className)!= strtoupper('MobiSaf') && strtoupper($function)!=strtoupper('AddUpdate2')))
    {
        ?>    
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0oDYz1cVvaM-fcAmYVsz9uVwnDNwD5g&callback=initMap1" async defer></script>
        <script>
            function initMap1()
            {
                
            }
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showError);
                } 
            }
            function showPosition(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;            
                $("#visiting_latitude").val(latitude);
                $("#visiting_longitude").val(longitude);
                initialize(latitude,longitude);
            }
            function showError(error) {
                
            }
            function vipAdd(){
                $.getJSON("http://jsonip.com/?callback=?", function (data) {
                    var vip = (((data.ip).split(","))[0]);
                    $("#visiting_ip").val(vip);
                    // alert(vip);
                });
            }
            function initialize(latitude,longitude) {
                // Set the latitude and longitude values
                var latlng = new google.maps.LatLng(latitude, longitude);

                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'location': latlng }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK && results[0]) {
                        document.getElementById('visiting_address').values = results[0].formatted_address;
                    } 
                });
            }

            function insertVistingDtlAjx()
            {  
                let postdata={
                        ip : $("#visiting_ip").val(),
                        latitude : $("#visiting_latitude").val(),            
                        longitude : $("#visiting_longitude").val(),
                        address : $("#visiting_address").val(),
                };
                $.ajax({
                    url:"<?php echo base_url("Login/Visiting");?>",    
                    type: "post",    
                    dataType: 'json',
                    data: postdata,
                    beforeSend: function() {
                        $("#loadingDiv").show();

                    },
                    success:function(result){
                        $("#loadingDiv").hide();
                        return result.status;
                    }
                });
            }
            $(document).ready(function (){ 
                let vip = ""; 
                let vlat = "";
                let vlong = "";
                var fields = "<span><input type='hidden' name='visiting_address' id ='visiting_address' value=''/> <input type='hidden' name='visiting_ip' id ='visiting_ip' value=''/> <input type='hidden' name='visiting_latitude' id ='visiting_latitude' value='' />  <input type='hidden' name='visiting_longitude' id ='visiting_longitude' value='' /> <input type='hidden' name='visiting_address' id ='visiting_address' value='' /></span>";
                for(i=0;i<document.getElementsByTagName("form").length;i++)
                {
                    document.getElementsByTagName("form")[i].innerHTML += (fields);
                }         
                
                vipAdd();
                getLocation(); 
                
            });
        </script>
        <?php
    }
?>