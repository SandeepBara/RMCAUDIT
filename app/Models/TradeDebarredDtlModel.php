<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TradeDebarredDtlModel extends Model 
{
    protected $db;
    protected $table = 'tbl_debarred_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','ward_mstr_id','licence_no','emp_details_id','apply_licence_id','application_no','firm_type_id','application_type_id','ownership_type_id','prop_dtl_id','firm_name','area_in_sqft','establishment_date','firm_address','pin_code','property_type','landmark','k_no','bind_book_no','account_no','debarred_date','created_on','status','licence_creation_date','holding_no'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function insertData($input){
        try
        {
            $builder = $this->db->table($this->table)
                ->insert([
                    "ward_mstr_id" => $input['ward_mstr_id'],
                    "licence_no" => $input['licence_no'],
                    "emp_details_id" => $input['emp_details_id'],
                    "apply_licence_id" => $input['apply_licence_id'],  
                    "application_no" => $input['application_no'], 
                    "firm_type_id" => $input['firm_type_id'], 
                    "application_type_id" => $input['application_type_id'],
                    "ownership_type_id" => $input['ownership_type_id'],
                    "prop_dtl_id" => $input['prop_dtl_id'],
                    "firm_name" => $input['firm_name'],
                    "area_in_sqft" => $input['area_in_sqft'],
                    "establishment_date" => $input['establishment_date'],
                    "firm_address" => $input['firm_address'],
                    "pin_code" => $input['pin_code'],
                    "landmark" => $input['landmark'],
                    "k_no" => $input['k_no'],
                    "bind_book_no" => $input['bind_book_no'],
                    "account_no" => $input['account_no'],
                    "debarred_date" => $input['debarred_date'], 
                    "created_on" => $input['created_on'],
                    "licence_creation_date" => $input['licence_creation_date'],
                    "holding_no" => $input['holding_no']
                ]);
               // echo $this->db->getLastQuery();
            return $insert_id = $this->db->insertId();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    } 
}