<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class WaterViewConsumerModel extends Model
{
	protected $db;
    protected $table = 'view_consumer';
  
	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function consumerDetails($consumer_id)
    {
        if(is_numeric($consumer_id))
            $consumer_id = md5($consumer_id);
        $sql="select * from view_consumer where md5(id::text)='".$consumer_id."'";
        $run=$this->db->query($sql);
        $result=$run->getFirstRow("array");
        //echo $this->getLastQuery();
        //echo"<pre>";print_r($result);echo"</pre>";
        return $result;

    }
}
?>
