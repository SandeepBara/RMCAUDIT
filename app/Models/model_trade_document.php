<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_trade_document extends Model
{
	protected $db;
    protected $table = 'tbl_document';
    protected $allowedFields = ['id','doc_name','doc_for','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getDocumentDetails($application_type_id,$show)
    {
        try
        {
            $builder = 'select doc_for,mandatory from tbl_document where application_type_id='.$application_type_id.' and status=1 and show in ('.$show.') group by doc_for,mandatory';
            $ql= $this->query($builder);
            //echo $this->getLastQuery();
            return $ql->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getDocumentDetailsCitizen($application_type_id, $show)
    {
        try
        {
            //89, 90, 91, 92 -- Application Form
            $builder = 'select doc_for,mandatory from tbl_document 
            where application_type_id='.$application_type_id.' and status=1 and show in ('.$show.') and id not in (89, 90, 91, 92)
            group by doc_for,mandatory';
            $ql= $this->query($builder);
            //echo $this->getLastQuery();
            return $ql->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }


    // mandatory doc count 
    public function Documentmandatory_count($application_type_id, $doc_id)
    {
        try
        {
            $builder = 'select count(id) from tbl_document where application_type_id='.$application_type_id.' and status=1 and mandatory = 1 and id in('.$doc_id.')';
            $ql= $this->query($builder);
            //echo $this->getLastQuery();
            return $ql->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    //doc count 
    public function Document_count($application_type_id,$doc_id){
        
        try{
             $builder = 'select count(id) from tbl_document where application_type_id='.$application_type_id.' and status=1  and id in('.$doc_id.')';
            $ql= $this->query($builder);
            $this->getLastQuery();
            return $ql->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

     public function getDocumentcount($application_type_id,$show)
     {
        try{
            $builder = $this->db->table('(SELECT  doc_for FROM tbl_document WHERE application_type_id = '.$application_type_id.' AND status = 1 and show in ('.$show.') GROUP BY doc_for) subdoc')
                        ->selectCount('doc_for')                                                                
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDocumentcountCitizen($application_type_id, $show)
    {
        try{
            $builder = $this->db->table('(SELECT  doc_for FROM tbl_document WHERE application_type_id = '.$application_type_id.' AND status = 1 and show in ('.$show.') and id not in (89, 90, 91, 92) GROUP BY doc_for) subdoc')
                        ->selectCount('doc_for')                                                                
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getDocumentcountMandatory($application_type_id,$show)
    {
        try
        {
            $builder = $this->db->table('(SELECT  doc_for FROM tbl_document WHERE application_type_id = '.$application_type_id.' AND status = 1 and show in ('.$show.') and mandatory = 1  GROUP BY doc_for) subdoc')
                        ->selectCount('doc_for')                                                                
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getDocumentcountMandatoryCitizen($application_type_id,$show)
    {
        try
        {
            $builder = $this->db->table('(SELECT  doc_for FROM tbl_document WHERE application_type_id = '.$application_type_id.' AND status = 1 and show in ('.$show.') and mandatory = 1 and id not in (89,90,91,92) GROUP BY doc_for) subdoc')
                        ->selectCount('doc_for')                                                                
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

    public function getDocumentappList($application_type_id,$doc_for){
        try
        {
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name,doc_for,status')
                        ->where('doc_for', $doc_for)
                        ->where('application_type_id', $application_type_id)
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }
        catch(Exception $e)
        {
            return $e->getMessage();   
        }
    }

     public function getDocumentList($doc_for){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name,doc_for,status')
                        ->where('doc_for', $doc_for)
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
                        //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	public function sentBackDetailRemark($apply_con_id){
        $sql = "SELECT * FROM tbl_level_sent_back_dtl where apply_connection_id=$apply_con_id ORDER BY id desc";
        $builder = $this->query($sql);
        $result = $builder->getFirstRow();

        return json_decode(json_encode($result),true);
    }
	
    public function getIdPoorfDocumentList(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('upper(doc_for)', 'IDENTITY PROOF')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
                    //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function get_doc_Business(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Business Premises')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_tanent(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Tanented')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function get_doc_Sapat(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Sapat Patra')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_Electricity(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Electricity Bill')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_Application(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Application Form')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_Solid(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Waste Document')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_NOC(){
		$data['doc']= ['NOC','NOC Affidavit'];
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->whereIn('doc_for', $data['doc'])
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_partnership(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Partnership Firm')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_tan(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Tanented')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_doc_pvt(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Pvt. Ltd. OR Ltd. Company')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function get_doc_pvtltd(){
        try{
            $builder = $this->db->table($this->table)
                        ->select('id, doc_name')
                        ->where('doc_for', 'Pvt. Ltd. OR Ltd. Company')
                        ->where('status', 1)
                        ->orderBy('id','ASC')
                        ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
     
    public function get_docname_data_bydoc_id($id,$doc_type)
    { 
       // $this->db1 = $this->load->database('db', TRUE);

        $sql="SELECT d.doc_name,d.id,t.transfer_mode FROM tbl_saf_doc_collected_dtl s join tbl_doc_mstr d on(s.doc_mstr_id=d.id) join tbl_transfer_mode_mstr t on(d.doc_id=t.id) where d.doc_type='$doc_type'  and s.saf_distributed_dtl_id='$id'";
        $q = $this->db->query($sql);        
        $result2 = $q->getResultArray();
        return $result2;
    }
}
?>