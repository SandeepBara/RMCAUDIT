<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class Siginsw_water_model extends Model
{
    protected $db;
    protected $table = 'tbl_single_window_singin';   
    protected $LogingCounter;
    public function __construct(ConnectionInterface $db)
    {
        $session=session();
        $this->db = $db;
        $this->LogingCounter=0;
    }
    public function InsertData(array $inputs)
    {
        $this->db->table('tbl_single_window_singin')
                ->insert($inputs);
        // echo $this->db->getLastQuery();die;
        $insert_id=$this->db->insertID();
        return $insert_id;
    }
    public function updateData(array $inputs, array $where)
    {
        try{
            $data = $this->db->table($this->table)
                    ->where($where)
                    ->update($inputs);
            return $data;
                
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            
        }
    }
    public function getData(array $where)
    {
        $data = $this->db->table($this->table)
                ->select('*')
                ->where($where)
                ->orderBy('id','desc')
                ->get()
                ->getFirstRow('array');
        return $data; 
        
    }
    public function row_sql($sql)
    {
        try
        {
            $data = $this->db->query($sql)->getResultArray();
            // echo $this->db->getLastQuery(); 
            // print_var($data);
            return $data;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }  
    
    public function loginSinglewindowCitizen($emp_id,$ip)
    {
        $username = env('single_indow_user');
        $password = env('single_indow_password');
        {
            $arr=["username"=>$username,"password" => $password];
            $url =env('single_indow_sigin_url');
            $http = env('single_indow_sigin_http');
            $login = json_decode(httpPost($url,$arr,$http),true);           
            if($login['status']!='Success')
            {
                if($this->LogingCounter<10)
                {
                    $this->loginSinglewindowCitizen($emp_id,$ip); 
                    $this->LogingCounter+=1;
                }
            }
            session()->set('validity',$login['validity']);
            session()->set('token',$login['token']);
            $tbl_single_window_singin=[
                "user_name"     =>$username,
                "password"      => $password,
                "url"           => $http.$url,
                "res_status"    =>$login['status'],
                "res_message"   =>$login['status']??'',
                "token"         => $login['token']??'',
                "validity"      =>$login['validity']??null,
                "user_id"       =>$emp_id??null,  
                "ip_address"    => $ip??null         
            ];
            
            $single_window_singin_id = $this->InsertData($tbl_single_window_singin);
            $login['single_window_singin_id'] = $single_window_singin_id ;  
            // print_var($login);die;          
            return $login;
        } 
    }
  
}
?>