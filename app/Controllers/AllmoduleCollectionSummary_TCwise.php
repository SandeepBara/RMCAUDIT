<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\TradeTransactionModel;
use App\Models\model_ward_mstr;
use App\Models\model_emp_details;
use App\Models\model_view_emp_details;

class AllmoduleCollectionSummary_TCwise extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $TradeTransactionModel;
    protected $model_ward_mstr;
	protected $model_emp_details;
	protected $model_view_emp_details;

    public function __construct(){
        
        parent::__construct();
    	helper(['db_helper','form']);
        if($db_name = dbConfig("trade")){
            $this->db_trade = db_connect($db_name); 
        }
		if($db_name = dbConfig("property")){
            $this->db_property = db_connect($db_name);            
        }
		if($db_name = dbConfig("water")){
            $this->db_water = db_connect($db_name);            
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->TradeTransactionModel = new TradeTransactionModel($this->db_trade);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_emp_details = new model_emp_details($this->dbSystem);
		$this->model_view_emp_details = new model_view_emp_details($this->dbSystem);

    }

    function __destruct() {
		$this->db_trade->close();
		$this->db_property->close();
        $this->db_water->close();
        $this->dbSystem->close();
	}
    
    
	public function collection_summary_details(){
        $data =(array)null;
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
 		$data['team_leader'] = $this->model_view_emp_details->get_team_leader();
        return view('report/allmodule_collection_summary_tcwise',$data);
    } 
	

	public function get_tax_collector_ajax()
	{
		if($this->request->getMethod()=='post'){
 
            try{
                $team_leader_id = sanitizeString($this->request->getVar('team_leader_id'));          
                $data['tax_collector'] = $this->model_view_emp_details->get_tax_collector($team_leader_id);
                $output = "";
                $output.=  '<option value="">Select</option>';
                foreach($data['tax_collector'] as $value)
                {
                   $output.=  '<option value="'.$value['id'].'">'.$value['emp_name'].'</option>';
                }
                 
                return json_encode($output);
            }catch(Exception $e){

            }
        }
	}
	
    public function get_collection_details_ajax()
    {
        if($this->request->getMethod()=='post'){
 
            try{
                $from_date = sanitizeString($this->request->getVar('from_date'));          
                $to_date = sanitizeString($this->request->getVar('to_date'));          
                $tax_collector_id = sanitizeString($this->request->getVar('tax_collector_id'));  
                if($tax_collector_id!="")
                {        
                   $data['collection_details'] = $this->TradeTransactionModel->get_collection_details_with_id($tax_collector_id,$from_date,$to_date);
				}
				else{
					$data['collection_details'] = $this->TradeTransactionModel->get_collection_details_with_id('ALL',$from_date,$to_date);
				}
                $output = "";
                $sn=1;
				$propcount=0;$propcll=0;$wtrcount=0;$wtrcll=0;$trdcount=0;$trdcll=0;$totlcll=0;
                
                if($data['collection_details']!=0)
                {
					foreach($data['collection_details'] as $value)
					{
						$output .= '<tr>
										<td id="leftTd" class="col-sm-1">'.$sn++.'</td>
										<td id="leftTd">'.$value['emp_name'].'</td>
                                        <td id="leftTd">'.$value['m_cnt_property'].'</td>
                                        <td id="leftTd">'.$value['m_property_amount'].'</td>
										<td id="leftTd">'.$value['m_cnt_water'].'</td>
										<td id="leftTd">'.$value['m_water_amount'].'</td>
                                        <td id="leftTd">'.$value['m_cnt_trade'].'</td>
                                        <td id="leftTd">'.$value['m_trade_amount'].'</td>
                                        <td id="leftTd">'.$value['m_total_amount'].'</td>
									</tr>';
						$propcount = round(($propcount + $value['m_cnt_property']), 2) ;  
						$propcll = round(($propcll + $value['m_property_amount']), 2) ;
						$wtrcount = round(($wtrcount + $value['m_cnt_water']), 2) ;
						$wtrcll = round(($wtrcll + $value['m_water_amount']), 2) ;
						$trdcount = round(($trdcount + $value['m_cnt_trade']), 2) ;
						$trdcll = round(($trdcll + $value['m_trade_amount']), 2) ;
						$totlcll = round(($totlcll + $value['m_total_amount']), 2) ;
					}
                }
                else{
                    $output .= '<tr>
                                    <td id="leftTd" class="col-sm-1" colspan="9">No Result</td>
								</tr>';
                }

                $response = array(
                    "output_tbl" => $output,"propcount" => $propcount,"propcll" => $propcll,"wtrcount" => $wtrcount,
					"wtrcll" => $wtrcll,"trdcount" => $trdcount,"trdcll" => $trdcll,"totlcll" => $totlcll,
                    "from_date_to_date" =>'From '. date("d-m-Y", strtotime($from_date)) .' To '. date("d-m-Y", strtotime($to_date))
                    
                );

                return json_encode($response);
            }catch(Exception $e){

            }
        } 
    }
	
}
?>
