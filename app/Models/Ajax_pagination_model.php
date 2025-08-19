<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class Ajax_pagination_model extends Model
{
	protected $db;
    protected $table = 'tbl_prop_dtl';
    protected $allowedFields = [''];

	public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    function count_all()
	{
	$query = $this->db->get("tbl_prop_dtl");
	return $query->num_rows();
	}

	function fetch_details($limit, $start)
	{
	$output = '';
	$this->db->select("*");
	$this->db->from("tbl_prop_dtl");
	$this->db->order_by("name", "ASC");
	$this->db->limit($limit, $start);
	$query = $this->db->get();
	$output .= '
	<table class="table table-bordered">
	<tr>
	<th>Country ID</th>
	<th>Country Name</th>
	</tr>
	';
	foreach($query->result() as $row)
	{
	$output .= '
	<tr>
	<td>'.$row->id.'</td>
	<td>'.$row->name.'</td>
	</tr>
	';
	}
	$output .= '</table>';
	return $output;
	}
}

?>
