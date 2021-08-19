<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loinc_code extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("pagination");
		$this->load->model(array(
			'dashboard_super/loinc_code/lonic_model',
			'dashboard_super/admin/document_model',
			'doctor_model',
		));

		if ($this->session->userdata('isLogIn') == false
			|| $this->session->userdata('user_role') != 11)
			redirect('login');

	}

	public function index()
	{
		$data['title'] = 'Loinc code List';//display('admin_list');
		$isadmin = $this->session->userdata('isadmin');
		$loinc_code_id = $this->session->userdata('loinc_code_id');
		$config = array();
        $config["base_url"] = base_url()."dashboard_super/loinc_code/loinc_code/index";
        $total_rows = $this->db->count_all("loinc_code");        
        $config["total_rows"] = $total_rows;
        $config["per_page"] = 10;
        $config["uri_segment"] = 5;
        $config['num_tag_open'] = '<li>'; 
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">'; 
		$config['cur_tag_close'] = '</a></li>'; 
		$config['next_link'] = 'Next'; 
		$config['prev_link'] = 'Prev'; 
		$config['next_tag_open'] = '<li class="pg-next">'; 
		$config['next_tag_close'] = '</li>'; 
		$config['prev_tag_open'] = '<li class="pg-prev">'; 
		$config['prev_tag_close'] = '</li>'; 
		$config['first_tag_open'] = '<li>'; 
		$config['first_tag_close'] = '</li>'; 
		$config['last_tag_open'] = '<li>'; 
		$config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $admin =	$this->db->select("*")
					->from("loinc_code")					
					->limit($config["per_page"], $page)	
					->order_by("loinc_code_id","asc")							
					->get()
					->result();				
        $data["links"] = $this->pagination->create_links();


		
		$data['loinc_code'] = $admin; //$this->lonic_model->read();
		$data['content'] = $this->load->view('dashboard_super/loinc_code/loinc_code',$data,true);
		$this->load->view('dashboard_super/main_wrapper',$data);
	}

	public function sendEmailAttachment($to, $subject, $htmlMessage) {
			$this->load->library('email');
			$this->load->helper('email');
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			$this->email->from('sahil@gtimecs.org', SYS_NAME);
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($htmlMessage);
		//	if (!empty($pdf_name)) {
			//	$this->email->attach($pdf_name);
			//}
			@$this->email->send();
		}
		function admindetail()
		{

			$did = $this->input->get('d_id');
			$result = $this->db->select("*")->from("user")->where("loinc_code_id",$did)->get()->row();
		//	echo $this->db->last_query();
		//print_r($result);
			$department = $this->db->select("*")->from("department")->where("dprt_id",$result->department_id)->get()->row();
			$role = $this->db->select("*")->from("role")->where("r_id",$result->role_id)->get()->row();
			$result->contactinfo = $result->address;
			if($department){
					$result->department = $department->name;
			}else{
				$result->department = '';
			}

      if($role){
				$result->role = $role->name;
			}else{
				$result->role = '';
			}


			$result->fullname = $result->firstname.' '.$result->lastname;
			$result->date_of_birth = date('d/m/Y',strtotime($result->date_of_birth));


			$result->mobile = $result->mobile_prefix.' '.$result->mobile;
			$result->create_date = date('d/m/Y',strtotime($result->create_date));

			$result->status = ($result->status=='1')?'Active':'Inactive';
			$result->phone = ($result->phone!='')?$result->phone:'';
	$result->admin_access = ($result->is_admin=='1')?'Yes':'No';
			echo json_encode($result);
		}
		function admin_search()
		 {
		$p_id = trim($this->input->get_post('p_id'));

        $isadmin = $this->session->userdata('isadmin');
			$loinc_code_id = $this->session->userdata('loinc_code_id');


			 if($p_id!=''){
				 //if($isadmin=='1'){
					 $sql ="SELECT * FROM admin WHERE admin_id like '%".($p_id)."%' or fname like '%".($p_id)."%' or email like '%".($p_id)."%' or date_of_birth like '%".($p_id)."%' ORDER BY id DESC";
				// }else{
					// $sql ="SELECT * FROM admin WHERE (created_by = $loinc_code_id) and (admin_id like '%".($p_id)."%' or fname like '%".($p_id)."%' or email like '%".($p_id)."%' or date_of_birth like '%".($p_id)."%') ORDER BY id DESC";
				// }

				 $query = $this->db->query($sql);
					$searchdetail =  $query->result();
					$msg ='';
					//if(count($searchdetail)>0){
						foreach ($searchdetail as $value) {
							if($value->sex==''){
								 $value->sex='Male';
							 }
							$value->date_of_birth=date('d/m/Y',strtotime($value->date_of_birth));
							$msg.='<tr style="border-bottom: 1px solid #ddd;" class="hovertr"><td>';
							$msg.='<img  src="'.base_url().'assets/images/admin/2017-01-16/p5.png"></td>';
							$msg.='<td><div class="kpull-left"><div class="word-break">';
							$msg.='<span data-id="'.$value->admin_id.'" class="fa fa-circle" data-toggle="tooltip" title="admin online"> </span>';
							$msg.='<span class="text-primary">'.$value->fname.'  <br> <a href="#" class="color-black">'.$value->admin_id.'</a> </span>';
							$msg.='</div></div></td>';
							$msg.='<td><span class="text-primary">'.$value->lname.'</span></td>';
							$msg.='<td>'.$value->date_of_birth.'<br>'.$value->sex.'</td>';
							$msg.='<td>'.$value->address1.' '.$value->address2.' '.$value->city.' '.$value->state.' '.$value->zip.'<br><span class="light-grey">M</span>'.$value->mobile.'<span class="light-grey ml-15">H</span>'.$value->phone.'</td>';
							$msg.= '<td>'.date('d/m/Y',strtotime($value->create_date)).'</td>';
							$msg.= '<td><span style="color:green" class="btn btn-default">'.(($value->status==1)?'Active':'Inactive').'</span></td>';
					//	 $msg.='<td class="pt-15"><div class="btn-group" style="float: right;display: flex;"><a href="'.base_url("admin/edit/$value->id").'" class="btn btn-xs btn-default" style="margin-right:10px;"><i class="fa fa-edit"></i></a>';
						// $msg.='<a href="'.base_url("admin/delete/$value->id").'" class="btn btn-xs btn-danger" onclick="return confirm('.display('are_you_sure').')"><i class="fa fa-trash"></i></a></div>
																						// </td>
								$msg.='</tr>';

							 //else{
							//   $value->sex='F';
							// }


		//   $value->date_of_birth=date('d/m/Y',strtotime($value->date_of_birth));
		//$value->age = $diff->y;
							//$data[] = $value;
						}


					 echo json_encode($msg);
				 }else{
					 $ds = "";
					 echo json_encode($ds);

				 }

			 // }else{
			 //
			 //
			 //   $lead = $this->lonic_model->read();
			 //   if(count($lead)>0){
			 //     foreach ($lead as $value) {
			 //       if($value->sex=='Male'){
			 //         $value->sex='M';
			 //       }else{
			 //         $value->sex='F';
			 //       }
			 //       $value->date_of_birth=date('Y-m-d',strtotime($value->date_of_birth));
			 //      // $value->picture = ($value->picture!='')?$value->picture:"assets/images/admin/2017-01-16/p5.png";
			 //       // code...
			 //       $data[] = $value;
			 //     }
			 //   }else{
			 //     $data = array();
			 //   }
			 //   echo json_encode($data);
			 // }
				 //$sql  = "SELECT * ";





		 }
		 function admin_report()
	 	{
	 		$this->load->library('dompdf_gen');
	 		$pdfname = "admin" . date('YmdHis') . '.pdf';
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
	             <h5>admin Report</h5>
	 						<table border="1" width="100%" class="pricedetail" style="margin-top: 1px;">
	 								 <tr>
	 										<th>Name</th>

	 										<th>DOB</th>
	 										<th>Contact Info</th>
	 										<th>Email</th>
	 										<th>Join Date</th>
	 										<th>Status</th>
	 								</tr>';
	 							//$doctors =	$this->doctor_model->read();

$isadmin = $this->session->userdata('isadmin');
			$loinc_code_id = $this->session->userdata('loinc_code_id');
			if($isadmin=='1'){
				$admins =	$this->db->select("*")
						->from("admin")
						->order_by('id','desc')
						->get()
						->result();
			}else{
				$admins =	$this->db->select("*")
						->from("admin")
						->where("created_by",$loinc_code_id)
						->order_by('id','desc')
						->get()
						->result();
			}

	 						//$admins =	$this->lonic_model->read();
	 foreach ($admins as $admin) {
	 //$role = $this->db->select("*")->from("role")->where("r_id",$doctor->role_id)->get()->row();
	 								$html .='<tr>
	 										<td>'.$admin->fname.' '.$admin->lname.'</td>

	 										<td>'.date('d/m/Y',strtotime($admin->date_of_birth)).'<br>'.$admin->sex.'</td>
	 										<td align="left">
	 												<p>'.$admin->address1.' '.$admin->address2.' '.$admin->city.' '.$admin->state.' '.$admin->zip.'</p>
	 												<p>M '.$admin->mobile.'</p>
	 												<p>H '.$admin->phone.'</p>

	 										</td>
	 										<td>'.$admin->email.'</td>';

	 										$html .='<td>'.date('d/m/Y',strtotime($admin->create_date)).'</td>';

	 									if($admin->status==1){
	 										$status = 'Active';
	 									}else{
	 										$status = 'Inactive';
	 									}
	 										$html .='<td>'.$status.'</td>
	 								</tr>';
	 						}

	 						$html .='</table>';
	 $this->dompdf->load_html($html);
	 		$this->dompdf->render();
	 		$output = $this->dompdf->output();
	 		//print_r($output);
	 		file_put_contents('pdf/' . $pdfname . '', $output);
	 		redirect('pdf/' . $pdfname . '', $output);
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
	'color' => array('rgb' => 'ffff03'),
	),
	'font' => array(
	'color' => array('rgb' => '000000'),
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
	'color' => array('rgb' => 'ffff03'),
	),
	'font' => array(
	'color' => array('rgb' => '000000'),
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
	'color' => array('rgb' => '000000'),
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
	'color' => array('rgb' => 'ffff03'),
	),
	'font' => array(
	'color' => array('rgb' => '000000'),
	'size' => 12,
	'name' => 'Arial',
	'bold' => true,
	),
	);

	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'admin Report');

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

	$objPHPExcel->getActiveSheet()->setCellValue('A2', 'ID');
	$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Name');
	$objPHPExcel->getActiveSheet()->setCellValue('C2', 'DOB');
	$objPHPExcel->getActiveSheet()->setCellValue('D2', 'SEX');
	$objPHPExcel->getActiveSheet()->setCellValue('E2', 'address');
	$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Mobile');
	$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Phone');
	$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Join Date');
	$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Status');




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
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

	$row = 3;
	//$custDtaData  = $this->panel_internal_model->panel_sale_list_all(0);
	//$doctors =	$this->doctor_model->read();
	$isadmin = $this->session->userdata('isadmin');
				$loinc_code_id = $this->session->userdata('loinc_code_id');
				if($isadmin=='1'){
					$admins =	$this->db->select("*")
							->from("admin")
							->order_by('id','desc')
							->get()
							->result();
				}else{
					$admins =	$this->db->select("*")
							->from("admin")
							->where("created_by",$loinc_code_id)
							->order_by('id','desc')
							->get()
							->result();
				}
