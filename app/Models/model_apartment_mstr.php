<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_apartment_mstr extends Model
{
    protected $db;
    protected $table = 'view_apartment_mstr_data';
    // protected $allowedFields = ['id','doc_name','doc_type','doc_id','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function apartmentList()
    {
        try{
            $sql = "select * from view_apartment_mstr_data order by id desc";
            $ap_query = $this->db->query($sql);
            return $result = $ap_query->getResultArray();
        //     $builder = $this->db->table('view_apartment_mstr_data')
        //                 ->select('*')
        //                 ->where('status',1)
        //                 ->get();
        //    return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getApartmentById($id)
    {
        try{
            $sql = "SELECT
            tb1.id,
            tb1.apt_code,
           tb1.apartment_name,
           tb1.apartment_address,
           tb1.water_harvesting_status,
           tb1.wtr_hrvs_image_file_name,
           tb1.apt_image_file_name,
           tb1.is_blocks,
           tb1.no_of_block,
           tb1.ward_mstr_id,
           tbl_road_type_mstr.road_type,
           tbl_road_type_mstr.id as road_id
          FROM tbl_apartment_details tb1
          left join tbl_road_type_mstr on tb1.road_type_mstr_id=tbl_road_type_mstr.id where tb1.id=".$id."";
            $ap_query = $this->db->query($sql);
            return $result = $ap_query->getResultArray()[0];
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
   
    public function insertApartment($data)
    {
        try{
             $sql = "insert into  tbl_apartment_details
            (apt_code,
            apartment_name,
            apartment_address,
            water_harvesting_status,
            wtr_hrvs_image_file_name,
            apt_image_file_name,
            entry_date,
            emp_dtl_id,
            ward_mstr_id,
            is_blocks,
            no_of_block,
            road_type_mstr_id,
            status) values(
            '".$data['apt_code']."', 
            '".$data['apt_name']."', 
            '".$data['apt_address']."',
            '".$data['water_hrvst_status']."',
            '".$data['wtr_img_file_path']." ',
            '".$data['apt_img_file_path']."',
            now(), 
            '".$data['emp_dtl_id']."', 
            '".$data['ward_mstr_id']."',
            '".$data['is_blocks']."', 
            '".$data['no_of_block']."',
            '".$data['road_type_mstr_id']."',
            1) 
            ";
            // return;
            $ap_query = $this->db->query($sql);
            return $insert_id = $this->db->insertID();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updateApartment($id,$data)
    {
        
        try{
              $sql = "update tbl_apartment_details set 
            apt_code='".$data['apt_code']."', 
            apartment_name='".$data['apt_name']."',
            apartment_address='".$data['apt_address']."',
            water_harvesting_status='".$data['water_hrvst_status']."',
            wtr_hrvs_image_file_name='".$data['wtr_hv_path']."',
            apt_image_file_name='".$data['apt_path']."',
            emp_dtl_id='".$data['emp_dtl_id']."',
            ward_mstr_id='".$data['ward_mstr_id']."',
            is_blocks='".$data['is_blocks']."',
            no_of_block='".$data['no_of_block']."',
            road_type_mstr_id='".$data['road_type_mstr_id']."'
            where tbl_apartment_details.id='".$id."'";
            $ap_query = $this->db->query($sql);
          
            // return $ap_query->insert_id();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function updateImagePath($id,$data)
    {
        try{
             $sql = "update tbl_apartment_details set 
            wtr_hrvs_image_file_name='".$data['wtr_hv_path']."',
            apt_image_file_name='".$data['apt_path']."'
            where tbl_apartment_details.id='".$id."'";
            $ap_query = $this->db->query($sql);
          
            // return $ap_query->insert_id();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function deleteApartment($id)
    {
        try{
            $sql = "delete from  tbl_apartment_details where id=".$id."";
            $ap_query = $this->db->query($sql);
            return $result = $ap_query->getResultArray();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function addDemand($id,$c_fy)
    {
        try{
            echo "adding demand    ";
            // echo $sql = "update tbl_prop_demand set 
            // amount=amount+(amount*0.5), 
            // balance=amount+(amount*0.5),
			// additional_amount=amount*0.5 
			
			// from tbl_apartment_details
            // INNER JOIN tbl_prop_dtl on tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
            // INNER JOIN tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
            // where tbl_apartment_details.id=".$id." and tbl_prop_demand.paid_status=0 
			// and 
			// tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
			// and tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
             $sql = "update tbl_prop_demand set amount=round((demand_amount+(demand_amount*0.5)),2), 
            balance=round((demand_amount+(demand_amount*0.5)),2), additional_amount=round((demand_amount*0.5),2) from 
            tbl_prop_dtl
            INNER JOIN tbl_apartment_details on 
            tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
            where tbl_apartment_details.id=".$id." and tbl_prop_demand.paid_status=0 and 
            tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id and 
            tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
            $ap_query = $this->db->query($sql);
            // return $ap_query->insert_id();
            // die;
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function removeDemand($id,$c_fy)
    {
        try{
            // echo "<br/>";

            // echo $sql = "update tbl_prop_demand set 
            // amount=amount-additional_amount, 
            // balance=amount-additional_amount,
			// additional_amount=0
			
			// from tbl_apartment_details
            // INNER JOIN tbl_prop_dtl on tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
            // INNER JOIN tbl_prop_demand on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
            // where tbl_apartment_details.id=".$id." and tbl_prop_demand.paid_status=0
			// and 
			// tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
			// and tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";

             $sql = "update tbl_prop_demand set 
            amount=amount-additional_amount, 
            balance=amount-additional_amount,
			additional_amount=0
             from 
            tbl_prop_dtl
            INNER JOIN tbl_apartment_details on 
            tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
            where tbl_apartment_details.id=".$id." and tbl_prop_demand.paid_status=0 and 
            tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id and 
            tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
            $ap_query = $this->db->query($sql);
            // return $ap_query->insert_id();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function remove_wtr_hrvst($id)
    {
        try{
          
            $sql = "update tbl_prop_dtl set is_water_harvesting='f'
            FROM tbl_apartment_details WHERE 
            tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id AND tbl_apartment_details.id=".$id."";
            $ap_query = $this->db->query($sql);
            // die;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function add_wtr_hrvst($id)
    {
        try{
          
            $sql = "update tbl_prop_dtl set is_water_harvesting='t'
            FROM tbl_apartment_details WHERE 
            tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id AND tbl_apartment_details.id=".$id."";
            $ap_query = $this->db->query($sql);
            // die;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function addUpdatePropTax($id,$c_fy)
    {
        try{
            echo "adding proptax";

            // echo $sql = "update tbl_prop_tax set 
            // holding_tax=holding_tax+(holding_tax*0.5), 
			// additional_tax=(holding_tax*0.5)
			
			// from tbl_apartment_details
            // INNER JOIN tbl_prop_dtl on tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
			// INNER JOIN tbl_prop_demand on tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
            // INNER JOIN tbl_prop_tax on tbl_prop_demand.prop_tax_id=tbl_prop_tax.id
            // where tbl_apartment_details.id=".$id."
			// and tbl_prop_demand.paid_status=0
			// and tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
            echo $sql = "update tbl_prop_tax set holding_tax=round((holding_tax+(holding_tax*0.5)),2), 
            additional_tax=round((holding_tax*0.5),2)
            FROM 
            (select distinct(prop_tax_id),paid_status,prop_dtl_id,fy_mstr_id from tbl_prop_demand )as 
            tbl_prop_demand 
            INNER JOIN tbl_prop_dtl on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
            INNER JOIN tbl_apartment_details on 
            tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
            where tbl_apartment_details.id=".$id." and tbl_prop_demand.paid_status=0 and 
            tbl_prop_demand.prop_tax_id=tbl_prop_tax.id and
            tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
            $ap_query = $this->db->query($sql);
            // return $ap_query->insert_id();
            // die;
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function removeUpdatePropTax($id,$c_fy)
    {
        try{
            echo "<br/>";

            // echo $sql = "update tbl_prop_tax set 
            // holding_tax=holding_tax-additional_tax, 
			// additional_tax=0
			
			// from tbl_apartment_details
            // INNER JOIN tbl_prop_dtl on tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
			// INNER JOIN tbl_prop_demand on tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
            // INNER JOIN tbl_prop_tax on tbl_prop_demand.prop_tax_id=tbl_prop_tax.id
            // where tbl_apartment_details.id=".$id."
			// and tbl_prop_demand.paid_status=0
			// and tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
            echo $sql = "update tbl_prop_tax set holding_tax=holding_tax-additional_tax, 
            additional_tax=0
            FROM 
            (select distinct(prop_tax_id),paid_status,prop_dtl_id,fy_mstr_id from tbl_prop_demand )as 
            tbl_prop_demand 
            INNER JOIN tbl_prop_dtl on tbl_prop_demand.prop_dtl_id=tbl_prop_dtl.id
            INNER JOIN tbl_apartment_details on 
            tbl_apartment_details.id=tbl_prop_dtl.apartment_details_id
            where tbl_apartment_details.id=".$id." and tbl_prop_demand.paid_status=0 and 
            tbl_prop_demand.prop_tax_id=tbl_prop_tax.id and
            tbl_prop_demand.fy_mstr_id=(select id from view_fy_mstr where fy='".$c_fy."')";
            $ap_query = $this->db->query($sql);
            // return $ap_query->insert_id();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getApartmentByWard($ward_id)
    {
        try{
           $sql = "SELECT
            id,
            apt_code,
            apartment_name,
            apartment_address,
            water_harvesting_status
          FROM tbl_apartment_details
          where ward_mstr_id=".$ward_id." and status=1";
            $ap_query = $this->db->query($sql);
            return $result = $ap_query->getResultArray();
       
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
}
?> 