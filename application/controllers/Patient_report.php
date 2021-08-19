<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_report extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array(
			'patient_model',
			'doctor_model',
            'document_model'
		));

		if ($this->session->userdata('isLogIn') == false
			|| $this->session->userdata('user_role') != 1)
			redirect('login');
	}

	public function index()
	{
		// echo $this->session->userdata('isadmin');
		// exit;
		if($this->session->userdata('isadmin')==0 ){
			$hospital_name = $this->db->select("*")->from("user")->where("user_id",$this->session->userdata('user_id'))->get()->row();
		}else if($this->session->userdata('isadmin')==1){
			$hospital_name = $this->db->select("*")->from("user")->where("user_id",$this->session->userdata('hospital_id'))->get()->row();
		}
		// print_r($hospital_name);
		// exit;
		$data['title'] = display('patient_list');
		$data['patients'] = $this->patient_model->read();
		$data['content'] = $this->load->view('patient_report',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}


    function search(){
		$date1 = $this->input->get('from_date');
		$date2 = $this->input->get('to_date');
		$hospital_name= "";
		$id = $this->session->userdata('user_id');
		$created_by_id = $this->session->userdata('created_by');
        $isadmin = $this->session->userdata('isadmin');
        if($isadmin == 1){
        	$id = $created_by_id;
        }

	
		$this->db->select("*");
		$this->db->from("patient");
		$this->db->where('create_date >=', $date1);
		$this->db->where('create_date <=', $date2);		
		$this->db->where('hospital_id',$id);
		$patients = $this->db->get()->result();
		//echo $this->db->last_query();exit;
		$html = "";
		if (!empty($patients)) {
            $sl = 1;
            $profile_picture ="";
                foreach ($patients as $patient) {
                	if($patient->picture!=''){
                		$profile_picture =  rtrim(base_url().$patient->picture, "/");;
                	}else{
                	 $profile_picture = base_url().'assets/images/patient/2017-01-16/p5.png';
                	}
                	if($patient->status==1){
                		$patient->status = "Active";
                	}else{
						$patient->status = "Inactive";
                	}
          $html .= '<tr style="border-bottom: 1px solid #ddd;" class="hovertr" >
            <td >
              <img style="width: 50px;  height: 50px;   border-radius: 50%;"  src='.$profile_picture.'>
            <!-- <div class="kpull-left patient-td-div"></div> -->
            </td>
            <td >
              <div class="kpull-left"><div class="word-break"> <span data-id='.$patient->patient_id.' class="fa fa-circle" data-toggle="tooltip" title="Patient online"> </span> <span class="text-primary">'.$patient->fname.' <br> <a href="#" class="color-black"> </a> </span>
              </div></div>
            </td>
           <td ><span class="text-primary">'.$patient->lname.'</span></td>
            <td >'. date('d/m/Y',strtotime($patient->date_of_birth)).' </td><td onclick="patient_info('.$patient->patient_id.')">'. $patient->sex.'</td>
            <td >'. $patient->address1.', '.$patient->city.'- '.$patient->zip.', '. $patient->state
            .', '.$patient->country.' <br>
              <div style="display:flex; margin-top:15px;">
                <span class="light-grey" style="width:50%;">M : <label style="color:#000;font-weight:400;color:#5785e8;">'.$patient->mobile.'</label></span>
                <span class="light-grey ml-15" style="width:50%;">H : <label style="color:#000;font-weight:400;color:#5785e8;">'.$patient->phone.'</label></span>
              </div>
            </td>
            <td >'.date('d/m/Y',strtotime($patient->create_date)).'</td>
            <td>'.$patient->status.'</td></tr>';


           		$sl++;
                } 
            }else{ 
            	$html = '<tr style="border-bottom: 1px solid #ddd;" class="hovertr text-center"><td colspan="8"> Patient Not Found. </td></tr>';
            }
		echo $html;
	}

	function patient_report_pdf()
	{
		$from_date 	= ($this->uri->segment(3)!="") ? date("Y-m-d",strtotime($this->uri->segment(3))) : "";
		$to_date 	= ($this->uri->segment(4)!="") ? date("Y-m-d",strtotime($this->uri->segment(4))) : "";	
		$id = $this->session->userdata('user_id');
		$created_by_id = $this->session->userdata('created_by');
        $isadmin = $this->session->userdata('isadmin');
        $html = "";
        if($isadmin == 1){
        	$id = $created_by_id;
        }

		$this->load->library('dompdf_gen');
		$customPaper = array(0,0,1024,1000);
		$this->dompdf->set_paper($customPaper);
		//	$this->dompdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'landscape');
		$pdfname = "Patient_report" . date('YmdHis') . '.pdf';
		$html = '<style>
								table {
									display: table; border-collapse: collapse;
								}
								.pricedetail tr td
								{
										font-family:Verdana;


								}
								.pricedetail tr th
								{
										font-family:Verdana;

								}
						</style>
            			<h3 style="text-align:center;">Patient Report</h3>
						<table border="1" width="100%" class="pricedetail" style="margin-top: 1px;">
								 <tr>
								 	<th width="5%" class="text-center"></th>
									<th width="15%">Patient Name</th>															
									<th class="text-center">DOB</th>
									<th class="text-center">Gender</th>
									<th width="30%" class="text-center">Contact Info</th>
									<th>Join Date</th>
									<th width="12%" class="text-center">Status</th>
								</tr>';
		if($from_date !="" && $to_date!=""){		
			$this->db->select("*");
			$this->db->from("patient");
			$this->db->where('create_date >=', $from_date);
			$this->db->where('create_date <=', $to_date);		
			$this->db->where('hospital_id',$id);
			$patients = $this->db->get()->result_array();
			$profile_picture ="";
			foreach ($patients as $patientdetail) { 
				

			 	if($patientdetail["picture"]!=''){
                		$profile_picture =  rtrim(base_url().$patientdetail["picture"], "/");;
                	}else{
                	 $profile_picture = base_url().'assets/images/patient/2017-01-16/p5.png';
                	}
                	if($patientdetail["status"]==1){
                		$patientdetail["status"] = "Active";
                	}else{
						$patientdetail["status"] = "Inactive";
                	}	
				
					$html.='<tr style="border-bottom: 1px solid #ddd;" class="hovertr">';
					$html.='<td>
              					<img style="width: 50px;  height: 50px;   border-radius: 50%;"  src='.$profile_picture.'>
            					<!-- <div class="kpull-left patient-td-div"></div> -->
            			</td>';
					 $html.='<td><div class="kpull-left"><div class="word-break">';
					 $html.='<span class="text-primary">'.$patientdetail['prefix']."  ".$patientdetail['fname']."  ".$patientdetail['lname'].'</span>';
					 $html.='</div></div></td>';



					 $html.='<td><span class="text-primary">'.date("d-m-Y",strtotime($patientdetail['date_of_birth'])).'</span></td>';
					 $html.='<td><div class="kpull-left"><div class="word-break">';
					 $html.='<span class="text-primary">'.$patientdetail['sex'].'</span>';
					 $html.='</div></div></td>';


					 $html.='<td>'.$patientdetail['address1'].', '.$patientdetail['city'].'- '.$patientdetail['zip'].', '.$patientdetail['state'].', '.$patientdetail['country'].' <br>
				              <div style="margin-top:8px;">
				                <span class="light-grey" style="width:50%;">M : <label style="color:#000;font-weight:400;color:#5785e8;">'.$patientdetail['mobile'].'</label></span>
				                <span class="light-grey ml-15" style="width:50%;">H : <label style="color:#000;font-weight:400;color:#5785e8;">'.$patientdetail['phone'].'</label></span>
				              </div>
            			</td>';
					 $html.='<td>'.date('d/m/Y',strtotime($patientdetail['create_date'])).'</td>';
					 $html.='<td>'.$patientdetail['status'].'</td>					 					
					</tr>';
								
				}

						$html.='</table>';
					 /*echo $html;
					 	exit;*/
			}else{

			$this->db->select("*");
			$this->db->from("patient");					
			$this->db->where('hospital_id',$id);
			$patients = $this->db->get()->result_array();
			$profile_picture ="";
			foreach ($patients as $patientdetail) { 
				

			 	if($patientdetail["picture"]!=''){
                		$profile_picture =  rtrim(base_url().$patientdetail["picture"], "/");;
                	}else{
                	 $profile_picture = base_url().'assets/images/patient/2017-01-16/p5.png';
                	}
                	if($patientdetail["status"]==1){
                		$patientdetail["status"] = "Active";
                	}else{
						$patientdetail["status"] = "Inactive";
                	}	
				
					$html.='<tr style="border-bottom: 1px solid #ddd;" class="hovertr">';
					$html.='<td>
              					<img style="width: 50px;  height: 50px;   border-radius: 50%;"  src='.$profile_picture.'>
            					<!-- <div class="kpull-left patient-td-div"></div> -->
            			</td>';
					 $html.='<td><div class="kpull-left"><div class="word-break">';
					 $html.='<span class="text-primary">'.$patientdetail['prefix']."  ".$patientdetail['fname']."  ".$patientdetail['lname'].'</span>';
					 $html.='</div></div></td>';



					 $html.='<td><span class="text-primary">'.date("d-m-Y",strtotime($patientdetail['date_of_birth'])).'</span></td>';
					 $html.='<td><div class="kpull-left"><div class="word-break">';
					 $html.='<span class="text-primary">'.$patientdetail['sex'].'</span>';
					 $html.='</div></div></td>';


					 $html.='<td>'.$patientdetail['address1'].' '.$patientdetail['city'].' '.$patientdetail['zip'].' '.$patientdetail['state'].' '. $patientdetail['country'].' <br>
				              <div style=" margin-top:15px;">
				                <span class="light-grey" style="width:50%;">M : <label style="color:#000;font-weight:400;color:#5785e8;">'.$patientdetail['mobile'].'</label></span>
				                <span class="light-grey ml-15" style="width:50%;">H : <label style="color:#000;font-weight:400;color:#5785e8;">'.$patientdetail['phone'].'</label></span>
				              </div>
            			</td>';
					 $html.='<td>'.date('d/m/Y',strtotime($patientdetail['create_date'])).'</td>';
					 $html.='<td>'.$patientdetail['status'].'</td>					 					
					</tr>';
								
				}

						$html .='</table>';
					/* echo $html;
					 	exit;*/
			}			 	
		$load = $this->dompdf->load_html($html);
		//print_r($load);exit;
		$this->dompdf->render();
		$output = $this->dompdf->output();
		//print_r($output);exit;
		file_put_contents('pdf/' . $pdfname . '', $output);
		redirect('pdf/' . $pdfname . '', $output);
	}


	function patientdetail()
	{

		$pid = $this->input->get('p_id');
		$result = $this->db->select("*")->from("patient")->where("id",$pid)->get()->row();
		$result->contactinfo = $result->address1.' '.$result->country.' '.$result->city.' '.$result->state.' '.$result->zip;
		$result->primary_address1 = $result->primary_address_1.' '.$result->primary_country.' '.$result->primary_city.' '.$result->primary_state.' '.$result->primary_zip;
		$result->date_of_birth = date('d/m/Y',strtotime($result->date_of_birth));
		$result->immunization_effective_date = date('d/m/Y',strtotime($result->immunization_effective_date));
		if($result->dod!='' and $result->dod!='0000-00-00'){
			$result->dod = date('d/m/Y',strtotime($result->dod));
		}else{
			$result->dod = '';
		}
			$result->mobile = $result->mobile_prefix.''.$result->mobile;
			$result->create_date = date('d/m/Y',strtotime($result->create_date));
			$result->guarantor_dob = date('d/m/Y',strtotime($result->guarantor_dob));
			$result->status = ($result->status=='1')?'Active':'Inactive';

			echo json_encode($result);
	}
	public function familymember($pid=""){
		$data['title'] = display('patient_list');
		$data['patients'] = $this->patient_model->read();
		$result = $this->db->select("*")->from("family_member")->where("patient_id",$pid)->get()->result();
		//echo $this->db->last_query();exit;
		$data['familymembers'] = $result;

		$data['content'] = $this->load->view('familymember',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
	public function create_familymember($pid=""){
		$data['title'] =  "Create Family Member";
		$patient_id = $this->input->post('patient_id');
		if (!empty($_POST)) {
			$relation_to_patient = $this->input->post("relation_to_patient");
			$name = $this->input->post("name");
			$family_name = $this->input->post("family_name");
			$prefix = $this->input->post("prefix");
			$suffix = $this->input->post("suffix");
			$age = $this->input->post("age");
			$date_of_birth = date("Y-m-d",strtotime($this->input->post("date_of_birth")));
			$deceased_age = $this->input->post("deceased_age");
			$races = implode(",", $this->input->post("races"));
			$ethnicitiesstring = implode(",", $this->input->post("ethnicities"));
			$ethnicities  = trim($ethnicitiesstring,",");
			$gender = $this->input->post("gender");

			$postData = array("relation_to_patient"=>$relation_to_patient,"name"=>$name,"family_name"=>$family_name,"prefix"=>$prefix,"suffix"=>$suffix,"age"=>$age,"birth_date"=>$date_of_birth,"deceased_age"=>$deceased_age,"races"=>$races,"ethnicities"=>$ethnicities,"gender"=>$gender,"patient_id"=>$patient_id);
			$this->db->insert("family_member",$postData);
			$insert_id = $this->db->insert_id();
			if($insert_id){
				redirect(base_url().'patient/familymember/'.$patient_id);
			}
		}
		$races_result = $this->db->select("*")->from("races")->get()->result();
		$data['races_result'] = $races_result;
		$ethnicities_result = $this->db->select("*")->from("ethnicities")->get()->result();
		$data['ethnicities_result'] = $ethnicities_result;
		$data['content'] = $this->load->view('familymember_form',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
	public function delete_familymember($id,$patient_id){
		$this->db->where("member_id",$id);
		$sql = $this->db->delete("family_member");
		if ($sql) {
			redirect("patient/familymember/".$patient_id);
		}else{
			redirect("patient/familymember/".$patient_id);
		}
	}
	public function edit_familymember($id="")
	{
		$data['title'] = "Edit Family Member";

		if (!empty($_POST)) {
			$member_id = $this->input->post('member_id');
			$patient_id = $this->input->post('patient_id');
			$relation_to_patient = $this->input->post("relation_to_patient");
			$name = $this->input->post("name");
			$family_name = $this->input->post("family_name");
			$prefix = $this->input->post("prefix");
			$suffix = $this->input->post("suffix");
			$age = $this->input->post("age");
			$date_of_birth = date("Y-m-d",strtotime($this->input->post("date_of_birth")));
			$deceased_age = $this->input->post("deceased_age");
			$races = implode(",", $this->input->post("races"));
			$ethnicitiesstring = implode(",", $this->input->post("ethnicities"));
			$ethnicities  = trim($ethnicitiesstring,",");
			$gender = $this->input->post("gender");

			$postData = array("relation_to_patient"=>$relation_to_patient,"name"=>$name,"family_name"=>$family_name,"prefix"=>$prefix,"suffix"=>$suffix,"age"=>$age,"birth_date"=>$date_of_birth,"deceased_age"=>$deceased_age,"races"=>$races,"ethnicities"=>$ethnicities,"gender"=>$gender,"patient_id"=>$patient_id);
				$sql =  $this->db->where("member_id",$member_id);
						$this->db->update("family_member",$postData);
			if($sql){
				redirect(base_url().'patient/familymember/'.$patient_id);
			}
		}
		$races_result = $this->db->select("*")->from("races")->get()->result();
		$data['races_result'] = $races_result;
		$ethnicities_result = $this->db->select("*")->from("ethnicities")->get()->result();
		$data['ethnicities_result'] = $ethnicities_result;
		$editfamily_member = $this->db->select("*")->from("family_member")->where("member_id",$id)->get()->row();
		$data["editfamily_member"] = $editfamily_member;
		$data['content'] = $this->load->view('familymember_form',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
	function familymember_search($patient_id=""){

		$p_id = trim($this->input->get_post('p_id'));
$patient_id = $this->input->get_post('patient_id');
		if($p_id!=''){
		//$sql ="SELECT * FROM family_member WHERE name like '%".($p_id)."%' or family_name like '%".($p_id)."%' or relation_to_patient like '%".($p_id)."%' or gender like '%".($p_id)."%' and patient_id='$patient_id' ORDER BY member_id DESC";
$sql ="SELECT * FROM family_member WHERE (patient_id ='".$patient_id."') and  (name like '%".($p_id)."%' or relation_to_patient like '%".($p_id)."%' or races like '%".($p_id)."%' or gender like '%".($p_id)."%' or ethnicities like '%".($p_id)."%') ORDER BY member_id DESC";
			$query = $this->db->query($sql);
			 $searchdetail =  $query->result();
			 $msg ='';
			 //if(count($searchdetail)>0){
				 foreach ($searchdetail as $value) {
					 if($value->gender==''){
							$value->gender='Male';
						}

					 $msg.='<tr style="border-bottom: 1px solid #ddd;" class="hovertr">';

					 $msg.='<td><div class="kpull-left"><div class="word-break">';
					 $msg.='<span class="text-primary">'.$value->prefix."  ".$value->name.'</span>';
					 $msg.='</div></div></td>';

					 $msg.='<td><span class="text-primary">'.$value->relation_to_patient.'</span></td>';
					 $msg.='<td><div class="kpull-left"><div class="word-break">';
					 $msg.='<span class="text-primary">'.$value->races.'</span>';
					 $msg.='</div></div></td>';


					 $msg.='<td>'.$value->gender.'</td>';
					 $msg.='<td>'.$value->ethnicities.'</td>';

					$msg.='<td class="pt-15"><div class="btn-group" style="float: right;display: flex;"><a href="#" onclick="patient_info(\''.$value->patient_id.'\')" class="btn btn-xs btn-default" style="margin-right:10px;"><i class="fa fa-eye"></i></a><a href="'.base_url("patient/edit/$value->id").'" class="btn btn-xs btn-default" style="margin-right:10px;"><i class="fa fa-edit"></i></a>';
					$msg.='<a href="'.base_url("patient/delete/$value->id").'" class="btn btn-xs btn-danger" onclick="return confirm('.display('are_you_sure').')"><i class="fa fa-trash"></i></a></div></td>
										</tr>';


				 }

				echo json_encode($msg);
			}else{
				$sql ="SELECT * FROM family_member WHERE patient_id ='".$patient_id."' ORDER BY member_id DESC";
							$query = $this->db->query($sql);
							 $searchdetail =  $query->result();
							 $msg ='';
							 //if(count($searchdetail)>0){
								 foreach ($searchdetail as $value) {
									 if($value->gender==''){
											$value->gender='Male';
										}

									 $msg.='<tr style="border-bottom: 1px solid #ddd;" class="hovertr">';

									 $msg.='<td><div class="kpull-left"><div class="word-break">';
									 $msg.='<span class="text-primary">'.$value->prefix."  ".$value->name.'</span>';
									 $msg.='</div></div></td>';

									 $msg.='<td><span class="text-primary">'.$value->relation_to_patient.'</span></td>';
									 $msg.='<td><div class="kpull-left"><div class="word-break">';
									 $msg.='<span class="text-primary">'.$value->races.'</span>';
									 $msg.='</div></div></td>';


									 $msg.='<td>'.$value->gender.'</td>';
									 $msg.='<td>'.$value->ethnicities.'</td>';

									$msg.='<td class="pt-15"><div class="btn-group" style="float: right;display: flex;"><a href="#" onclick="patient_info(\''.$value->patient_id.'\')" class="btn btn-xs btn-default" style="margin-right:10px;"><i class="fa fa-eye"></i></a><a href="'.base_url("patient/edit/$value->id").'" class="btn btn-xs btn-default" style="margin-right:10px;"><i class="fa fa-edit"></i></a>';
									$msg.='<a href="'.base_url("patient/delete/$value->id").'" class="btn btn-xs btn-danger" onclick="return confirm('.display('are_you_sure').')"><i class="fa fa-trash"></i></a></div></td>
														</tr>';


								 }

								echo json_encode($msg);

			}
		}

	public function sendEmailAttachment($to, $subject, $htmlMessage) {
		if($this->session->userdata('isadmin')==0 ){
			$hospital_name = $this->db->select("*")->from("user")->where("user_id",$this->session->userdata('user_id'))->get()->row();
		}else if($this->session->userdata('isadmin')==1){
			$hospital_name = $this->db->select("*")->from("user")->where("user_id",$this->session->userdata('hospital_id'))->get()->row();
		}
			$this->load->library('email');
			$this->load->helper('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('sahil@gtimecs.org', $hospital_name->hospitalname);
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($htmlMessage);
		//	if (!empty($pdf_name)) {
		//		$this->email->attach($pdf_name);
		//	}
			@$this->email->send();
		}
		public function download_excel()
		{
						$this->load->library('excel');
						require_once './application/third_party/PHPExcel.php';
						require_once './application/third_party/PHPExcel/IOFactory.php';
						$objPHPExcel = new PHPExcel();




						$default_border = array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000'),
						);

						$acc_default_border = array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => 'c7c7c7'),
						);
						$outlet_style_header = array(
						'font' => array(
						'color' => array('rgb' => '000000'),
						'size' => 10,
						'name' => 'Arial',
						'bold' => true,
						),
						);
						$top_header_style = array(
						'borders' => array(
						'bottom' => $default_border,
						'left' => $default_border,
						'top' => $default_border,
						'right' => $default_border,
						),
						'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '150aec'),
						),
						'font' => array(
						'color' => array('rgb' => 'ffffff'),
						'size' => 15,
						'name' => 'Arial',
						'bold' => true,
						),
						'alignment' => array(
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						),
						);
						$style_header = array(
						'borders' => array(
						'bottom' => $default_border,
						'left' => $default_border,
						'top' => $default_border,
						'right' => $default_border,
						),
						'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '150aec'),
						),
						'font' => array(
						'color' => array('rgb' => 'ffffff'),
						'size' => 12,
						'name' => 'Arial',
						'bold' => true,
						),
						'alignment' => array(
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						),
						);
						$account_value_style_header = array(
						'borders' => array(
						'bottom' => $default_border,
						'left' => $default_border,
						'top' => $default_border,
						'right' => $default_border,
						),
						'font' => array(
						'color' => array('rgb' => 'ffffff'),
						'size' => 12,
						'name' => 'Arial',
						),
						'alignment' => array(
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						),
						);
						$text_align_style = array(
						'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						),
						'borders' => array(
						'bottom' => $default_border,
						'left' => $default_border,
						'top' => $default_border,
						'right' => $default_border,
						),
						'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '150aec'),
						),
						'font' => array(
						'color' => array('rgb' => 'ffffff'),
						'size' => 12,
						'name' => 'Arial',
						'bold' => true,
						),
						);

	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:AM1');
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Patient Report');

	$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($top_header_style);
		$objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($top_header_style);
		$objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($top_header_style);
$objPHPExcel->getActiveSheet()->getStyle('k1')->applyFromArray($top_header_style);
$objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($top_header_style);
$objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('W1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('X1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('Y1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('Z1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AA1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AB1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AC1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AD1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AE1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AF1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AG1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AH1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AI1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AJ1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AK1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AL1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AM1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AN1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AO1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AP1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AQ1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AR1')->applyFromArray($top_header_style);
	$objPHPExcel->getActiveSheet()->getStyle('AS1')->applyFromArray($top_header_style);



	$objPHPExcel->getActiveSheet()->setCellValue('A2', 'ID');
	$objPHPExcel->getActiveSheet()->setCellValue('B2', 'First Name');
	$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Last Name');
	$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Suffix');
	$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Second Last Name');
	$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Previous Name');
	$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Date Of Birth');
	$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Sex');
	$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Date Of Death');
	$objPHPExcel->getActiveSheet()->setCellValue('J2', 'SSN');
	$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Ethnicity');
	$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Address');
	$objPHPExcel->getActiveSheet()->setCellValue('M2', 'City');
	$objPHPExcel->getActiveSheet()->setCellValue('N2', 'State');
	$objPHPExcel->getActiveSheet()->setCellValue('O2', 'Country');
	$objPHPExcel->getActiveSheet()->setCellValue('P2', 'Zip');
	$objPHPExcel->getActiveSheet()->setCellValue('Q2', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('R2', 'Mobile');
	$objPHPExcel->getActiveSheet()->setCellValue('S2', 'Phone');
	$objPHPExcel->getActiveSheet()->setCellValue('T2', 'Work Phone');
	$objPHPExcel->getActiveSheet()->setCellValue('U2', 'Method Of Communication');
	$objPHPExcel->getActiveSheet()->setCellValue('V2', 'Relationshi To Guarantor');
	$objPHPExcel->getActiveSheet()->setCellValue('W2', 'Guarantor Name');
	$objPHPExcel->getActiveSheet()->setCellValue('X2', 'Guarantor Address');
	$objPHPExcel->getActiveSheet()->setCellValue('Y2', 'Guarantor DOB');
	$objPHPExcel->getActiveSheet()->setCellValue('Z2', 'Guarantor Sex');
	$objPHPExcel->getActiveSheet()->setCellValue('AA2', 'Guarantor SSN');
	$objPHPExcel->getActiveSheet()->setCellValue('AB2', 'Guarantor Primary Phone');
	$objPHPExcel->getActiveSheet()->setCellValue('AC2', 'Guarantor Primary Ext');
	$objPHPExcel->getActiveSheet()->setCellValue('AD2', 'Guarantor Secondary Phone');
	$objPHPExcel->getActiveSheet()->setCellValue('AE2', 'Guarantor Secondary Ext');
	$objPHPExcel->getActiveSheet()->setCellValue('AF2', 'Primary Care Name');
	$objPHPExcel->getActiveSheet()->setCellValue('AG2', 'Primary Care Phone');
	$objPHPExcel->getActiveSheet()->setCellValue('AH2', 'Primary Care Phone Type');
	$objPHPExcel->getActiveSheet()->setCellValue('AI2', 'Primary Care Address');
	$objPHPExcel->getActiveSheet()->setCellValue('AJ2', 'Patient Mother Name');
	$objPHPExcel->getActiveSheet()->setCellValue('AK2', 'Immunization Registery Status');
	$objPHPExcel->getActiveSheet()->setCellValue('AL2', 'Immunization Effective Date');
	$objPHPExcel->getActiveSheet()->setCellValue('AM2', 'data_privacy');
	$objPHPExcel->getActiveSheet()->setCellValue('AN2', 'Join Date');
	$objPHPExcel->getActiveSheet()->setCellValue('AO2', 'Status');
		$objPHPExcel->getActiveSheet()->setCellValue('AP2', 'Email Reminder');
		$objPHPExcel->getActiveSheet()->setCellValue('AQ2', 'Voice Reminders');
		$objPHPExcel->getActiveSheet()->setCellValue('AR2', 'SMS Reminders');
		$objPHPExcel->getActiveSheet()->setCellValue('AS2', 'My practice');




	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('J2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('K2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('L2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('M2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('N2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('O2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('P2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('Q2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('R2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('S2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('T2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('U2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('V2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('W2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('X2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('Y2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('Z2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AA2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AB2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AC2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AD2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AE2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AF2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AG2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AH2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AI2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AJ2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AK2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AL2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AM2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AN2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AO2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AP2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AQ2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AR2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('AS2')->applyFromArray($style_header);


		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

	$row = 3;
	//$custDtaData  = $this->panel_internal_model->panel_sale_list_all(0);
	//$doctors =	$this->doctor_model->read();
	$patients =	$this->patient_model->read();
	foreach ($patients as $value)
	{

	if($value->status==1){
		$value->status='Active';
	}else{
		$value->status='Inactive';
	}
	$email_reminder = $value->emailreminders;
	if($email_reminder=='1'){
		$email_reminder = 'Yes';
	}else{
		$email_reminder = 'No';
	}
	$voice_reminders = $value->voicereminders;
	if($voice_reminders=='1'){
		$voice_reminders = 'Yes';
	}else{
		$voice_reminders = 'No';
	}
	$smsreminders = $value->smsreminders;
	if($smsreminders=='1'){
		$smsreminders = 'Yes';
	}else{
		$smsreminders = 'No';
	}
	$value->date_of_birth = date('d/m/Y',strtotime($value->date_of_birth));
	$value->create_date = date('d/m/Y',strtotime($value->create_date));
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $value->patient_id);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $value->prefix.' '.$value->fname);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $value->lname);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $value->suffix);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $value->secondlastname);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $value->previousfname.' '.$value->previousmname.' '.$value->previouslname);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $value->date_of_birth);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $value->sex);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $value->dod);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $value->ssn);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $value->ethnicity_race);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $value->address1);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $value->city);
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$row, $value->state);
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$row, $value->country);
				$objPHPExcel->getActiveSheet()->setCellValue('p'.$row, $value->zip);
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, $value->email);
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$row, $value->mobile);
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$row, $value->phone);
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$row, $value->workphone);
				$objPHPExcel->getActiveSheet()->setCellValue('U'.$row, $value->methodofcommunication);
				$objPHPExcel->getActiveSheet()->setCellValue('V'.$row, $value->relationship_to_guarantor);
				$objPHPExcel->getActiveSheet()->setCellValue('W'.$row, $value->guarantor_fname.' '.$value->guarantor_mname.' '.$value->guarantor_lname);
				$objPHPExcel->getActiveSheet()->setCellValue('X'.$row, $value->guarantor_address1.' '.$value->guarantor_city.' '.$value->guarantor_state.' '.$value->guarantor_zip.' '.$value->guarantor_country);
				$objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, $value->guarantor_dob);
				$objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, $value->guarantor_sex);
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, $value->guarantor_ssn);
				$objPHPExcel->getActiveSheet()->setCellValue('AB'.$row, $value->guarantor_primary_phone);
				$objPHPExcel->getActiveSheet()->setCellValue('AC'.$row, $value->guarantor_primary_ext);
				$objPHPExcel->getActiveSheet()->setCellValue('AD'.$row, $value->guarantor_secondary_phone);
				$objPHPExcel->getActiveSheet()->setCellValue('AE'.$row, $value->guarantor_secondary_ext);
				$objPHPExcel->getActiveSheet()->setCellValue('AF'.$row, $value->primary_fname.' '.$value->primary_mname.' '.$value->primary_lname);
				$objPHPExcel->getActiveSheet()->setCellValue('AG'.$row, $value->primary_phone);
				$objPHPExcel->getActiveSheet()->setCellValue('AH'.$row, $value->primary_phone_type);
				$objPHPExcel->getActiveSheet()->setCellValue('AI'.$row, $value->primary_address_1.' '.$value->primary_city.' '.$value->primary_country.' '.$value->primary_state.' '.$value->primary_zip);
				$objPHPExcel->getActiveSheet()->setCellValue('AJ'.$row, $value->patient_mother_name);
				$objPHPExcel->getActiveSheet()->setCellValue('AK'.$row, $value->immunization_registery_status);
				$objPHPExcel->getActiveSheet()->setCellValue('AL'.$row, $value->immunization_effective_date);
				$objPHPExcel->getActiveSheet()->setCellValue('AM'.$row, $value->data_privacy);
				$objPHPExcel->getActiveSheet()->setCellValue('AN'.$row, $value->create_date);
				$objPHPExcel->getActiveSheet()->setCellValue('AO'.$row, $value->status);
				$objPHPExcel->getActiveSheet()->setCellValue('AP'.$row, $email_reminder);
				$objPHPExcel->getActiveSheet()->setCellValue('AQ'.$row, $voice_reminders);
				$objPHPExcel->getActiveSheet()->setCellValue('AR'.$row, $smsreminders);
				$objPHPExcel->getActiveSheet()->setCellValue('AS'.$row, $value->my_practice);
				$row++;
	}


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="patient_report.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');


		}
		public function changestatus()
		{
			$id = $this->input->get_post('id');
			$value = $this->input->get_post('value');

		 $arr['status'] = $value;
		 $this->db->where('patient_id',$id);
		 $this->db->update('patient',$arr);
		 echo 'success';
		 exit;
		}
    public function email_check($email, $id)
    {
        $emailExists = $this->db->select('email')
            ->where('email',$email)
            ->where_not_in('id',$id)
            ->get('patient')
            ->num_rows();
						$emailExistsdo = $this->db->select('email')
								->where('email',$email)
								->get('user')
								->num_rows();
        if ($emailExists > 0 or $emailExistsdo > 0) {
            $this->form_validation->set_message('email_check', 'The {field} field must contain a unique value.');
            return false;
        } else {
            return true;
        }
    }
		public function create()
		{
			$session_id = $this->session->userdata('user_id');
			$created_by_id = $this->session->userdata('created_by');
	        $isadmin = $this->session->userdata('isadmin');
	        if($isadmin == 1){
	        	$session_id = $created_by_id;
	        }
			$data['title'] = display('add_patient');
					$id = $this->input->post('id');
			#-------------------------------#
			$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
									$pass = array(); //remember to declare $pass as an array
									$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
									for ($i = 0; $i < 8; $i++) {
											$n = rand(0, $alphaLength);
											$pass[] = $alphabet[$n];
									}
									$newpass = implode($pass);
			//$datas['password'] = md5($newpass);
			$pass = $newpass;
			$this->form_validation->set_rules('fname', display('first_name'),'required|max_length[50]');
			$this->form_validation->set_rules('lname', "Last Name",'required|max_length[50]');
			$this->form_validation->set_rules('sex', 'Sex','required');

			if ($this->input->post('id') == null) {
				$this->form_validation->set_rules('email', "Email I'd",'required|max_length[100]|is_unique[patient.email]|valid_email|is_unique[user.email]');
				$this->form_validation->set_message('is_unique', " %s already exist!"); //Email I'd already exist!

			} else {
				$this->form_validation->set_rules('email',display('email'), "required|max_length[50]|valid_email|callback_email_check[$id]");
			}

			//$this->form_validation->set_rules('password', display('password'),'required|max_length[32]');
			//$this->form_validation->set_rules('phone', display('phone'),'max_length[20]');
			if($this->input->get_post('mobilecheck')!='1'){
				$this->form_validation->set_rules('mobile', display('mobile'),'required|max_length[20]');
			}
//echo $this->input->get_post('mobilecheck');
			//$this->form_validation->set_rules('blood_group', display('blood_group'),'max_length[10]');
			//$this->form_validation->set_rules('sex', display('sex'),'required|max_length[10]');
			$this->form_validation->set_rules('date_of_birth', display('date_of_birth'),'required|max_length[10]');
			//$this->form_validation->set_rules('address', display('address'),'required|max_length[255]');
			//$this->form_validation->set_rules('status', display('status'),'required');
			#-------------------------------#
			//picture upload
			$picture = $this->fileupload->do_upload(
				'assets/images/patient/',
				'picture'
			);
			// if picture is uploaded then resize the picture
			if ($picture !== false && $picture != null) {
				$this->fileupload->do_resize(
					$picture,
					200,
					150
				);
			}
			//if picture is not uploaded
		//	if ($picture === false) {
				//$this->session->set_flashdata('exception', display('invalid_picture'));
			//}

			//insurance
		//	$insurance = $this->fileupload->do_upload(
			//	'assets/images/patient/',
			//	'insurance_file'
			//);
			// if picture is uploaded then resize the picture
		//	if ($insurance !== false && $insurance != null) {
			//	$this->fileupload->do_resize(
			//		$insurance,
			//		200,
			//		150
			//	);
		//	}
			//if picture is not uploaded
		//	if ($insurance === false) {
		//		$this->session->set_flashdata('exception', 'Invalid insurance file');
	//		}

			#-------------------------------#
     $ethnicity_race ='';
  //   if($this->input->get_post('ethnicity_race')=='choose'){
			// $arr_s = $this->input->get_post('ethnicity_race_option');
			// $ethnicity_race = implode(",",$arr_s);
		 //}else{
			 $ethnicity_race = $this->input->get_post('ethnicity_race');
		// }

			$pid = "P".$this->randStrGen(2,7);
			if ($this->input->post('id') == null) { //create a patient
				$data['patient'] = (object)$postData = [
					'id'   		   => $this->input->post('id'),
					'mobilecheck'   		   => ($this->input->post('mobilecheck')!='')?$this->input->post('mobilecheck'):'0',
					'patient_id'   => $pid,
					'prefix'    => $this->input->post('prefix'),
					'fname' 	   => $this->input->post('fname'),
					'mname' 	   => $this->input->post('mname'),
					'lname' 	   => $this->input->post('lname'),
					'password' 	   => md5($newpass),
					//'phone'   	   => $this->input->post('phone'),
					'suffix'       => $this->input->post('suffix'),
					//'blood_group'  => $this->input->post('blood_group'),
					'secondlastname' 		   => $this->input->post('secondlastname'),
					'previousfname' 		   => $this->input->post('previousfname'),
					'date_of_birth' => date('Y-m-d', strtotime(($this->input->post('date_of_birth') != null)? $this->input->post('date_of_birth'): date('Y-m-d'))),
					'previousmname' 	   => $this->input->post('previousmname'),
					'previouslname' 	   => $this->input->post('previouslname'),
					'sex' 	   => $this->input->post('sex'),
'dod' 	   => date('Y-m-d', strtotime(($this->input->post('dod') != null)? $this->input->post('dod'): "0000-00-00")),
'ssn' 	   => $this->input->post('ssn'),
//'gestitation' 	   => $this->input->post('gestitation'),
'mobile_prefix' 	   => $this->input->post('mobile_prefix'),
'mobile' 	   => ($this->input->post('mobile')!='')?$this->input->post('mobile'):'',
'email' 	   => $this->input->post('email'),
'phone' 	   => $this->input->post('phone'),
'workphone' 	   => $this->input->post('workphone'),
'ext' 	   => $this->input->post('ext'),
'address1' 	   => $this->input->post('address1'),
'country' 	   => $this->input->post('country'),
'city' 	   => $this->input->post('city'),
'state' 	   => $this->input->post('state'),
'zip' 	   => $this->input->post('zip'),
'methodofcommunication' 	   => $this->input->post('methodofcommunication'),
'emailreminders' 	   => $this->input->post('emailreminders'),
'voicereminders' 	   => $this->input->post('voicereminders'),
'smsreminders' 	   => $this->input->post('smsreminders'),
'relationship_to_guarantor' 	   => $this->input->post('relationship_to_guarantor'),
'guarantor_fname' 	   => $this->input->post('guarantor_fname'),
'guarantor_mname' 	   => $this->input->post('guarantor_mname'),
'guarantor_lname' 	   => $this->input->post('guarantor_lname'),
'guarantor_address1' 	   => $this->input->post('guarantor_address1'),
'guarantor_country' 	   => $this->input->post('guarantor_country'),
'guarantor_city' 	   => $this->input->post('guarantor_city '),
'guarantor_state' 	   => $this->input->post('guarantor_state'),
'guarantor_zip' 	   => $this->input->post('guarantor_zip'),
'guarantor_dob' 	   => date('Y-m-d', strtotime(($this->input->post('guarantor_dob') != null)? $this->input->post('guarantor_dob'): date('Y-m-d'))),
'guarantor_sex' 	   => $this->input->post('guarantor_sex'),
'guarantor_ssn' 	   => $this->input->post('guarantor_ssn'),
'guarantor_primary_phone' 	   => $this->input->post('guarantor_primary_phone'),
'guarantor_primary_ext' 	   => $this->input->post('guarantor_primary_ext'),
'guarantor_secondary_phone' 	   => $this->input->post('guarantor_secondary_phone'),
'guarantor_secondary_ext' 	   => $this->input->post('guarantor_secondary_ext'),
'primary_fname' 	   => $this->input->post('primary_fname'),
'primary_mname' 	   => $this->input->post('primary_mname'),
'primary_lname' 	   => $this->input->post('primary_lname'),
'relation_to_patient' 	   => $this->input->post('relation_to_patient'),
'primary_phone' 	   => $this->input->post('primary_phone'),
'primary_phone_type' 	   => $this->input->post('primary_phone_type'),
'primary_address_1' 	   => $this->input->post('primary_address_1'),
'primary_city' 	   => $this->input->post('primary_city'),
'primary_state' 	   => $this->input->post('primary_state'),
'primary_country' 	   => $this->input->post('primary_country'),
'primary_zip' 	   => $this->input->post('primary_zip'),
'patient_mother_name' 	   => $this->input->post('patient_mother_name'),

'immunization_registery_status' 	   => $this->input->post('immunization_registery_status'),

'immunization_effective_date' 	   => date('Y-m-d', strtotime(($this->input->post('immunization_effective_date') != null)? $this->input->post('immunization_effective_date'): date('Y-m-d'))),
'data_privacy' 	   => $this->input->post('data_privacy'),
'reminder_call' 	   => $this->input->post('reminder_call'),








					'create_date'  => date('Y-m-d'),
					'created_by'   => $this->session->userdata('user_id'),
					'status'       => '1',
					'hospital_id'   => $session_id,
				//	'preferred_language'       => $this->input->post('preferred_language'),
					'ethnicity_race'       => $ethnicity_race,
					'picture'       => $picture,
					'my_practice' => ($this->input->post('my_practice')!='')?$this->input->post('my_practice'):'No'

				//	'insurance'       => $this->input->post('insurance'),
				//	'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
					//
				];
			} else { // update patient
				$data['patient'] = (object)$postData = [
					'id'   		   => $this->input->post('id'),
					//'patient_id'   => "P".$this->randStrGen(2,7),
					'mobilecheck'   		   => ($this->input->post('mobilecheck')!='')?$this->input->post('mobilecheck'):'0',
					'prefix'    => $this->input->post('prefix'),
					'fname' 	   => $this->input->post('fname'),
					'mname' 	   => $this->input->post('mname'),
					'lname' 	   => $this->input->post('lname'),
					//'password' 	   => md5($newpass),
					//'phone'   	   => $this->input->post('phone'),
					'suffix'       => $this->input->post('suffix'),
					//'blood_group'  => $this->input->post('blood_group'),
					'secondlastname' 		   => $this->input->post('secondlastname'),
					'previousfname' 		   => $this->input->post('previousfname'),
					'date_of_birth' => date('Y-m-d', strtotime(($this->input->post('date_of_birth') != null)? $this->input->post('date_of_birth'): date('Y-m-d'))),
					'previousmname' 	   => $this->input->post('previousmname'),
					'previouslname' 	   => $this->input->post('previouslname'),
					'sex' 	   => $this->input->post('sex'),
'dod' 	   => date('Y-m-d', strtotime(($this->input->post('dod') != null)? $this->input->post('dod'): "0000-00-00")),
'ssn' 	   => $this->input->post('ssn'),
//'gestitation' 	   => $this->input->post('gestitation'),
'mobile_prefix' 	   => $this->input->post('mobile_prefix'),
'mobile' 	   => ($this->input->post('mobile')!='')?$this->input->post('mobile'):'',
'email' 	   => $this->input->post('email'),
'phone' 	   => $this->input->post('phone'),
'workphone' 	   => $this->input->post('workphone'),
'ext' 	   => $this->input->post('ext'),
'address1' 	   => $this->input->post('address1'),
'country' 	   => $this->input->post('country'),
'city' 	   => $this->input->post('city'),
'state' 	   => $this->input->post('state'),
'zip' 	   => $this->input->post('zip'),
'methodofcommunication' 	   => $this->input->post('methodofcommunication'),
'emailreminders' 	   => $this->input->post('emailreminders'),
'voicereminders' 	   => $this->input->post('voicereminders'),
'smsreminders' 	   => $this->input->post('smsreminders'),
'relationship_to_guarantor' 	   => $this->input->post('relationship_to_guarantor'),
'guarantor_fname' 	   => $this->input->post('guarantor_fname'),
'guarantor_mname' 	   => $this->input->post('guarantor_mname'),
'guarantor_lname' 	   => $this->input->post('guarantor_lname'),
'guarantor_address1' 	   => $this->input->post('guarantor_address1'),
'guarantor_country' 	   => $this->input->post('guarantor_country'),
'guarantor_city' 	   => $this->input->post('guarantor_city '),
'guarantor_state' 	   => $this->input->post('guarantor_state'),
'guarantor_zip' 	   => $this->input->post('guarantor_zip'),
'guarantor_dob' 	   => date('Y-m-d', strtotime(($this->input->post('guarantor_dob') != null)? $this->input->post('guarantor_dob'): date('Y-m-d'))),
'guarantor_sex' 	   => $this->input->post('guarantor_sex'),
'guarantor_ssn' 	   => $this->input->post('guarantor_ssn'),
'guarantor_primary_phone' 	   => $this->input->post('guarantor_primary_phone'),
'guarantor_primary_ext' 	   => $this->input->post('guarantor_primary_ext'),
'guarantor_secondary_phone' 	   => $this->input->post('guarantor_secondary_phone'),
'guarantor_secondary_ext' 	   => $this->input->post('guarantor_secondary_ext'),
'primary_fname' 	   => $this->input->post('primary_fname'),
'primary_mname' 	   => $this->input->post('primary_mname'),
'primary_lname' 	   => $this->input->post('primary_lname'),
//'relation_to_patient' 	   => $this->input->post('relation_to_patient'),
'primary_phone' 	   => $this->input->post('primary_phone'),
'primary_phone_type' 	   => $this->input->post('primary_phone_type'),
'primary_address_1' 	   => $this->input->post('primary_address_1'),
'primary_country' 	   => $this->input->post('primary_country'),
'primary_city' 	   => $this->input->post('primary_city'),
'primary_state' 	   => $this->input->post('primary_state'),
'primary_zip' 	   => $this->input->post('primary_zip'),

'patient_mother_name' 	   => $this->input->post('patient_mother_name'),

'immunization_registery_status' 	   => $this->input->post('immunization_registery_status'),

'immunization_effective_date' 	   => date('Y-m-d', strtotime(($this->input->post('immunization_effective_date') != null)? $this->input->post('immunization_effective_date'): date('Y-m-d'))),
'data_privacy' 	   => $this->input->post('data_privacy'),
'reminder_call' 	   => $this->input->post('reminder_call'),
					'create_date'  => date('Y-m-d'),
					'created_by'   => $this->session->userdata('user_id'),
					'status'       => '1',
					'hospital_id'   => $session_id,
					//'preferred_language'       => $this->input->post('preferred_language'),
					'ethnicity_race'       => $ethnicity_race,
					'picture'       => (!empty($picture)?$picture:$this->input->post('old_picture')),
					'my_practice' => ($this->input->post('my_practice')!='')?$this->input->post('my_practice'):'No'
				//	'insurance'       => $this->input->post('insurance'),
					//'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
					//
				];
			}
			#-------------------------------#
			if ($this->form_validation->run() === true) {

				#if empty $id then insert data
				if (empty($postData['id'])) {
					if ($this->patient_model->create($postData)) {
						if($this->session->userdata('isadmin')==0 ){
							$hospital_name = $this->db->select("*")->from("user")->where("user_id",$this->session->userdata('user_id'))->get()->row();
						}else if($this->session->userdata('isadmin')==1){
							$hospital_name = $this->db->select("*")->from("user")->where("user_id",$this->session->userdata('hospital_id'))->get()->row();
						}
						$patient_id = $this->db->insert_id();
						$insurance_u_id = $this->input->get_post('insurance_u_id');
						$this->db->where("insurance_u_id",$insurance_u_id);
						$arr['patient_id'] = $pid;
						$this->db->update("insurance",$arr);
						#set success message
						$to =$this->input->post('email',true);
						$subject="Welcome to ".$hospital_name->hospitalname;
					$htmlMessage='<html>
							<head>
								<meta name="viewport" content="width=device-width" />
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
								<title>New register in  '. $hospital_name->hospitalname .'</title>
								<style type="text/css">
									body{
										 background-color: #e8e4e4;
										 font-family: Arial, Helvetica, sans-serif;
										font-size: 14px;
										line-height: 1.12857143;
										color: #847f7f;
									}
									p{
										margin-left: 15px;
									}
								</style>
							</head>
						<body>
						<table class="table" style="width: 100%;">
							<tr>
								<td width="20%"></td>
								<td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;border-top: 1px solid #d7d0d0;background-color: white;text-align: center;"><img width="250px" src='.base_url()."assets/images/logo.png".' style="margin:10px 0px;"  /></td>
								<td width="20%"></td>
							</tr>
						 <tr>
								<td width="20%"></td>
								<td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;background-color: white;">
									<p style="color: #150aec;font-weight: 700;font-size: 16px;">New register in '. $hospital_name->hospitalname .'</p>
									<p><b>Hello '.$this->input->post('email',true).',</b></p>
									<p>Your account has been registered on '. $hospital_name->hospitalname .'</p>
									<p>You can sign in to your account by using your this  email  '.$this->input->post('email',true).' and password:  '. $pass.'</p>
								 </td>
								<td width="20%"></td>
							</tr>
								<tr>
									<td width="20%"></td>
									<td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;background-color: white;">
										<table class="table" style="border-collapse: collapse;margin-left: 15px;margin-right: 15px;margin-top:15px;width: 90%;">
											<tbody>
												<tr>
													<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Fullname</td>
													<td style="border:1px solid #ccc5c5;padding: 8px;">'.$this->input->post('firstname',true).' '.$this->input->post('lastname',true).'</td>
												</tr>
												<tr>
													<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Email</td>
													<td style="border:1px solid #ccc5c5;padding: 8px;">'.$this->input->post('email',true).'</td>
												</tr>
												<tr>
													<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Phone No.</td>
													<td style="border:1px solid #ccc5c5;padding: 8px;">'.$this->input->post('mobile',true).'</td>
												</tr>
												<tr>
													<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Birth Date</td>
													<td style="border:1px solid #ccc5c5;padding: 8px;">'.date('Y-m-d', strtotime($this->input->post('date_of_birth',true))).'</td>
												</tr>
												<tr>
													<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Role</td>
													<td style="border:1px solid #ccc5c5;padding: 8px;">Patient</td>
											</tr>
											<tr>
												<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Join Date </td>
												<td style="border:1px solid #ccc5c5;padding: 8px;">'.date('Y-m-d').'</td>
											</tr>
										</tbody>
									</table>
									<p style="color: #6f5f5f;">* Thanks! For new register in '. $hospital_name->hospitalname .' application.</p>
								</td>
							<td width="20%"></td>
						</tr>
						<tr>
						 <td width="20%"></td>
							<td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;background-color: black;">
								<p style="text-align: center;color: white;">This message was sent to <span style="color: orange;">'.$to.'.</span> If this is not you please delete this email and send an email to support to report this error. This email has been generated with user knowledge by our system. Please login to change your preference if you no longer wish to receive this email. or contact support. We do not transmit nor do we ask for sensitive information over email. If any such information is transmitted or requested over email please report it to support. If you have any questions, contact us at <span style="color: orange;">'.$hospital_name->email.'</span></p>
							<td width="20%"></td>
						</tr>
						</table>
						</body>
						</html>';
						//$htmlMessage="Your Username is your register email address"."<br>";
						// $htmlMessage.="Your Password is:  ".$pass;
						$this->sendEmailAttachment($to,$subject,$htmlMessage);
						$this->session->set_flashdata('message', display('save_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception', display('please_try_again'));
					}
		redirect('patient');
					//redirect('patient/profile/' . $patient_id);
				} else {
					if ($this->patient_model->update($postData)) {
						#set success message
						$this->session->set_flashdata('message', display('update_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception', display('please_try_again'));
						redirect('patient/edit/'.$postData['id']);
					}

					redirect('patient');
				}

			} else {
				$data['content'] = $this->load->view('patient_form',$data,true);
				$this->load->view('layout/main_wrapper',$data);
			}
		}
	public function create_old()
	{
		$data['title'] = display('add_patient');
        $id = $this->input->post('id');
		#-------------------------------#
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
                $pass = array(); //remember to declare $pass as an array
                $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                for ($i = 0; $i < 8; $i++) {
                    $n = rand(0, $alphaLength);
                    $pass[] = $alphabet[$n];
                }
                $newpass = implode($pass);
    //$datas['password'] = md5($newpass);
		$pass = $newpass;
		$this->form_validation->set_rules('firstname', display('first_name'),'required|max_length[50]');
		$this->form_validation->set_rules('lastname', display('last_name'),'required|max_length[50]');
		if ($this->input->post('id') == null) {
			$this->form_validation->set_rules('email', display('email'),'required|max_length[100]|is_unique[patient.email]|valid_email|is_unique[user.email]');
		} else {
			$this->form_validation->set_rules('email',display('email'), "required|max_length[50]|valid_email|callback_email_check[$id]");
		}

		//$this->form_validation->set_rules('password', display('password'),'required|max_length[32]');
		$this->form_validation->set_rules('phone', display('phone'),'max_length[20]');
		$this->form_validation->set_rules('mobile', display('mobile'),'required|max_length[20]');
		$this->form_validation->set_rules('blood_group', display('blood_group'),'max_length[10]');
		$this->form_validation->set_rules('sex', display('sex'),'required|max_length[10]');
		$this->form_validation->set_rules('date_of_birth', display('date_of_birth'),'required|max_length[10]');
		$this->form_validation->set_rules('address', display('address'),'required|max_length[255]');
		//$this->form_validation->set_rules('status', display('status'),'required');
		#-------------------------------#
		//picture upload
		$picture = $this->fileupload->do_upload(
			'assets/images/patient/',
			'picture'
		);
		// if picture is uploaded then resize the picture
		if ($picture !== false && $picture != null) {
			$this->fileupload->do_resize(
				$picture,
				200,
				150
			);
		}
		//if picture is not uploaded
		if ($picture === false) {
			$this->session->set_flashdata('exception', display('invalid_picture'));
		}

		//insurance
		$insurance = $this->fileupload->do_upload(
			'assets/images/patient/',
			'insurance_file'
		);
		// if picture is uploaded then resize the picture
		if ($insurance !== false && $insurance != null) {
			$this->fileupload->do_resize(
				$insurance,
				200,
				150
			);
		}
		//if picture is not uploaded
		if ($insurance === false) {
			$this->session->set_flashdata('exception', 'Invalid insurance file');
		}

		#-------------------------------#
		if ($this->input->post('id') == null) { //create a patient
			$data['patient'] = (object)$postData = [
				'id'   		   => $this->input->post('id'),
				'patient_id'   => "P".$this->randStrGen(2,7),
				'firstname'    => $this->input->post('firstname'),
				'lastname' 	   => $this->input->post('lastname'),
				'email' 	   => $this->input->post('email'),
				'password' 	   => md5($newpass),
				//'phone'   	   => $this->input->post('phone'),
				'mobile'       => $this->input->post('mobile'),
				//'blood_group'  => $this->input->post('blood_group'),
				'sex' 		   => $this->input->post('sex'),
				'date_of_birth' => date('Y-m-d', strtotime(($this->input->post('date_of_birth') != null)? $this->input->post('date_of_birth'): date('Y-m-d'))),
				'address' 	   => $this->input->post('address'),
				'picture'      => (!empty($picture)?$picture:$this->input->post('old_picture')),
				'affliate'     => null,
				'create_date'  => date('Y-m-d'),
				'created_by'   => $this->session->userdata('user_id'),
				'status'       => '1',
				'insurance'       => $this->input->post('insurance'),
				'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
				//
			];
		} else { // update patient
			$data['patient'] = (object)$postData = [
				'id'   		   => $this->input->post('id'),
				'firstname'    => $this->input->post('firstname'),
				'lastname' 	   => $this->input->post('lastname'),
				'email' 	   => $this->input->post('email'),
				'password' 	   => md5($this->input->post('password')),
				'phone'   	   => $this->input->post('phone'),
				'mobile'       => $this->input->post('mobile'),
				'blood_group'  => $this->input->post('blood_group'),
				'sex' 		   => $this->input->post('sex'),
				'date_of_birth' => date('Y-m-d', strtotime($this->input->post('date_of_birth'))),
				'address' 	   => $this->input->post('address'),
				'picture'      => (!empty($picture)?$picture:$this->input->post('old_picture')),
				'affliate'     => null,
				'created_by'   => $this->session->userdata('user_id'),
				'status'       => $this->input->post('status'),
				'insurance'       => $this->input->post('insurance'),
				'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
			];
		}
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			#if empty $id then insert data
			if (empty($postData['id'])) {
				if ($this->patient_model->create($postData)) {
					$patient_id = $this->db->insert_id();
					#set success message
					$to =$this->input->post('email',true);
					$subject="Welcome to ".$hospital_name->hospitalname;
				$htmlMessage='<html>
					  <head>
					    <meta name="viewport" content="width=device-width" />
					    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					    <title>New register in '. $hospital_name->hospitalname .'</title>
					    <style type="text/css">
					      body{
					         background-color: #e8e4e4;
					         font-family: Arial, Helvetica, sans-serif;
					        font-size: 14px;
					        line-height: 1.12857143;
					        color: #847f7f;
					      }
					      p{
					        margin-left: 15px;
					      }
					    </style>
					  </head>
					<body>
					<table class="table" style="width: 100%;">
					  <tr>
					    <td width="20%"></td>
					    <td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;border-top: 1px solid #d7d0d0;background-color: white;text-align: center;"><img width="250px" src='.base_url()."assets/images/logo.png".' style="margin:10px 0px;"  /></td>
					    <td width="20%"></td>
					  </tr>
					 <tr>
					    <td width="20%"></td>
					    <td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;background-color: white;">
					      <p style="color: #150aec;font-weight: 700;font-size: 16px;">New register in '. $hospital_name->hospitalname .'</p>
					      <p><b>Hello '.$this->input->post('email',true).',</b></p>
					      <p>Your account has been registered on '. $hospital_name->hospitalname .'</p>
					      <p>You can sign in to your account by using your this  email  '.$this->input->post('email',true).' and password:  '. $pass.',</p>
					     </td>
					    <td width="20%"></td>
					  </tr>
					    <tr>
					      <td width="20%"></td>
					      <td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;background-color: white;">
					        <table class="table" style="border-collapse: collapse;margin-left: 15px;margin-right: 15px;margin-top:15px;width: 90%;">
					          <tbody>
					            <tr>
					              <td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Fullname</td>
					              <td style="border:1px solid #ccc5c5;padding: 8px;">'.$this->input->post('firstname',true).' '.$this->input->post('lastname',true).'</td>
					            </tr>
					            <tr>
					              <td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Email</td>
					              <td style="border:1px solid #ccc5c5;padding: 8px;">'.$this->input->post('email',true).'</td>
					            </tr>
					            <tr>
					              <td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Phone No.</td>
					              <td style="border:1px solid #ccc5c5;padding: 8px;">'.$this->input->post('mobile',true).'</td>
					            </tr>
					            <tr>
					              <td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Birth Date</td>
					              <td style="border:1px solid #ccc5c5;padding: 8px;">'.date('Y-m-d', strtotime($this->input->post('date_of_birth',true))).'</td>
					            </tr>
					            <tr>
					              <td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Role</td>
					              <td style="border:1px solid #ccc5c5;padding: 8px;">Patient</td>
										</tr>
										<tr>
											<td style="border:1px solid #ccc5c5;padding: 8px;width: 25%;font-weight: 600;">Join Date </td>
											<td style="border:1px solid #ccc5c5;padding: 8px;">'.date('Y-m-d').'</td>
										</tr>
									</tbody>
								</table>
								<p style="color: #6f5f5f;">* Thanks! For new register in '. $hospital_name->hospitalname .' application.</p>
							</td>
						<td width="20%"></td>
					</tr>
					<tr>
					 <td width="20%"></td>
						<td width="60%" style="border-right: 1px solid #d7d0d0;border-left: 1px solid #d7d0d0;background-color: black;">
							<p style="text-align: center;color: white;">This message was sent to <span style="color: orange;">'.$to.'.</span> If this is not you please delete this email and send an email to support to report this error. This email has been generated with user knowledge by our system. Please login to change your preference if you no longer wish to receive this email. or contact support. We do not transmit nor do we ask for sensitive information over email. If any such information is transmitted or requested over email please report it to support. If you have any questions, contact us at <span style="color: orange;">'.$hospital_name->email.'</span></p>
						<td width="20%"></td>
					</tr>
					</table>
					</body>
					</html>';
					//$htmlMessage="Your Username is your register email address"."<br>";
					// $htmlMessage.="Your Password is:  ".$pass;
					$this->sendEmailAttachment($to,$subject,$htmlMessage);
					$this->session->set_flashdata('message', display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				}
redirect('patient');
				//redirect('patient/profile/' . $patient_id);
			} else {
				if ($this->patient_model->update($postData)) {
					#set success message
					$this->session->set_flashdata('message', display('update_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
					redirect('patient/edit/'.$postData['id']);
				}

				redirect('patient');
			}

		} else {
			$data['content'] = $this->load->view('patient_form',$data,true);
			$this->load->view('layout/main_wrapper',$data);
		}
	}
	function patient_report()
	{
		$this->load->library('dompdf_gen');
		$customPaper = array(0,0,1024,1000);
$this->dompdf->set_paper($customPaper);
	//	$this->dompdf->set_paper(DEFAULT_PDF_PAPER_SIZE, 'landscape');
		$pdfname = "Patient" . date('YmdHis') . '.pdf';
$html = '<style>
								table {
									display: table; border-collapse: collapse;
								}
								.pricedetail tr td
								{
										font-family:Verdana;


								}
								.pricedetail tr th
								{
										font-family:Verdana;

								}
						</style>
            <h3 style="text-align:center;">Patient Report</h3>
						<table border="1" width="100%" class="pricedetail" style="margin-top: 1px;">
								 <tr>
										<th>First Name</th>
										<th>Last Name</th>
										<th>DOB</th>
										<th>Gender</th>
										<th>Contact Info</th>
										<th>Email</th>
										<th>Join Date</th>
										<th>Email Reminder</th>
										<th>SMS Reminder</th>
										<th>Voice Reminder</th>
										<th>Status</th>
								</tr>';
							//$doctors =	$this->doctor_model->read();
						$patients =	$this->patient_model->read();
foreach ($patients as $patient) {
//$role = $this->db->select("*")->from("role")->where("r_id",$doctor->role_id)->get()->row();
								$html .='<tr>
										<td width="10%" style="padding: 5px;">'.$patient->fname.'</td><td style="padding: 5px;">'.$patient->lname.'</td>

										<td style="padding: 5px;">'.date('d/m/Y',strtotime($patient->date_of_birth)).'</td><td style="padding: 5px;">'.$patient->sex.'</td>
										<td align="left" style="width:16%;padding: 5px;">
												<p>'.$patient->address1.' '.$patient->country.' '.$patient->city.' '.$patient->state.' '.$patient->zip.'</p>
												<p>M '.$patient->mobile.'</p>
												<p>H '.$patient->phone.'</p>

										</td>
										<td width="12%" style="padding: 5px;">'.$patient->email.'</td>';

										$html .='<td style="padding: 5px;">'.date('d/m/Y',strtotime($patient->create_date)).'</td>';
										if($patient->emailreminders=='1'){
											$html .='<td style="padding: 5px;">Yes</td>';
										}else{
											$html .='<td style="padding: 5px;">No</td>';
										}
										if($patient->voicereminders=='1'){
											$html .='<td style="padding: 5px;">Yes</td>';
										}else{
											$html .='<td style="padding: 5px;">No</td>';
										}
										if($patient->smsreminders=='1'){
											$html .='<td style="padding: 5px;">Yes</td>';
										}else{
											$html .='<td style="padding: 5px;">No</td>';
										}
									//	$html .='<td style="padding: 5px;">'.$patient->emailreminders.'</td>';
								//		$html .='<td style="padding: 5px;">'.$patient->voicereminders.'</td>';
									//	$html .='<td style="padding: 5px;">'.$patient->smsreminders.'</td>';
									if($patient->status==1){
										$status = 'Active';
									}else{
										$status = 'Inactive';
									}
										$html .='<td style="padding: 5px;">'.$status.'</td>
								</tr>';
						}

						$html .='</table>';
					// echo $html;
					// 	exit;
$this->dompdf->load_html($html);
		$this->dompdf->render();
		$output = $this->dompdf->output();
		//print_r($output);
		file_put_contents('pdf/' . $pdfname . '', $output);
		redirect('pdf/' . $pdfname . '', $output);
	}
	public function createvital()
	{
		$data['title'] = display('add_vital_sign');
				$id = $this->input->post('vital_id');
		#-------------------------------#

		$this->form_validation->set_rules('patient_id', display('patient_id'),'required|max_length[50]');
		$this->form_validation->set_rules('pulse', display('pulse'),'required|max_length[50]');


		$this->form_validation->set_rules('temperature', display('temperature'),'required|max_length[32]');
		$this->form_validation->set_rules('blood_pressure_sytolic', display('sytolic'),'required');
		$this->form_validation->set_rules('blood_pressure_diastolic', display('diastolic'),'required');
		$this->form_validation->set_rules('blood_pressure_position', 'Sitting or Standing','required');
		$this->form_validation->set_rules('weight', display('Weight(kg)'),'required');
		$this->form_validation->set_rules('rest_rate', display('Rest.Rate(Breaths/min)'),'required');

		#-------------------------------#
		//picture upload

		#-------------------------------#
		if ($this->input->post('id') == null) { //create a patient
			$data['patient'] = (object)$postData = [
				'vital_id'   		   => $this->input->post('vital_id'),
				'patient_id'   => $this->input->post('patient_id'),
				'pulse'    => $this->input->post('pulse'),
				'temperature' 	   => $this->input->post('temperature'),
				'temperature_a' 	   => $this->input->post('temperature_a'),
				'blood_pressure_sytolic' 	   => $this->input->post('blood_pressure_sytolic'),
				'blood_pressure_diastolic'   	   => $this->input->post('blood_pressure_diastolic'),
				'blood_pressure_position'       => $this->input->post('blood_pressure_position'),

				'weight' 		   => $this->input->post('weight'),
				'rest_rate' => $this->input->post('rest_rate'),
				'created_date' => date('Y-m-d'),
				'created_time' => date('H:i:s'),
			];
		} else { // update patient
			$data['patient'] = (object)$postData = [
				'vital_id'   		   => $this->input->post('vital_id'),
				'patient_id'   => $this->input->post('patient_id'),
				'pulse'    => $this->input->post('pulse'),
				'temperature' 	   => $this->input->post('temperature'),
				'temperature_a' 	   => $this->input->post('temperature_a'),
				'blood_pressure_sytolic' 	   => $this->input->post('blood_pressure_sytolic'),
				'blood_pressure_diastolic'   	   => $this->input->post('blood_pressure_diastolic'),
				'blood_pressure_position'       => $this->input->post('blood_pressure_position'),

				'weight' 		   => $this->input->post('weight'),
				'rest_rate' => $this->input->post('rest_rate'),
				'created_date' => date('Y-m-d'),
				'created_time' => date('H:i:s'),
			];
		}
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			#if empty $id then insert data
			if (empty($postData['vital_id'])) {
				if ($this->patient_model->createvital($postData)) {
					$vital_id = $this->db->insert_id();
					#set success message

					$this->session->set_flashdata('message', display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				}

			//	redirect('patient/profile/' . $this->input->post('patient_id'));
				//	redirect('patient');
					$result =	$this->db->select('*')
										 ->from('patient')
										 ->where('patient_id',$this->input->post('patient_id'))->get()->row();

						redirect('patient/profile/' . $result->id);
			} else {
				if ($this->patient_model->updatevital($postData)) {
					#set success message
					$this->session->set_flashdata('message', display('update_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				}
				redirect('patient/editvital/'.$postData['vital_id']);
		//		redirect('patient');
			}

		} else {
			$data['content'] = $this->load->view('vital_form',$data,true);
			$this->load->view('layout/main_wrapper',$data);
		}
	}

	public function profile($patient_id = null)
	{
		$data['title'] =  display('patient_information');
		#-------------------------------#
		$data['profile'] = $this->patient_model->read_by_id($patient_id);
		$data['documents'] = $this->document_model->read_by_patient($patient_id);
		$this->db->select('*')
		         ->from('pa_vital_sign')
		         ->join('patient', 'patient.patient_id = pa_vital_sign.patient_id')
						 ->where('patient.id',$patient_id)->order_by('pa_vital_sign.created_time','desc')->order_by('pa_vital_sign.created_date','desc');
		$result = $this->db->get();
	//	echo $this->db->last_query();
		$data['vital'] = $result->result();
		$data['content'] = $this->load->view('patient_profile',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}
	public function add_insurance($patient_id = null)
	{
		$data['title'] =  display('patient_information');
		#-------------------------------#
		//$data['profile'] = $this->patient_model->read_by_id($patient_id);
		//$data['documents'] = $this->document_model->read_by_patient($patient_id);
	//	$this->db->select('*')
				//		 ->from('pa_vital_sign')
			///			 ->join('patient', 'patient.patient_id = pa_vital_sign.patient_id')
					//	 ->where('patient.id',$patient_id)->order_by('pa_vital_sign.created_time','desc')->order_by('pa_vital_sign.created_date','desc');
	//	$result = $this->db->get();
	//	echo $this->db->last_query();
	//	$data['vital'] = $result->result();
		$data['content'] = $this->load->view('insurance_form',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}

	public function edit($patient_id = null)
	{
		$data['title'] = display('patient_edit');
		#-------------------------------#
		$data['patient'] = $this->patient_model->read_by_id($patient_id);
		$data['content'] = $this->load->view('patient_form',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}


	public function delete($patient_id = null)
	{
		if ($this->patient_model->delete($patient_id)) {
			#set success message
			$this->session->set_flashdata('message','Patient profile delete successfully');
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('patient');
	}



    /*
    |----------------------------------------------
    |        id genaretor
    |----------------------------------------------
    */
    public function randStrGen($mode = null, $len = null){
        $result = "";
        if($mode == 1):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 2):
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif($mode == 3):
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 4):
            $chars = "0123456789";
        endif;

        $charArray = str_split($chars);
        for($i = 0; $i < $len; $i++) {
                $randItem = array_rand($charArray);
                $result .="".$charArray[$randItem];
        }
        return $result;
    }
    /*
    |----------------------------------------------
    |         Ends of id genaretor
    |----------------------------------------------
    */


	public function document()
	{
		$data['title'] = display('document_list');
		$data['documents'] = $this->document_model->read();
		$data['content'] = $this->load->view('document',$data,true);
		$this->load->view('layout/main_wrapper',$data);
	}



    public function document_form()
    {
        $data['title'] = display('add_document');
        /*----------VALIDATION RULES----------*/
        $this->form_validation->set_rules('patient_id', display('patient_id') ,'required|max_length[30]');
        $this->form_validation->set_rules('doctor_name', display('doctor_id'),'max_length[11]');
        $this->form_validation->set_rules('description', display('description'),'trim');
        $this->form_validation->set_rules('hidden_attach_file', display('attach_file'),'required|max_length[255]');
        /*-------------STORE DATA------------*/
        $urole = $this->session->userdata('user_role');
        $data['document'] = (object)$postData = array(
            'patient_id'  => $this->input->post('patient_id'),
            'doctor_id'   => $this->input->post('doctor_id'),
            'description' => $this->input->post('description'),
            'hidden_attach_file' => $this->input->post('hidden_attach_file'),
            'date'        => date('Y-m-d'),
            'upload_by'   => (($urole==10)?0:$this->session->userdata('user_id'))
        );

        /*-----------CREATE A NEW RECORD-----------*/
        if ($this->form_validation->run() === true) {

            if ($this->document_model->create($postData)) {
                #set success message
                $this->session->set_flashdata('message', display('save_successfully'));
            } else {
                #set exception message
                $this->session->set_flashdata('exception',display('please_try_again'));
            }
            redirect('patient/document_form');
        } else {
            $data['doctor_list'] = $this->doctor_model->doctor_list();
            $data['content'] = $this->load->view('document_form',$data,true);
            $this->load->view('layout/main_wrapper',$data);
        }
    }
	 public function delete_profile_pic($id){
	      $this->db->set('picture','');
	      $this->db->where('id',$id);
	      $delete = $this->db->update('patient');
	    if ($delete) {
	      $this->session->set_flashdata('message',display('update_successfully'));
	      $this->session->unset_userdata('picture','');
	    } else {
	       $this->session->set_flashdata('exception', display('please_try_again'));
	    }
	      redirect('patient/');
	  }

    public function do_upload()
    {
        ini_set('memory_limit', '200M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);

        if (($_SERVER['REQUEST_METHOD']) == "POST") {
            $filename = $_FILES['attach_file']['name'];
            $filename = strstr($filename, '.', true);
            $email    = $this->session->userdata('email');
            $filename = strstr($email, '@', true)."_".$filename;
            $filename = strtolower($filename);
            /*-----------------------------*/

            $config['upload_path']   = FCPATH .'./assets/attachments/';
            // $config['allowed_types'] = 'csv|pdf|ai|xls|ppt|pptx|gz|gzip|tar|zip|rar|mp3|wav|bmp|gif|jpg|jpeg|jpe|png|txt|text|log|rtx|rtf|xsl|mpeg|mpg|mov|avi|doc|docx|dot|dotx|xlsx|xl|word|mp4|mpa|flv|webm|7zip|wma|svg';
            $config['allowed_types'] = '*';
            $config['max_size']      = 0;
            $config['max_width']     = 0;
            $config['max_height']    = 0;
            $config['file_ext_tolower'] = true;
            $config['file_name']     =  $filename;
            $config['overwrite']     = false;

            $this->load->library('upload', $config);

            $name = 'attach_file';
            if ( ! $this->upload->do_upload($name) ) {
                $data['exception'] = $this->upload->display_errors();
                $data['status'] = false;
                echo json_encode($data);
            } else {
                $upload =  $this->upload->data();
                $data['message'] = display('upload_successfully');
                $data['filepath'] = './assets/attachments/'.$upload['file_name'];
                $data['status'] = true;
                echo json_encode($data);
            }
        }
    }


    public function document_delete($id = null)
    {
    	if ($this->document_model->delete($id)) {

	    	$file = $this->input->get('file');
	    	if (file_exists($file)) {
	    		@unlink($file);
	    	}
    		$this->session->set_flashdata('message', display('save_successfully'));

    	} else {
    		$this->session->set_flashdata('exception', display('please_try_again'));
    	}

    	redirect($_SERVER['HTTP_REFERER']);
    }




}
