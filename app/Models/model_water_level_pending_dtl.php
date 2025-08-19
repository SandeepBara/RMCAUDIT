<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_water_level_pending_dtl extends Model 
{
    protected $db;
    protected $table = 'tbl_level_pending';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'apply_connection_id','sender_user_type_id','receiver_user_type_id','forward_date','forward_time', 'created_on','remarks','verification_status', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function insertData(array $data)
    {

        $result= $this->db->table($this->table)
                 ->insert($data);       
       // echo $this->getLastQuery();
        $insert_id=$this->db->insertID();
        return $insert_id;

    }
    
    // public function updatelevelpendingdtl($input)
    // {
	// 	print_r($input);
    //     $builder=$this->db->table($this->table)
    //                     ->select('id')
    //                     ->where('apply_connection_id', $input['apply_connection_id'])
    //                     ->orderBy('id','DESC')
    //                     ->get();
    //     $builder =  $builder->getFirstRow("array");
    //     $level_pending_id = $builder['id'];
    //     $builder = $this->db->table($this->table)
    //                 ->where('id', $level_pending_id)
    //                 ->where('apply_connection_id', $$input['apply_connection_id'])
    //                 ->update([
    //                         'remarks'=>$input['remarks'],    
    //                         "forward_date"=>$input["forward_date"],
    //                         "forward_time"=>$input["forward_time"],
    //                         ]);
	// 	echo $this->db->getLastQuery();
    // }

    public function insrtlevelpendingdtl($input){
		
        $builder = $this->db->table($this->table)
                ->insert([
                  "apply_connection_id"=>$input["apply_connection_id"],
                  "sender_user_type_id"=>$input["sender_user_type_id"],
                  "receiver_user_type_id"=>$input["receiver_user_type_id"],
                  //"forward_date"=>$input["forward_date"],
                  //"forward_time"=>$input["forward_time"],
                  "created_on"=>$input["created_on"],
                  "send_date"=> $input["forward_date"],
                  //"remarks"=>$input["remarks"],
                  "emp_details_id"=>$input["emp_details_id"],
                  "verification_status"=> 0,
                  "status"=> '1'
				  ]);
			//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function updatebacktocitizenById($input){
        $builder = $this->db->table($this->table)
                            ->where('md5(id::text)', $input['level_pending_dtl_id'])
                            ->update([
                                        'created_on'=> $input['created_on'], 
                                        'remarks'=> $input['remarks'],    
                                        'verification_status'=> $input['verification_status'],
                                        'receiver_user_id'=>$input['receiver_user_id']??null,
                                ]);
        //echo $this->db->getLastQuery();
        return $builder;
        
    }
    public function tbl_level_sent_back_dtl($input)
    { 
        $builder = $this->db->table('tbl_level_sent_back_dtl')
                            ->insert([
                                'level_id' => $input['level_id'], 
                                'apply_connection_id'=> $input['apply_connection_id'], 
                                'sender_user_type_id' => $input['sender_user_type_id'], 
                                'receiver_user_type_id' => $input['receiver_user_type_id'], 
                                'forward_date'  => $input['forward_date'], 
                                'forward_time' => $input['forward_time'], 
                                'created_on' => $input['created_on'], 
                                'remarks' => $input['remarks'], 
                                'verification_status'  => $input['verification_status'], 
                                'emp_details_id' => $input['emp_details_id'], 
                                'status' => $input['status'], 
                                'send_date' => $input['send_date'], 
                                'receiver_user_id' => $input['receiver_user_id'],
                                'ip_address' => $input['ip_address']??null, 
                            ]);
        return $insert_id = $this->db->insertID();      
    }
    public function updateRejectStatusById($input){
        $builder = $this->db->table($this->table)
                            ->where('md5(id::text)',$input['level_pending_dtl_id'])
                            ->update([
                                    'remarks'=>$input['remarks'],    
                                    'verification_status'=>$input['verification_status'],
                                    'receiver_user_id'=>$input['receiver_user_id']??null,
                                    ]);
        // echo $this->db->GetLastQuery();
        return $builder;
        
    }


    public function backtocitizen_dl_remarks_by_con_id($apply_connection_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',0)
                        ->where('receiver_user_type_id',12)
                        ->where('verification_status ',2)
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    

    public function updatelevelpendingById($input)
    {
         $builder = $this->db->table($this->table)
                            ->where('md5(id::text)', $input['level_pending_dtl_id'])
                            ->update([
                                    'verification_status'=> $input['verification_status'],
                                    'remarks'=> $input['remarks'] ?? NULL,
                                    'forward_date'=> 'NOW()',
                                    'forward_time'=> 'NOW()',
                                    'receiver_user_id'=>$input['receiver_user_id']??null,
                                    ]);
        //echo $this->db->getLastQuery();die();
        return $builder;

    }
    

    public function approved_dl_remarks_by_con_id($apply_connection_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',12)
                        //->where('verification_status ',0)
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0]??array();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function forward_remarks_by_con_id($apply_connection_id,$sender_user_type_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',$sender_user_type_id)
                        ->where('verification_status ',0)
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0]??array();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function approved_je_remarks_by_con_id($apply_connection_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',13)
                        //->where('verification_status ',0)
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0]??null;
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function approved_sh_remarks_by_con_id($apply_connection_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',14)
                        //->where('verification_status ',0)
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0]??array();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function approved_ae_remarks_by_con_id($apply_connection_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('remarks')
                        ->where('sender_user_type_id',15)
                        //->where('verification_status ',0)
                        ->where('apply_connection_id', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0]??array();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function si_level_verify_dtls($apply_connection_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('verification_status')
                        ->where('receiver_user_type_id',13)
                        ->where('md5(apply_connection_id::text)', $apply_connection_id)
                        ->where('status',1)
                        ->orderBy('id','DESC')
                        ->get()
                        ->getResultArray()[0]??array();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function level_pending_insrt($input){
		//print_r($input);
        $builder = $this->db->table($this->table)
                ->insert([
                  "apply_connection_id"=>$input["apply_connection_id"],
                  "sender_user_type_id"=>$input["sender_user_type_id"],
                  "receiver_user_type_id"=>$input["receiver_user_type_id"],
                  "send_date"=>$input["forward_date"],
                  "created_on"=>$input["created_on"],
                  "emp_details_id"=>$input["emp_details_id"],
                  "verification_status"=>0,
                  "status"=>'1'
				  ]);
			//echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }

    public function level_pending_updt($input,$where=array())
    {
        if(!empty($where))
        {
             $builder = $this->db->table($this->table)
                              ->where ($where)
                              ->update([
								'remarks'=>$input['remarks'],
								"forward_date"=>$input["forward_date"],
								"forward_time"=>$input["forward_time"],								
								'verification_status'=>$input["verification_status"],
                                'receiver_user_id'=>$input["receiver_user_id"]??null,
								]);  
                                echo($this->db->getLastQuery());
                                return $builder;
        }
        return $builder = $this->db->table($this->table)
						->where('apply_connection_id',$input['apply_connection_id'])
						->where('receiver_user_type_id',$input['sender_user_type_id'])
						->update([
								'remarks'=>$input['remarks'],
								"forward_date"=>$input["forward_date"],
								"forward_time"=>$input["forward_time"],								
								'verification_status'=>$input["verification_status"]
								]);
        
    }

    public function getLevelBtcz($apply_connection_id)
    {
        return $builder = $this->db->table($this->table)
                        ->select('*')
						->where('apply_connection_id', $apply_connection_id)
						->where('verification_status', 2) //Back to citizen
                        ->where('status', 1)
                        ->get()
                        ->getFirstRow('array');
        
    }

    /* 29/01/22 by sandeep */
    public function getDataNew($where=array(),$column=array('*'),$tbl='',$groupBy=array('id'),$orderBy=array('id'=>'ASC'))
    {
        if($tbl=='')
            $tbl=$this->table;
        try{
            $data = array();
            $builder = $this->db->table($tbl)
                        ->select($column);
            if(!empty($where))
            {
                $builder=$builder->where($where);
            }
            $builder=$builder->groupBy($groupBy);
            foreach($orderBy as $key=>$val)
            {
               $builder=$builder->orderBy($key,$val);
            }
            $data=$builder->get()->getResultArray();
            if(sizeof($data)==1)
            {
                $data = $data[0];
            }
            else
                $data = $data;
            return $data;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

}