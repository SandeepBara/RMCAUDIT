<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class WaterMobileModel extends Model
{

    protected $table = 'tbl_apply_water_connection';

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
        $this->builder='';
	}

    public function get_penalty_details($water_conn_id)
    {

        return $result=$this->db->table("tbl_penalty_dtl")
                            ->select("coalesce(sum(penalty_amt),0) as penalty")
                            ->where("md5(water_conn_id::text)",$water_conn_id)
                            ->where('status',1)
                            ->get()
                            ->getFirstRow("array");

                           // echo $this->getLastQuery();

      
    }


    public function insert_transaction(array $data)
    {

        $result= $this->db->table("tbl_transaction")
                 ->insert($data);       
               //  echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    
    public function search_consumer(array $data,$where=array(),$ward_ids='')
    {
        $sql="select view_water_application_details.*,tbl_level_pending.id as level_pending_dtl_id 
        from view_water_application_details 
        join tbl_level_pending on tbl_level_pending.apply_connection_id=view_water_application_details.id 
        left join tbl_site_inspection on tbl_site_inspection.apply_connection_id=view_water_application_details.id
        where tbl_level_pending.created_on::date between '".$data['from_date']."' and '".$data['upto_date']."' and receiver_user_type_id=".$data['user_type_mstr_id']." and verification_status=0 -- and scheduled_status=1";
        //and  (tbl_site_inspection.verified_status is NULL or tbl_site_inspection.payment_status=0)
        $w=!empty($where)? " and ":" ";
        foreach ($where as $key=>$val)
        {
            $w.=$key.'::text = '."'$val'";
        }
        $in_wards='';
        if($ward_ids!='')
        {
            $in_wards=" and view_water_application_details.ward_id in ($ward_ids)";
        }
        $sql="select view_water_application_details.*,tbl_level_pending.id as level_pending_dtl_id 
        from view_water_application_details 
        join tbl_level_pending on tbl_level_pending.apply_connection_id=view_water_application_details.id 
        left join tbl_site_inspection on tbl_site_inspection.apply_connection_id=view_water_application_details.id and scheduled_status=1  $w 
        where tbl_level_pending.send_date::date between '".$data['from_date']."' and '".$data['upto_date']."' and 
        receiver_user_type_id=".$data['user_type_mstr_id']." and verification_status=0 and tbl_level_pending.status=1  $in_wards ";
        $run=$this->db->query($sql);
        $result=$run->getResultArray();
        // echo $this->db->getLastQuery();
        //print_var($result);
        return $result;
    }

    //******************sandeep ****************//
    public function getDataNew($where = array(),$columan=array('*'),$tbls='',$group_by=array(),$orderBy=array('id'=>'ASC'))
    {   
        $data = array();
        $tbl='';
        //print_var($where);die;
        try
        {   
            if($tbls=='')
                $tbl=$this->table;
            else
                $tbl=$tbls;

            $this->builder = $this->db->table($tbl)
                        ->select($columan);

            if(count($where)!=0)
            {   
                foreach($where as $key=>$val)
                {   $pattern = "/[<>!]/i";
                    
                    if(preg_match($pattern,$key))
                    {
                        if(is_array($val))
                        {
                            $key = preg_replace($pattern, '', $key);
                            $this->builder = $this->builder->whereNotIn($key,$val);
                        }
                        else
                        $this->builder = $this->builder->where($key,$val);
                    }
                    else
                    {
                        if(is_array($val))
                        $this->builder = $this->builder->whereIn($key,$val);
                        else
                        $this->builder = $this->builder->where($key,$val);
                        
                    } 
                }                               
                
            }
            
            if(!empty($group_by))
                $this->builder = $this->builder->groupBy($group_by); 

            foreach($orderBy as $key=>$val)
            {
                $this->builder = $this->builder->orderBy($key,$val);
            }
            $data = $this->builder->get()->getResultArray();
            if(sizeof($data)==1)
                $data=$data[0];
            
            //print_r($where);
            // echo $this->db->getLastQuery();
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getDataRowQuery($sql,$show_data = null,$from=null)
    {
        $data=array();
        if(is_null($show_data))
        {
                $show_data = limitInPagination();
        }
        $uri_string = uri_string();
        if(isset($_GET['page']))
        {
            $page = intval($_GET['page'])-1;
            if($page<0) $page = 0;
        } 
        else 
        {
            $page = 0;
        }
        $start_page = $page*$show_data;

        try
        {
            //->limit( $show_data,$start_page)
            $sql2=$sql."offset $start_page limit $show_data ";
            $run= $this->db->query($sql2);
            //print_var($this->db->getlastQuery());
            $data['result']=$run->getResultArray();
            $count=$this->db->query($sql)->getResultArray();
            //echo($this->db->getlastQuery());
            $data['count']=!empty($count)?count($count):0; 
            $data['offset']=$start_page;
            //print_var($data['count']);
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function getDataRowQuery2($sql)
    {
        $data=array();
        try
        {
            $run= $this->db->query($sql);
            $data['result']=$run->getResultArray();
            // print_var($sql);
            $count = count($data['result']);
            $data['count']=!empty($count)?$count:0;
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function updateNew($where=array(),$data=array(),$tbl='')
    {
        if($tbl!='')
            $builder = $this->db->table($tbl);
        else
            $builder = $this->db->table($this->table);
        $data = $builder->where($where)->update($data);
        //echo $this->db->getLastQuery();
        return $data;
    }
    public function get_data_10($from,$select,$show=false,$with=null,$show_data=null)
    {
        $data=array();
        if(is_null($show_data))
        {
                $show_data = limitInPagination();
        }
        $uri_string = uri_string();
        if(isset($_GET['page']))
        {
            $page = intval($_GET['page'])-1;
            if($page<0) $page = 0;
        } 
        else 
        {
            $page = 0;
        }
        $start_page = $page*$show_data;
        try
        {
            
            $sql2=$with.$select.$from." offset $start_page limit $show_data ";
            
            $run= $this->db->query($sql2);
            if($show)
            {
                echo $this->db->getLastQuery();
            }            
            $sql = " select count(*) from (".$with.$select.$from.") t" ;
            $data['result']=$run->getResultArray();
            $count=$this->db->query($sql)->getFirstRow('array');           
            $data['count']=!empty($count)?$count['count']:0; 
            $data['offset']=$start_page;
            // print_var($data['count']);die;
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();die;
        }
    }

    function row_sql($sql)
    {
        try{
            $data = $this->db->query($sql);
            return $data->getResultArray();
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    

}
