<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TradeCategoryTypeModel extends Model 
{
    protected $db;
    protected $table = 'tbl_category_type';
    protected $allowedFields = ['id', 'category_type','status'];
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }   
    public function getCategoryType(){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('status',1)
                      ->orderBy('id','ASC')
                      ->get();
                      /*echo $this->db->getLastQuery();*/
            return $builder = $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    } 

    public function getdatabyid($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id,category_type,status');
        $builder->where('id', $id);
        $builder->where('status', 1);
        $builder = $builder->get();
        return $builder = $builder->getFirstRow('array');
    } 
	
	public function categoryDetails($id)
    {
        $sql = "SELECT *
                FROM tbl_category_type where id=$id
               ";
                $ql= $this->query($sql);
                //echo($this->getLastQuery());
            return $result =$ql->getFirstRow('array');
    } 
}