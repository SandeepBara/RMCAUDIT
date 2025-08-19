<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class model_view_water_application_details extends Model
{
    protected $db;
    protected $table = 'view_water_application_details';
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function applyList($from_date,$to_date,$ward_id,$status=array(),$show_data = null)
    {
        try
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
            $start_page = $page*$show_data; echo($show_data);

            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_date >=', $from_date)
                        ->where('apply_date <=', $to_date)
                        ->where('ward_id', $ward_id)
                        ->where('status !=', 0)
                        ->where('apply_from !=', 'Existing');
            $builder1 = $this->db->table($this->table)
                        ->select('count(id)')
                        ->where('apply_date >=', $from_date)
                        ->where('apply_date <=', $to_date)
                        ->where('ward_id', $ward_id)
                        ->where('status !=', 0)
                        ->where('apply_from !=', 'Existing');
            if(count($status)>0)
            {
                foreach($status as $key=>$value)
                {
                    //print_r($status);
                    $builder = $builder->whereIn($key,$value);
                    $builder1 = $builder1->whereIn($key,$value);

                }
            }

            // $builder = $builder->orderBy('id', 'DESC')
            //             ->get();


            $builder = $builder->orderBy('id','DESC')  
                                ->limit( $show_data,$start_page) 
                                ->get();           
            //echo $this->db->getLastQuery();
            $data['result']=$builder->getResultArray();

            $builder1 = $builder1->get();
            $builder1=$builder1->getFirstRow('array');
            //echo"<br>";echo $this->db->getLastQuery();print_r($builder1);
            $data['count']=$builder1['count'];
            $data['offset']=$start_page;
            return $data;
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }
    public function applyListByDate($from_date,$to_date,$status=array(),$show_data = null)
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
        $start_page = $page*$show_data; //echo($show_data);

        //$sql = $sqlLocal." LIMIT $show_data OFFSET $start_page;";

        $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_date >=', $from_date)
                        ->where('apply_date <=', $to_date)
                        ->where('status !=', 0)
                        ->where('apply_from !=', 'Existing');
        $builder1 = $this->db->table($this->table)
                        ->select('count(id)')
                        ->where('apply_date >=', $from_date)
                        ->where('apply_date <=', $to_date)
                        ->where('status !=', 0)
                        ->where('apply_from !=', 'Existing');
        if(count($status)>0)
            {
                foreach($status as $key=>$value)
                {
                    //print_r($builder);
                    $builder = $builder->whereIn($key,$value);
                    $builder1 = $builder1->whereIn($key,$value);
                }
            }
        

        $builder1=$builder1->get()->getFirstRow('array');

        $builder= $builder->orderBy('id','DESC')
                        ->limit( $show_data,$start_page) 
                        ->get();
       

            //echo $this->db->getLastQuery();
           //return $builder->getResultArray();
            $data['result']=$builder->getResultArray();

            $data['count']=$builder1['count'];
            $data['offset']=$start_page;
            return $data;
        
    }

    public function getData($where=array(),$column='*',$order_by=array(),$gruop_by=array())
    {
        $builder = $this->db->table($this->table)
                        ->select($column);
            foreach($where as $key=>$value)
            {
               
                $builder = $builder->where($key,$value);
            }
            foreach($order_by as $key=>$value)
            {
               
                $builder = $builder->orderBy($key,$value);
            }
            foreach($gruop_by as $key=>$value)
            {
                
                //$builder = $builder->group_by($key,$value);
            }
            $builder = $builder->get();
            //echo(count($where));            
            //echo $this->db->getLastQuery();
           //return $builder->getResultArray();
    }
}
?> 