<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_trade_licence extends Model 
{
    protected $db;
    protected $table = 'view_trade_licence';
    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getDatabyid2($id)
    {
        
    	$result=$this->db->table('tbl_apply_licence as al')
    				->select('al.*')
                    // ->join('tbl_application_type_mstr as atm', 'atm.id = al.application_type_id')
                    // ->join('tbl_firm_type_mstr as ftm', 'ftm.id = al.firm_type_id')
    				//->where('status',1)
                    ->where('md5(al.id::text)', $id)
                    ->orderBy('al.id','ASC')
    				->get()
    				->getFirstRow("array");

                   // echo $this->db->getLastQuery();
    	return $result;

    }

    public function getDatabyid($id)
    {
        
        $result=$this->db->table($this->table)
                    ->select('*')
                    //->where('status',1)
                    ->where('md5(id::text)', $id)
                    ->orderBy('id','ASC')
                    ->get()
                    ->getFirstRow("array");

                   // echo $this->db->getLastQuery();
        return $result;

    }
    public function getFirmTypebyid($firm_type_id)
    {
        
        $result=$this->db->table('tbl_firm_type_mstr')
                    ->select('firm_type')
                    ->where('id', $firm_type_id)
                    // ->orderBy('al.id','ASC')
                    ->get()
                    ->getFirstRow("array");

                   // echo $this->db->getLastQuery();
        return $result['firm_type'];

    }
    public function getApplicationTypebyid($tbl_application_type_id)
    {
        
        $result=$this->db->table('tbl_application_type_mstr')
                    ->select('application_type')
                    ->where('id', $tbl_application_type_id)
                    // ->orderBy('al.id','ASC')
                    ->get()
                    ->getFirstRow("array");

                   // echo $this->db->getLastQuery();
        return $result['application_type'];

    }
    public function getNatureOfBussinessbyid($nature_of_bussiness)
    {
        
        $result=$this->db->table('tbl_trade_items_mstr')
                    ->select('trade_item')
                    ->where('id', $nature_of_bussiness)
                    // ->orderBy('al.id','ASC')
                    ->get()
                    ->getFirstRow("array");

                   // echo $this->db->getLastQuery();
        return $result['application_type'];

    }

    public function getlicencedatabydate($data)
    {
        $result=$this->db->table($this->table)
    				->select('*')
                    ->where('apply_date >=', $data['from_date'])
                    ->where('apply_date <=', $data['to_date'])
                    ->where('status', 1)
                    ->where('pending_status', 5)
                    ->orderBy('id','ASC')
    				->get()
    				->getResultArray();
        //echo $this->db->getLastQuery();
    	return $result;
    }

    public function getlicencedatabykeyword($data)
    {
        $keyword = $data['keyword'];
        $sql="select * from $this->table where update_status=0 and status=1 and (license_no like '%$keyword%' or application_no like  '%$keyword%' or firm_name like '%$keyword%' or owner_name like '%$keyword%' or mobile like '%$keyword%') order by id desc limit 100";
        $result=$this->db->query($sql)
    				->getResultArray();
        //echo $this->db->getLastQuery();
    	return $result;
    }

    public function row_query($sql,$parm=array())
    {
        $data=$this->db->query($sql,$parm)->getResultArray();
        // echo $this->db->getLastQuery();
        return$data;
    }

    public function row_query2($sql,$show_data = null)
    {
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
        $start_page = $page * $show_data;

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
            //$data['count']=!empty($count)?100:0; 
            $data['offset']=$start_page;
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function getPropetyIdByNewHolding($holding_no)
    {
        $sql="select md5(id::text) as id 
                from view_prop_detail
                where  upper(new_holding_no) = upper('".$holding_no."')";

        $run=$this->db->query($sql);
        $result=$run->getFirstRow('array');
        // echo $this->getLastQuery();
        return $result;

    }
}