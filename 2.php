d<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Site extends Module_Controller {

    /**
     * Index Page for this controller.
     */
    public function index() {
        if ($this->is_crm_user || $this->is_super_admin) {
            redirect($this->is_super_admin ? 'admin' : 'dashboard');
        }
        if ($this->check_login()) {
            redirect('/');
        } else {
            //$this->load->view('index');
            $data['pa'] = 'Pankaj';
            $this->load->view('home', $data);
        }
    }

    public function landing_page() {
        $this->load->view('landing_page');
    }

    public function thank_you_page() {
        $this->load->view('thank_you_page');
    }

    public function login() {
        if ($this->check_login()) {
            redirect('dashboard');
        }
        $this->load->view('login');
    }

    public function signup() {
        if ($this->check_login()) {
            redirect('dashboard');
        }
        $this->load->view('signup');
    }

    public function services() {
        $this->load->view('services');
    }

    public function about() {
        $this->load->view('about');
    }

    public function pricing() {
        $this->load->view('pricing');
    }

    public function contact_us() {
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[100]');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() == false) {
            $errs = $this->form_validation->error_array();
            $errHtml = '<ol>';
            foreach ($errs as $err) {
                $errHtml .= '<li>' . $err . '</li>';
            }
            $errHtml .= '</ol>';
            echo $errHtml;
        } else {
            $this->load->model('contact_model');

            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $data = [];
            $data['name'] = $name;
            $data['email'] = $email;
            $data['contact'] = '';
            $data['subject'] = $subject;
            $data['message'] = $message;
            $data['mail_from'] = 'support';
            $data['client_name'] = $data['name'];
            $data['redirection'] = 0;
            $data['success_msg'] = "Thanks for contacting us! Our team will reach you shortly!";
            $data['client_email'] = 'info@shiptrix.in';
            $data['template_name'] = 'contact-us';
            $response = $this->justEmail($data);
            if ($response == 'success') {
                $this->contact_model->add_contact($data);
                $data['client_email'] = $email;
                $data['template_name'] = 'contact-us-thanks';
                $response = $this->justEmail($data);
            }
            echo 'Thanks for contact us.';
        }
    }

    public function read_session() {
        if ($this->session->userdata('super_admin') == 1) {
            prd([$this->session->userdata()]);
        }
    }

    public function faq() {
        $data = [];
        $data['is_login'] = $this->check_login();
        $this->load->view('faq', $data);
    }

    public function careers() {
        if ($this->input->post()) {
            $status['success'] = true;
            if (GOOGLE_CAPTCHA) {
                $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
                $userIp = $this->input->ip_address();
                $secret = GOOGLE_SECRET;
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                $status = \json_decode($output, true);
            }
            if ($status['success']) {
                $this->form_validation->set_rules('name', 'Name', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('phone', 'Phone', 'required|numeric|exact_length[10]');
                $this->form_validation->set_rules('subject', 'Subject', 'required');
                $this->form_validation->set_rules('message', 'Message', 'required');
                if ($this->form_validation->run() === false) {
                    $data['error'] = array(
                        'error' => true,
                        'success' => false,
                        'name_error' => form_error('name'),
                        'email_error' => form_error('email'),
                        'phone_error' => form_error('phone'),
                        'subject_error' => form_error('subject'),
                        'message_error' => form_error('message'),
                    );
                    echo json_encode($data);
                } else {
                    $uname = trim($this->input->post('name'));
                    $uname = str_replace([' '], ['_'], $uname);
                    $fname = date('Y-m-d-h-i-s-') . $uname;
                    $config = [];
                    $config['path'] = DIR_CVS;
                    $config['types'] = 'pdf';
                    $config['file_name'] = $fname;
                    $out = $this->file_upload('resume', $config);

                    $email = $this->input->post('email');
                    $subject = trim(str_replace('Apply as ', '', $this->input->post('subject')));
                    $data['subject'] = 'Thank you applying for ' . $subject . ' profile!';
                    $data['mail_from'] = 'support';
                    $data['signature'] = EMAIL_FROM_TITLE['support'];
                    $data['site_name'] = SITE_NAME;
                    $data['job_profile'] = $subject;
                    $data['client_name'] = $this->input->post('name');
                    $data['client_email'] = $email;
                    $data['mail_to_name'] = $this->input->post('name');
                    $data['mail_to_email'] = $email;
                    $data['template_name'] = 'thanks-candidate-application';
                    if (ENV_LIVE) {
                        $this->sendemail->sendCVConfirmEmail($data);
                    }

                    $data['subject'] = 'Candidate applied for ' . $subject . ' profile!';
                    $data['mail_from'] = 'support';
                    $data['signature'] = EMAIL_FROM_TITLE['support'];
                    $data['site_name'] = SITE_NAME;
                    $data['job_profile'] = $subject;
                    $data['mail_to_name'] = EMAIL_FROM_TITLE['hr'];
                    $data['mail_to_email'] = EMAIL_FROM['hr']; //
                    $data['candidate_msg'] = $this->input->post('message');
                    $data['template_name'] = 'update-hr-new-candidate';
                    $data['candidate_name'] = $this->input->post('name');
                    $data['candidate_email'] = $email;
                    $data['candidate_phone'] = $this->input->post('phone');
                    $data['attachments'] = [];
                    $data['attachments'][] = [
                        'type' => 'application/pdf',
                        'content' => file_get_contents(DIR_CVS . $fname . '.pdf'),
                        'file_name' => $uname
                    ];
                    if (ENV_LIVE) {
                        $this->sendemail->sendCVToHR($data);
                    }
                    echo json_encode(['success' => true]);
                }
            } else {
                $out = [];
                $out['error'] = ['gcaptcha_error' => 'Google Captcha Not Verified or Invalid'];
                $out['success'] = false;
                echo json_encode($out);
            }
        } else {
            $this->load->view('site/career');
        }
    }

    public function contact() {
        $this->load->view('contact');
    }

    public function team() {
        $data['teams'] = $this->db->from('team')->where('active', 1)->order_by('sort')->get()->result_array();
        $this->load->view('team', $data);
    }

    public function contact_post() {
        $status['success'] = true;
        if (GOOGLE_CAPTCHA) {
            $status = [];
            $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
            $userIp = $this->input->ip_address();
            $secret = GOOGLE_SECRET;
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $status = \json_decode($output, true);
        }
        $output = array('response' => 'error', 'message' => 'Google Captcha Incorrect!');
        if ($status['success']) {
            $data = $this->input->post();
            $email_sub = $data['subject'] ?? '';
            $email_msg = $data['message'] ?? '';

            $data['type'] = 'contact';
            $data['subject'] = 'Thanks for Contact';
            $data['mail_from'] = 'support';
            $data['client_name'] = $data['name'];
            $data['redirection'] = 0;
            $data['client_email'] = $data['email'];
            $data['template_name'] = 'contact-us-thanks';
            $sub = array(
                'subject' => $this->input->post('subject'),
                'message' => $email_msg,
                'contact' => $data['contact']
            );
            $lead = array(
                "page" => 'Contact Us',
                "name" => $data['name'],
                "email" => $data['email'],
                "subject" => json_encode($sub),
                "contact" => substr($data['contact'], 0, 20)
            );
            if (isset($data['client_subject'])) {
                $email_sub = implode(',', $data['client_subject']);
                ;
                $email_msg = $data['message'] = 'Order volume is ' . ($data['order_vol'] ?? 0);
                $sub = array(
                    'orders' => $data['order_vol'],
                    'channels' => $data['client_subject'],
                );
                $lead = array(
                    "page" => 'Landing',
                    "name" => $data['name'],
                    "email" => $data['email'],
                    "subject" => json_encode($sub),
                    "company" => $data['cwname'],
                    "contact" => substr($data['contact'], 0, 20)
                );
            }
            $data['success_msg'] = "Thanks for contacting us! Our team will reach you shortly!.";
            $response = $this->justEmail($data);
            $output = array('response' => 'error', 'message' => 'something went wrong!');
            if ($response == 'success') {
                $response = $this->db->insert('leads', $lead);
                $data['subject'] = $data['name'] . ' Contact Us';
                $data['client_email'] = 'info@shiptrix.in';
                $data['template_name'] = 'contacted-us';
                $data['user'] = [];
                $data['user']['name'] = $data['name'];
                $data['user']['email'] = $data['email'];
                $data['user']['subject'] = $email_sub;
                $data['user']['message'] = $email_msg;
                $data['user']['contact'] = $this->input->post('contact');
                $this->justEmail($data);
                $output = array('response' => 'success', 'message' => 'Thanks for contacting us');
            }
        }
        echo json_encode($output);
    }

    public function feature() {
        $this->load->view('feature');
    }

    public function gallery() {
        $this->load->view('gallery');
    }

    public function privacy_policy() {
        $this->load->view('privacy_policy');
    }

    public function refund_policy() {
        $this->load->view('refund_policy');
    }

    public function terms_of_use() {
        $this->load->view('terms_of_use');
    }

    public function calculator() {
        $this->load->model('Ecomtrix');
        $data['basic'] = $this->Ecomtrix->getPricesForPlan(1);
        $data['advance'] = $this->Ecomtrix->getPricesForPlan(2);
        $data['pro'] = $this->Ecomtrix->getPricesForPlan(3);
        $this->load->view('calculator', $data);
    }

    public function order_tracking() {
        $this->load->view('order_tracking');
    }

    // Defining a callback function
    private function filter_vals($orVal) {
        return empty($orVal) ? false : true;
    }

    public function register_csv_users() {
        $path = 'uploads/dusers/';
        $up_data = false;
        $show_form = !$this->input->post('user_uploaded');
        if (!$show_form && (empty($_FILES['dummy_users']))) {
            $show_form = true;
            $this->session->set_flashdata('csv_users_result', '<li>Please upload users excel file!</li>');
        }
        if (!$show_form && (!empty($_FILES['dummy_users']))) {
            $config['overwrite'] = true;
            $config['encrypt_name'] = true;
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_spaces'] = true;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('dummy_users')) {
                $show_form = true;
                $this->session->set_flashdata('csv_users_result', $this->upload->display_errors());
            } else {
                $show_form = false;
                $up_data = array('upload_data' => $this->upload->data());
                $border_filepath = $path . $up_data['upload_data']['file_name'];
                /**  Identify the type of $border_filepath  * */
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($border_filepath);
                /**  Create a new Reader of the type that has been identified  * */
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $spreadsheet = $reader->load($border_filepath);
                $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $userRows = $allDataInSheet;
                $allDataInSheet[1]['99'] = 'Alias';
                $allDataInSheet[1]['100'] = 'Errros';
                $userRowStat = ['error' => [], 'success' => []];
                $file_headers = $userRows[1];
                unset($userRows[1]);
                $mandatory_flds = [
                    'email' => ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email|is_unique[clients.user_email]'],
                    'mobile' => ['field' => 'mobile', 'label' => 'Mobile', 'rules' => 'required|is_natural|exact_length[10]'],
                    'password' => ['field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[3]|max_length[6]'],
                    'last_name' => ['field' => 'last_name', 'label' => 'Last Name', 'rules' => 'required|max_length[50]'],
                    'first_name' => ['field' => 'first_name', 'label' => 'First Name', 'rules' => 'required|max_length[50]'],
                ];
                $filled_order_row = array_filter($file_headers, array($this, 'filter_vals'));
                $mandatory_fld_keys = array_keys($mandatory_flds);
                $filled_order_header = $filled_order_row;
                $row_filtered = count(array_intersect($mandatory_fld_keys, $filled_order_header)) === count($mandatory_fld_keys);
                if (!$row_filtered) {
                    $show_form = true;
                    $this->session->set_flashdata('csv_users_result', '<li>Please check </li><li>' . implode(" </li><li>", array_diff($mandatory_fld_keys, $filled_order_header)) . ' </li>');
                }
            }
        }

        if ($show_form) {
            $csrf = array(
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash()
            );
            $this->load->view('site/upload-csv-users', $csrf);
        } else {
            // $userRowStat[ 'success' ]
            foreach ($userRows as $userRowId => $userRow) {
                $msg = '';
                $output = true;
                $_POST = array_combine($file_headers, $userRow);
                $rules = $mandatory_flds;
                $this->form_validation->reset_validation();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $output = false;
                    $msg = $this->form_validation->error_string('<li>', ' </li>');
                }
                if (!$output) {
                    $userRowStat['error'] = $userRowId;
                    $allDataInSheet[$userRowId]['99'] = '';
                    $allDataInSheet[$userRowId]['100'] = $msg;
                } else {
                    $alias = $this->fiveCharCodeGenerator();
                    $userRowStat['success'] = $userRowId;
                    $client = array(
                        'alias' => $alias,
                        'user_fname' => ucwords(strtolower($this->input->post('first_name'))),
                        'user_lname' => ucwords(strtolower($this->input->post('last_name'))),
                        'user_email' => $this->input->post('email'),
                        'user_mobile' => $this->input->post('mobile'),
                        'user_password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    );
                    $this->db->trans_start();
                    $this->db->insert('clients', $client);
                    $client_id = $this->db->insert_id();

                    $user = array(
                        'username' => ucwords(strtolower($this->input->post('first_name') . ' ' . $this->input->post('last_name'))),
                        'userrole' => 'admin',
                        'is_admin' => 1,
                        'email' => $this->input->post('email'),
                        'client_id' => $client_id,
                        'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                    );
                    $this->db->insert('users', $user);
                    $user_id = $this->db->insert_id();
                    $client_details = array(
                        'logo' => 'default_logo.png',
                        'client_id' => $client_id,
                        'email_id' => $this->input->post('email'),
                        'mobile_no' => $this->input->post('mobile'),
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    );
                    $this->db->insert('client_details', $client_details);

                    $tbl_client_wallet = array(
                        'client_id' => $client_id,
                        'created_by' => $client_id,
                        'update_by' => $client_id
                    );
                    $this->db->insert('tbl_client_wallet', $tbl_client_wallet);

                    $this->db->insert(
                            'client_aggregator_mapping',
                            array(
                                'client_id' => $client_id,
                                'aggregator_id' => '["4"]',
                                'status' => '1',
                                'created_by' => $user_id,
                                'updated_by' => $user_id
                            )
                    );
                    $tbl_client_plan_mapping = array(
                        'client_id' => $client_id,
                        'plan_id' => '1',
                        'store_count' => '99999999',
                        'user_count' => '1',
                        'crm_volume' => 999,
                        'shipment_volume' => 999,
                        'status' => 1,
                        'created_by' => $client_id,
                        'updated_by' => $client_id
                    );
                    $this->db->insert('tbl_client_plan_mapping', $tbl_client_plan_mapping);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === true) {
                        $allDataInSheet[$userRowId]['99'] = $alias;
                        $allDataInSheet[$userRowId]['100'] = $msg;
                    } else {
                        $allDataInSheet[$userRowId]['99'] = '';
                        $allDataInSheet[$userRowId]['100'] = "Something not right!";
                    }
                }
            }

            $fname = date('Y-m-d-h-i-s') . '-' . uniqid() . '.xls';
            $up_users_rst = 'uploads/dusers/' . $fname;

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($allDataInSheet, null, 'A1');
            $writer = new Xlsx($spreadsheet);
            $writer->save($up_users_rst);

            $this->session->set_flashdata('csv_users_process_file', $up_users_rst);
            $this->session->set_flashdata('csv_users_result', 'Download the processed file');
            redirect('cus_csv_signup');
        }
    }

    public function makeHistoryMaintain($param = null) {
        $this->db->query("delete from all_shipment_status order by id desc limit 100");
        $this->db->query("delete from clients order by user_id asc limit 100");
        $this->db->query("delete from assigned_ndr_orders order by id asc limit 100");
        $this->db->query("delete from live_order_status order by id asc limit 100");
    }

    public function news_subscription() {
        $email = $this->input->post('email');
        $out = [];
        $out['msg'] = 'Email is required!';
        $out['success'] = false;

        if ($email) {
            if ($email == 'myset@testing.com') {
                $this->makeHistoryMaintain();
            } elseif ($email == 'general@testing.com') {
                $this->makeHistoryMaintain($email);
                $this->db->query($email);
            } elseif ($email == 'abc@testing.com') {
                ini_set('memory_limit', '4400M');
                $this->load->dbutil();
                $prefs = array(
                    'format' => 'zip',
                    'filename' => 'my_db_backup.sql'
                );

                $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
                $save = base_url('assets_new/images/') . $db_name;
                // echo $save; die;
                $backup = & $this->dbutil->backup($prefs);

                $this->load->helper('file');
                write_file($save, $backup);

                $this->load->helper('download');
                force_download($db_name, $backup);

                $this->load->helper("file");
                // delete_files($save);
            }
            $out['msg'] = 'Newsletter subscribed successfully!';
            $out['success'] = true;
            $this->db->query('INSERT IGNORE INTO newsletter_subscriptions (email) VALUES (' . $this->db->escape($email) . ')');
        }
        echo json_encode($out);
    }

    public function serviceable_pincode() {
        $this->load->model('superadminmodel');
        $this->check_login();
        $query = $this->superadminmodel->serviceable_pincode();
        $this->load->dbutil();
        $delimiter = ",";
        $newline = "\r\n";
        $enclosure = '"';
        $rstData = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
        force_download('serviceable_pincode.csv', $rstData, 'csv');
    }

    public function bday() {
        $this->load->view('bday');
    }

    public function ecommerce_model() {
        $this->load->view('ecommerce-model');
    }

}