//	$admins =	$this->lonic_model->read();
	foreach ($admins as $value)
	{

	if($value->status==1){
		$value->status='Active';
	}else{
		$value->status='Inactive';
	}
	$value->date_of_birth = date('d/m/Y',strtotime($value->date_of_birth));
	$value->create_date = date('d/m/Y',strtotime($value->create_date));
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $value->admin_id);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $value->fname.' '.$value->lname);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $value->date_of_birth);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $value->sex);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $value->address1.' '.$value->address2.' '.$value->city.' '.$value->state.' '.$value->zip);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $value->email);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $value->mobile);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $value->phone);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $value->create_date);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $value->status);
				$row++;
	}


	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="admin_report.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');










		}
		public function changestatus()
		{
			$id = $this->input->get_post('id');
			$value = $this->input->get_post('value');

		 $arr['status'] = $value;
		 $this->db->where('loinc_code_id',$id);
		 $this->db->update('loinc_code',$arr);
		 echo 'success';
		 exit;
		}
		public function subloinc_codechangestatus()
		{
			$id = $this->input->get_post('id');
			$value = $this->input->get_post('value');

		 $arr['status'] = $value;
		 $this->db->where('loinc_code_id',$id);
		 $this->db->update('loinc_code_type',$arr);
		 echo 'success';
		 exit;
		}
    public function email_check($email, $id)
    {
        $emailExists = $this->db->select('email')
            ->where('email',$email)
            ->where_not_in('loinc_code_id',$id)
            ->get('user')
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
			$data['title'] = 'Add Loinc Code';//display('add_admin');


			#-------------------------------#

			$this->form_validation->set_rules('loinc_code', 'Loinc Code','required');





			$pid = "P".$this->randStrGen(2,7);
			if ($this->input->post('loinc_code_id') == null) { //create a admin
				$data['loinc_code'] = (object)$postData = [
					'loinc_code'   		   => $this->input->post('loinc_code'),
					'status'   		   => '1',

					'loinc_code_id'   		   => $this->input->post('loinc_code_id')



				//	'insurance'       => $this->input->post('insurance'),
				//	'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
					//
				];
			} else { // update loinc_code
				$data['loinc_code'] = (object)$postData = [
					'loinc_code'   		   => $this->input->post('loinc_code'),
					'status'   		   => '1',

					'loinc_code_id'   		   => $this->input->post('loinc_code_id')
				//	'insurance'       => $this->input->post('insurance'),
					//'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
					//
				];

			}
			#-------------------------------#
			if ($this->form_validation->run() === true) {

				#if empty $id then insert data
				if (empty($postData['loinc_code_id'])) {
					if ($this->lonic_model->create($postData)) {
						$admin_id = $this->db->insert_id();



						#set success message


						$this->session->set_flashdata('message', display('save_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception', display('please_try_again'));
					}
		redirect('dashboard_super/loinc_code/loinc_code/');
					//redirect('admin/profile/' . $admin_id);
				} else {
					if ($this->lonic_model->update($postData)) {
						#set success message
						$this->session->set_flashdata('message', display('update_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception', display('please_try_again'));
						redirect('dashboard_super/loinc_code/loinc_code/edit/'.$postData['loinc_code_id']);
					}

					redirect('dashboard_super/loinc_code/loinc_code/');
				}

			} else {
				$data['content'] = $this->load->view('dashboard_super/loinc_code/loinc_code_form',$data,true);
				$this->load->view('dashboard_super/main_wrapper',$data);
			}
		}

		public function upload_loniccode(){

			if (!empty($_FILES['loinc_codefile']['name'])) {

				if (!empty($_FILES['loinc_codefile']['name'])) {
					$ext = pathinfo(($_FILES['loinc_codefile']['name']), PATHINFO_EXTENSION);
					$allowed = array('csv','xls');
					if (!in_array($ext, $allowed)) {
						$this->session->set_flashdata('fail',"Please Upload CSV Or xls File");
						redirect(base_url('dashboard_super/loinc_code/loinc_code/upload_loniccode'));
					}

					$filename  = "assets/loinccodefile/".date('Ymdhis')."_".rand(999,9999)."_".$_FILES['loinc_codefile']['name'];
					$file_data = $_FILES['loinc_codefile']['tmp_name'];
					$count=0;
	        		$fp = fopen($_FILES['loinc_codefile']['tmp_name'],'r') or die("can't open file");
	        		move_uploaded_file($_FILES['loinc_codefile']['tmp_name'], $filename);
			        while($csv_line = fgetcsv($fp,1024))
			        {
			        	$query = "SELECT * FROM loinc_code WHERE loinc_code='$csv_line[0]'";
	        			$resultcheckExistingData = $this->db->query($query);
	        			$countExistingData = $resultcheckExistingData->num_rows();
			        	if($countExistingData == 0)
	        			{
				            $count++;
				            if($count == 1)
				            {
				                continue;
				            }
			                $insert_csv = array();
			                $insert_csv['loinc_code'] = $csv_line[0];//remove if you want to have primary key,
			                $insert_csv['status'] = 1;
				            $data = array( 'loinc_code'=>$insert_csv['loinc_code'], 'status' => $insert_csv['status']);


				            $this->db->insert('loinc_code', $data);

				        }else{
				        	$this->session->set_flashdata('fail',"Lonic Code Already Exists.");
							redirect(base_url('dashboard_super/lonic_code/lonic_code/upload_loniccode'));
				        }
			        }
			        fclose($fp) or die("can't close file");
							$this->session->set_flashdata('message',"Import successfully.");
			       	redirect(base_url('dashboard_super/loinc_code/loinc_code'));
				}else{
					$this->session->set_flashdata('fail',"Lonic Code File Required.");
					redirect(base_url('dashboard_super/lonic_code/lonic_code/upload_loniccode'));
				}
			}
			$data['title'] = 'Add Lonic Code';
			$data['content'] = $this->load->view('dashboard_super/loinc_code/loniccode_upload',$data,true);
			$this->load->view('dashboard_super/main_wrapper',$data);
		}


		public function subloinc_codecreate()
		{
			$data['title'] = 'Add admin';//display('add_admin');

      $data['parent_category'] = $this->db->select("*")->from('loinc_code_type')->where('parent_id','0')->get()->result();
			#-------------------------------#

			$this->form_validation->set_rules('loinc_code_name', 'loinc_code name','required');





			$pid = "P".$this->randStrGen(2,7);
			if ($this->input->post('loinc_code_id') == null) { //create a admin
				$data['loinc_code'] = (object)$postData = [
					'loinc_code_name'   		   => $this->input->post('loinc_code_name'),
					'parent_id'   		   => $this->input->post('parent_id'),
					'status'   		   => '1',
					'date'   		   => date('Y-m-d'),
					'loinc_code_id'   		   => $this->input->post('loinc_code_id')



				//	'insurance'       => $this->input->post('insurance'),
				//	'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
					//
				];
			} else { // update loinc_code
				$data['loinc_code'] = (object)$postData = [
					'loinc_code_name'   		   => $this->input->post('loinc_code_name'),
					'parent_id'   		   => $this->input->post('parent_id'),
					'status'   		   => '1',
					'date'   		   => date('Y-m-d'),
					'loinc_code_id'   		   => $this->input->post('loinc_code_id')
				//	'insurance'       => $this->input->post('insurance'),
					//'insurance_file'       => (!empty($insurance)?$insurance:$this->input->post('old_insurance')),
					//
				];

			}
			#-------------------------------#
			if ($this->form_validation->run() === true) {
				//echo $this->input->post('parent_id');
				//exit;
				#if empty $id then insert data
				if (empty($postData['loinc_code_id'])) {
					if ($this->lonic_model->create($postData)) {
						$admin_id = $this->db->insert_id();



						#set success message


						$this->session->set_flashdata('message', display('save_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception', display('please_try_again'));
					}
		redirect('dashboard_super/loinc_code/loinc_code/subcategory/');
					//redirect('admin/profile/' . $admin_id);
				} else {
					if ($this->lonic_model->update($postData)) {
						#set success message
						$this->session->set_flashdata('message', display('update_successfully'));
					} else {
						#set exception message
						$this->session->set_flashdata('exception', display('please_try_again'));
						redirect('dashboard_super/loinc_code/loinc_code/edit/'.$postData['id']);
					}

					redirect('dashboard_super/loinc_code/loinc_code/subcategory/');
				}

			} else {
				$data['content'] = $this->load->view('dashboard_super/loinc_code/subloinc_code_form',$data,true);
				$this->load->view('dashboard_super/main_wrapper',$data);
			}
		}
	  public function delete_profile_pic($id){
	      $this->db->set('picture','');
	      $this->db->where('loinc_code_id',$id);
	      $delete = $this->db->update('user');
	    if ($delete) {
	      $this->session->set_flashdata('message',display('update_successfully'));
	      $this->session->unset_userdata('picture','');
	    } else {
	       $this->session->set_flashdata('exception', display('please_try_again'));
	    }
	      redirect('dashboard_super/loinc_code/loinc_code/');
	  }
	public function createvital()
	{
		$data['title'] = display('add_vital_sign');
				$id = $this->input->post('vital_id');
		#-------------------------------#

		$this->form_validation->set_rules('admin_id', display('admin_id'),'required|max_length[50]');
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
		if ($this->input->post('id') == null) { //create a admin
			$data['loinc_code'] = (object)$postData = [
				'vital_id'   		   => $this->input->post('vital_id'),
				'admin_id'   => $this->input->post('admin_id'),
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
		} else { // update admin
			$data['loinc_code'] = (object)$postData = [
				'vital_id'   		   => $this->input->post('vital_id'),
				'admin_id'   => $this->input->post('admin_id'),
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
				if ($this->lonic_model->createvital($postData)) {
					$vital_id = $this->db->insert_id();
					#set success message

					$this->session->set_flashdata('message', display('save_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				}
			$result =	$this->db->select('*')
								 ->from('admin')
								 ->where('admin_id',$this->input->post('admin_id'))->get()->row();

				redirect('dashboard_super/loinc_code/loinc_code/profile/' . $result->id);
				//	redirect('dashboard_super/loinc_code/loinc_code');
			} else {
				if ($this->lonic_model->updatevital($postData)) {
					#set success message
					$this->session->set_flashdata('message', display('update_successfully'));
				} else {
					#set exception message
					$this->session->set_flashdata('exception', display('please_try_again'));
				}
				redirect('dashboard_super/admin/editvital/'.$postData['vital_id']);
		//		redirect('admin');
			}

		} else {
			$data['content'] = $this->load->view('dashboard_super/admin/vital_form',$data,true);
			$this->load->view('dashboard_super/main_wrapper',$data);
		}
	}
	public function profile($admin_id = null)
	{
		$data['title'] =  display('admin_information');
		#-------------------------------#
		$data['profile'] = $this->lonic_model->read_by_id($admin_id);
		$data['documents'] = $this->document_model->read_by_admin($admin_id);
		$this->db->select('*')
		         ->from('pa_vital_sign')
		         ->join('admin', 'admin.admin_id = pa_vital_sign.admin_id')
						 ->where('admin.id',$admin_id)->order_by('pa_vital_sign.created_time','desc')->order_by('pa_vital_sign.created_date','desc');
		$result = $this->db->get();
	//	echo $this->db->last_query();
		$data['vital'] = $result->result();


		$data['content'] = $this->load->view('dashboard_super/loinc_code/loinc_code_profile',$data,true);
		$this->load->view('dashboard_super/main_wrapper',$data);
	}


	public function edit($admin_id = null)
	{
		$data['title'] = 'Edit';//display('admin_edit');
		#-------------------------------#
		$data['loinc_code'] = $this->lonic_model->read_by_id($admin_id);
		$data['content'] = $this->load->view('dashboard_super/loinc_code/loinc_code_form',$data,true);
		$this->load->view('dashboard_super/main_wrapper',$data);
	}
	public function subloinc_codeedit($admin_id = null)
	{
		$data['title'] = 'Edit';//display('admin_edit');
		#-------------------------------#
		$data['loinc_code'] = $this->lonic_model->read_by_id($admin_id);
		$data['parent_category'] = $this->db->select("*")->from('loinc_code_type')->where('parent_id','0')->get()->result();
	//	print_r($data);
		//exit;
		$data['content'] = $this->load->view('dashboard_super/loinc_code/subloinc_code_form',$data,true);
		$this->load->view('dashboard_super/main_wrapper',$data);
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
		$data['documents'] = $this->document_model->read($this->session->userdata('loinc_code_id'));
		$data['content'] = $this->load->view('dashboard_super/admin/document',$data,true);
		$this->load->view('dashboard_super/main_wrapper',$data);
	}

	public function delete($loinc_code_id = null)
	{
		if ($this->lonic_model->delete($loinc_code_id)) {
		//	$this->delete_admin_mag($loinc_code_id);
			#set success message
			$this->session->set_flashdata('message','loinc code Removed!');   //display('delete_successfully')
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('dashboard_super/loinc_code/loinc_code');
	}
	public function subloinc_codedelete($loinc_code_id = null)
	{
		if ($this->lonic_model->delete($loinc_code_id)) {
	//		$this->delete_admin_mag($loinc_code_id);
			#set success message
			$this->session->set_flashdata('message','loinc_code Removed!');   //display('delete_successfully')
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect('dashboard_super/loinc_code/loinc_code/subcategory');
	}


	public function delete_admin_mag($id)
	    {
	        $this->db->where('from_loinc_code_id',$id);
			$this->db->delete('contact_super_admin');

			$this->db->where('to_loinc_code_id',$id);
			$this->db->delete('contact_super_admin');
	    }


    public function document_form()
    {
        $data['title'] = display('add_document');
        /*----------VALIDATION RULES----------*/
        $this->form_validation->set_rules('admin_id', display('admin_id') ,'required|max_length[30]');
        $this->form_validation->set_rules('description', display('description'),'trim');
        $this->form_validation->set_rules('hidden_attach_file', display('attach_file'),'required|max_length[255]');
        /*-------------STORE DATA------------*/
        $urole = $this->session->userdata('user_role');
        $data['document'] = (object)$postData = array(
            'admin_id'  => $this->input->post('admin_id'),
            'doctor_id'   => $this->session->userdata('loinc_code_id'),
            'description' => $this->input->post('description'),
            'hidden_attach_file' => $this->input->post('hidden_attach_file'),
            'date'        => date('Y-m-d'),
            'upload_by'   => (($urole==10)?0:$this->session->userdata('loinc_code_id'))
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
            redirect('dashboard_super/loinc_code/loinc_code/document_form');
        } else {
            $data['doctor_list'] = $this->doctor_model->doctor_list();
            $data['content'] = $this->load->view('dashboard_super/admin/document_form',$data,true);
            $this->load->view('dashboard_super/main_wrapper',$data);
        }
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
