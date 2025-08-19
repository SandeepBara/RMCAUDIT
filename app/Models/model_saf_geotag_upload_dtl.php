<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_saf_geotag_upload_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_saf_geotag_upload_dtl';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','geotag_dtl_id','image_path','latitude','longitude','direction_type','created_by_emp_details_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

     public function insertData($input){
        $builder = $this->db->table($this->table)
                            ->insert([
                  "geotag_dtl_id"=>$input['geotag_dtl_id'],
                  "image_path"=>$input['image_path'],
                  "latitude"=>$input['latitude'],
                  "longitude"=>$input['longitude'],
                  "direction_type"=>$input['direction_type'],
                  "created_by_emp_details_id"=>$input['created_by_emp_details_id'],
                  "created_on"=>$input['created_on'],
                  "upload_type"=>$input['upload_type'],
                  "status"=>'1'
				  ]);
							//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function check_distributed_image_details($saf_distributed_id,$upload_type,$direction_type)
    {
        try{
             //$names = ['10', '11'];
             return $this->db->table($this->table)
                        ->select('id,image_path')
                        ->where('md5(geotag_dtl_id::text)',$saf_distributed_id)
                        ->where('upload_type', $upload_type)
                        ->where('direction_type', $direction_type)
                        ->where('status',1)
                        ->get()
                        ->getResultArray();                        
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getImgpathBySafId($saf_distributed_dtl_id,$upload_type,$direction_type)
    {
        $builder = $this->db->table($this->table);
        $builder->select('image_path');
        $builder->where('saf_distributed_dtl_id', $saf_distributed_dtl_id);
        $builder->where('upload_type', $upload_type);
        $builder->where('direction_type', $direction_type);
        $builder->where('status', 1);
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder[0];
    }

    public function getallImgpath()
    {
        $builder = $this->db->table($this->table);
        $builder->select('geotag_dtl_id,image_path,latitude,longitude');
        $builder->where('status', 1);
        $builder->where('latitude is not NULL');
        $builder->where('longitude is not NULL');
        $builder = $builder->get();
        $builder = $builder->getResultArray();
        return $builder;
    }

    public function getAllGeoTagImgDtlBySafDtlId($input)
    {
        $builder = $this->db->table($this->table)
                            ->select('*')
                            ->where('geotag_dtl_id', $input['saf_dtl_id'])
                            ->where('status', 1)
                            ->where('latitude is not NULL')
                            ->where('longitude is not NULL')
                            ->get();
        //echo $this->db->getLastQuery();
        return $builder->getResultArray();
    }


}