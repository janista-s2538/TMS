<?php
class pump extends Controller {

  public function officer_coupon(){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
		switch($p):
      case'driver-is-borrow':
        $year = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : date('Y');
        $month = isset($_GET['month']) && !empty($_GET['month']) ? $_GET['month'] : date('m');
        $business = isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
        $status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '';
        $coupon_type = isset($_GET['coupon_type']) && !empty($_GET['coupon_type']) ? $_GET['coupon_type'] : '';
        $is_invoice = isset($_GET['is_invoice']) && !empty($_GET['is_invoice']) ? $_GET['is_invoice'] : '';
        $data['filter']['year'] = $year;
        $data['filter']['month']=$month;
        $data['filter']['business']=$business;
        $data['filter']['status']=$status;
        $data['filter']['coupon_type']=$coupon_type;
        $data['filter']['is_invoice']=$is_invoice;
        $data['year']=$pump_model->YearOptionList();
        $data['month']=MonthTH();
        $data['business'] = $pump_model->SrtBusinessOptionList();
        $data['driver'] = $pump_model->getDriverIsBorrow($data['filter']);
        $template = $this->loadView('admin/pump/report_driver_is_borrow');
        $template->set('data',$data);
        $template->render();
      break;
    default:
      $year = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : date('Y');
      $month = isset($_GET['month']) && !empty($_GET['month']) ? $_GET['month'] : date('m');
      $business = isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
      $status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '';
      $coupon_type = isset($_GET['coupon_type']) && !empty($_GET['coupon_type']) ? $_GET['coupon_type'] : '';
      $is_invoice = isset($_GET['is_invoice']) && !empty($_GET['is_invoice']) ? $_GET['is_invoice'] : '';
      $data['filter']['year'] = $year;
      $data['filter']['month']=$month;
      $data['filter']['business']=$business;
      $data['filter']['status']=$status;
      $data['filter']['coupon_type']=$coupon_type;
      $data['filter']['is_invoice']=$is_invoice;
      $data['year']=$pump_model->YearOptionList();
      $data['month']=MonthTH();
      $data['business'] = $pump_model->SrtBusinessOptionList();
      $data['site_business'] = $pump_model->getOfficerCouponSrtSiteAndSrtBusiness($year,$month,$business,$status,$coupon_type,$is_invoice);
      if(!empty($data['site_business']['site_name'])){
        foreach ($data['site_business']['site_name'] as $skey => $svalue) {
          $SrtSite_id=isset($svalue['SrtSite_id']) && !empty($svalue['SrtSite_id']) ? $svalue['SrtSite_id'] : 0;
          $SrtBusiness_id=isset($svalue['SrtBusiness_id']) && !empty($svalue['SrtBusiness_id']) ? $svalue['SrtBusiness_id'] : 0;
          $data['coupon_details'][$SrtSite_id] = $pump_model->CouponOfficerDetails($SrtSite_id,$SrtBusiness_id,$year,$month,$business,$status,$coupon_type,$is_invoice);
        }
      }

      $data['site_list'] = $pump_model->CouponTypeList($year,$month,$business,$status,$is_invoice,$coupon_type);

      $template = $this->loadView('admin/pump/report_officer_create_coupon');
      $template->set('data',$data);
  		$template->render();
    endswitch;
  }

	function conpon(){
		$pump_model = $this->loadModel('admin/pump_model');
		$p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
		switch($p):
			case'cpac-coupon-edit':
				$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
				$data['coupon']=$pump_model->CpacCouponBYID($id);
				$data['list']=$pump_model->CPacCouponDetailsList($id);
				$template = $this->loadView('admin/pump/cpac_coupon_edit');
				$template->set('data',$data);
				$template->render();
			break;
			case 'update-cpacdpdaily-conpon':
				$id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $sdate = isset($_GET['sdate']) && !empty($_GET['sdate']) ? $_GET['sdate'] : '';
        $driver_id = isset($_GET['driver_id']) && !empty($_GET['driver_id']) ? $_GET['driver_id'] : '';
        $car_id = isset($_GET['car_id']) && !empty($_GET['car_id']) ? $_GET['car_id'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
				if(!empty($id)){
					$chackconpon = $pump_model->ChackConponId($id);
					if(!empty($chackconpon)){
						$Coupon_id = isset($chackconpon['id']) && !empty($chackconpon['id']) ? $chackconpon['id'] : '';
						$update = $pump_model->getUpdate("tbl_CpacDpDaily", " Coupon_id=".$Coupon_id, array(
							"is_coupon" => 0,
							"Coupon_id" => 0,
						));
						if ($update===true) {
                $update_Coupon = $pump_model->getUpdate("tbl_Coupon", " id=".$Coupon_id, array(
                  "status" => 9,
                ));
                if ($update_Coupon===true) {
						    	pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/conpon/?p=cpac-coupon-edit&id=".$Coupon_id."");
                }
						  }
					}
				}
			break;
      case 'cut-dp':
        $DpDaily_id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $sdate = isset($_GET['sdate']) && !empty($_GET['sdate']) ? $_GET['sdate'] : '';
        $driver_id = isset($_GET['driver_id']) && !empty($_GET['driver_id']) ? $_GET['driver_id'] : '';
        $car_id = isset($_GET['car_id']) && !empty($_GET['car_id']) ? $_GET['car_id'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
				if(!empty($DpDaily_id)){
          $chack = $pump_model->ChackCpacDpDaily($DpDaily_id);
					if(!empty($chack['id'])){
						$update = $pump_model->getUpdate("tbl_CpacDpDaily", " id=".$DpDaily_id, array("is_coupon" => 1));
						if ($update===true) {
						    pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/conpon/?sdate=".$sdate."&driver_id=".$driver_id."&car_id=".$car_id."&site=".$site."");
            }else{
              sAlert($this->lang->alert->fail);
            }
					}
				}
      break;
      case 'return-dp':
        $DpDaily_id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $sdate = isset($_GET['sdate']) && !empty($_GET['sdate']) ? $_GET['sdate'] : '';
        $driver_id = isset($_GET['driver_id']) && !empty($_GET['driver_id']) ? $_GET['driver_id'] : '';
        $car_id = isset($_GET['car_id']) && !empty($_GET['car_id']) ? $_GET['car_id'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
				if(!empty($DpDaily_id)){
          $chack = $pump_model->ChackCpacDpDaily($DpDaily_id);
					if(!empty(($chack['id']))){
						$update = $pump_model->getUpdate("tbl_CpacDpDaily", " id=".$DpDaily_id, array("is_coupon" => 0));
						if ($update===true) {
              pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/conpon/?sdate=".$sdate."&driver_id=".$driver_id."&car_id=".$car_id."&site=".$site."");
            }else{
              sAlert($this->lang->alert->fail);
            }
					}
				}
      break;
			default:
	      $code = isset($_GET['code']) && !empty($_GET['code']) ? $_GET['code'] : '';
				$sdate = isset($_GET['sdate']) && !empty($_GET['sdate']) ? $_GET['sdate'] : '';
				$date = dateBTW($sdate);
				$startdate = isset($date['start']) && !empty($date['start']) ? $date['start'] : '';
				$enddate = isset($date['end']) && !empty($date['end']) ? $date['end'] : '';
				$driver_id= isset($_GET['driver_id']) && !empty($_GET['driver_id']) ? $_GET['driver_id'] : '';
				$driver_new= isset($_GET['driver_new']) && !empty($_GET['driver_new']) ? $_GET['driver_new'] : '';
				$car_id= isset($_GET['car_id']) && !empty($_GET['car_id']) ? $_GET['car_id'] : '';
				$site= isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
	      $data['filter']['code']=$code;
				$data['filter']['sdate'] = $sdate;
				$data['filter']['car_id'] = $car_id;
			//  print_r($data['filter']['code']);exit;
				$data['filter']['startdate'] = $startdate;
				$data['filter']['enddate'] = $enddate;
				$data['filter']['driver_id'] = $driver_id;
				$data['filter']['site'] = $site;

				$data['site']=$pump_model->SiteCpacOptionList();
				$data['driver_id']=$pump_model->DriverCpacOptionList($site);
				$data['dp'] = $pump_model->CpacDpDailyList($data['filter']);
				$data['dp_iscoupon'] = $pump_model->CpacDpDailyIsConPonList($data['filter']);
				$data['car'] = $pump_model->GetCarCpacDpDaily($driver_id,$startdate,$enddate);
				$template = $this->loadView('admin/pump/cpac_coupon_list');
				$template->set('data',$data);
				$template->render();
		endswitch;
	}
  public function route(){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch($p):
      case'sil':
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $customer = isset($_GET['customer']) && !empty($_GET['customer']) ? $_GET['customer'] : '';
        $search= isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
        $data['filter']['customer']=$customer;
        $data['filter']['site']=$site;
        $data['filter']['search']=$search;
        $data['site']=$pump_model->SilSiteOptionList();
        $data['customer']=$pump_model->SilCustomerOptionList();
        $data['sil_site_node']=$pump_model->SilSiteNode($data['filter']);
        $template = $this->loadView('admin/pump/pump_route_sil');
        $template->set('data',$data);
        $template->render();
      break;
      case'sil-route':
        $customer = isset($_GET['customer']) && !empty($_GET['customer']) ? $_GET['customer'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $search= isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
        $data['filter']['customer']=$customer;
        $data['filter']['site']=$site;
        $data['filter']['search']=$search;
        $data['site']=$pump_model->SilSiteOptionList();
        $data['customer']=$pump_model->SilCustomerOptionList();
        $data['sil_site_node']=$pump_model->SilSiteNodeStatus0($data['filter']);
        $template = $this->loadView('admin/pump/pump_route_sil_add');
        $template->set('data',$data);
        $template->render();
      break;
      case 'chemical':
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $customer = isset($_GET['customer']) && !empty($_GET['customer']) ? $_GET['customer'] : '';
        $contract= isset($_GET['contract']) && !empty($_GET['contract']) ? $_GET['contract'] : '';
        $search= isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
        $data['filter']['site']=$site;
        $data['filter']['customer']=$customer;
        $data['filter']['contract']=$contract;
        $data['filter']['search']=$search;
        $data['route']=$pump_model->ChemicalRouteList($data['filter']);
        $data['site']=$pump_model->ChemicalOilSiteOptionList();
        $data['customer']=$pump_model->CustomerOptionList();
        $data['contract']=$pump_model->ContractAllOptionList($data['filter']);
        $template = $this->loadView('admin/pump/pump_route_chemical');
        $template->set('data',$data);
        $template->render();
      break;
      case 'chemical-route':
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $customer = isset($_GET['customer']) && !empty($_GET['customer']) ? $_GET['customer'] : '';
        $contract= isset($_GET['contract']) && !empty($_GET['contract']) ? $_GET['contract'] : '';
        $search= isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
        $data['filter']['site']=$site;
        $data['filter']['customer']=$customer;
        $data['filter']['contract']=$contract;
        $data['filter']['search']=$search;
        $data['site']=$pump_model->ChemicalOilSiteOptionList();
        $data['customer']=$pump_model->CustomerOptionList();
        $data['contract']=$pump_model->ContractOptionList($data['filter']);
        $data['chemical_route']=$pump_model->ChemicalRoute($data['filter']);
        $template = $this->loadView('admin/pump/pump_route_chemical_add');
        $template->set('data',$data);
        $template->render();
      break;
      case 'oil':
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $customer = isset($_GET['customer']) && !empty($_GET['customer']) ? $_GET['customer'] : '';
        $search= isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
        $data['filter']['site']=$site;
        $data['filter']['customer']=$customer;
        $data['filter']['search']=$search;
        $data['site']=$pump_model->OilSiteOptionList();
        $data['customer']=$pump_model->OilCustomerOptionList();
        $data['oil_route']=$pump_model->OilRoute($data['filter']);
        $template = $this->loadView('admin/pump/pump_route_oil');
        $template->set('data',$data);
        $template->render();
      break;
      case 'oil-route':
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $customer = isset($_GET['customer']) && !empty($_GET['customer']) ? $_GET['customer'] : '';
        $search= isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : '';
        $data['filter']['site']=$site;
        $data['filter']['customer']=$customer;
        $data['filter']['search']=$search;
        $data['site']=$pump_model->OilSiteOptionList();
        $data['customer']=$pump_model->OilCustomerOptionList();
        $data['oil_route']=$pump_model->OilRouteStatus0($data['filter']);
        $template = $this->loadView('admin/pump/pump_route_oil_add');
        $template->set('data',$data);
        $template->render();
      break;
    default:
    $template = $this->loadView('admin/pump/pump_route_menu');
    $template->render();
    endswitch;
  }
  public function coupon(){
    $pump_model = $this->loadModel('admin/pump_model');
    $master_model = $this->loadModel('admin/master_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch($p):
      case 'expire':
        $year = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : date('Y');
        $business = isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $data['filter']['year'] = $year;
        $data['filter']['business']=$business;
        $data['filter']['site']=$site;
        $data['year']=$pump_model->YearOptionList();
        $data['month']=MonthTH();
        $data['business'] = $pump_model->SrtBusinessCoupouExpireOptionList();
        $data['site'] = $pump_model->SrtSiteCoupouExpireOptionList();
        $data['expire']=$pump_model->CouponExpire($data['filter']);
        $template = $this->loadView('admin/pump/pump_coupon_expire');
        $template->set('data',$data);
        $template->render();
      break;
      case 'details':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        if (!empty($id)) {
          $data['coupon_details']=$pump_model->CouponDetails($id);
          $template = $this->loadView('admin/pump/pump_coupon_details');
          $template->set('data',$data);
          $template->render();
        }
      break;
      default:
        $code = isset($_GET['code']) && !empty($_GET['code']) ? $_GET['code'] : '';
        $check= isset($_GET['check']) && !empty($_GET['check']) ? $_GET['check'] : '';
        $business = isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $truck_license = isset($_GET['truck_license']) && !empty($_GET['truck_license']) ? $_GET['truck_license'] : '';
        $driver = isset($_GET['driver']) && !empty($_GET['driver']) ? $_GET['driver'] : '';
        $is_refuel = isset($_GET['is_refuel']) && !empty($_GET['is_refuel']) ? $_GET['is_refuel'] : '';
        $user_refueler = isset($_GET['user_refueler']) && !empty($_GET['user_refueler']) ? $_GET['user_refueler'] : '';
        $status = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '';
        $sdate = isset($_GET['sdate']) && !empty($_GET['sdate']) ? $_GET['sdate'] : date('d/m/Y',strtotime('- 5 day')).' - '.date('d/m/Y',strtotime('+ 3 day'));
        $date = dateBTW($sdate);
        $startdate = isset($date['start']) && !empty($date['start']) ? $date['start'] : date('d/m/Y');
        $enddate = isset($date['end']) && !empty($date['end']) ? $date['end'] : date('d/m/Y');
        $data['filter']['sdate'] = $sdate;
        $data['filter']['startdate'] = $startdate;
        $data['filter']['enddate'] = $enddate;
        $data['filter']['status']=$status;
        $data['filter']['business']=$business;
    		$data['filter']['site']=$site;
        $data['filter']['truck_license']=$truck_license;
        $data['filter']['driver']=$driver;
        $data['filter']['is_refuel']=$is_refuel;
        $data['filter']['user_refueler']=$user_refueler;
        $data['filter']['code']=$code;
        $data['filter']['check']=$check;

        $data['year']=$master_model->YearOptionList();
        $data['month']=MonthTH();

        $data['business']=$pump_model->GroupSrtBusinessOptionList();
        $data['site']=$pump_model->GroupSrtSiteCouponOptionList();

        $data['company'] = $pump_model->CompanyOptionList();
        $data['srt_business']=$pump_model->SrtBusinessOptionList();
        $data['srt_site']=$pump_model->SrtSiteOptionList();
        $data['truck_license']=$pump_model->TruckLicenseOptionList($data['filter']);
        $data['driver']=$pump_model->DriverEmployeeOptionList($data['filter']);
        $data['user_refueler']=$pump_model->UserRefuelerOptionList($data['filter']);
        $data['coupon']=$pump_model->CouponAll($data['filter']);
        $template = $this->loadView('admin/pump/pump_coupon_list');
        $template->set('data',$data);
        $template->render();
    endswitch;
  }
  public function user(){
    $admin_model = $this->loadModel('admin/user_model');
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch($p):
      case'reset-pin-code':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $update = $pump_model->getUpdate("base_UserRefueler"," id=".$id, array("pin_code"=>NULL,"is_pin"=>0));
        if($update===TRUE){
          pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/user/?p=edit&id=".$id);
        }else{
          sAlert($this->lang->alert->error->save);
        }
      break;
      case 'edit':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        if(!empty($id)){
          $data['business']=$pump_model->SrtBusinessOptionList();
          $data['site']=$pump_model->SrtSiteOptionList();
          $data['user']=$pump_model->getUserPump($id);
          $data['UserSiteRefueler']=$pump_model->UserSiteRefuelerList($id);
          $data['user_site'] = $admin_model->UserSiteBusinessOptionList($id);
          $template = $this->loadView('admin/pump/pump_user_edit');
          $template->set('data',$data);
          $template->render();
        }
      break;
      default:
        $business = isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $data['filter']['business'] = $business;
        $data['filter']['site'] = $site;
        $data['site']=$pump_model->UserRefuelerSrtSiteGroupList();
        $data['business']=$pump_model->UserRefuelerSrtBusinessGroupList();
        $data['list']=$pump_model->UserPumpList($data['filter']);
        $template = $this->loadView('admin/pump/pump_user_list');
        $template->set('data',$data);
        $template->render();
    endswitch;
  }
  public function driver(){
    $admin_model = $this->loadModel('admin/user_model');
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    $data['srt_site']=$pump_model->SrtSiteOptionList();
    $data['srt_business']=$pump_model->SrtBusinessOptionList();
    switch($p):
      case'reset-driver-pin-code':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $update = $pump_model->getUpdate("tbl_DriverEmployee"," id=".$id, array("pin_code"=>NULL,"is_pin"=>0));
        if($update===TRUE){
          pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/driver/?p=driver-edit&id=".$id);
        }else{
          sAlert($this->lang->alert->error->save);
        }
      break;
      case 'driver-edit':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        if (!empty($id)) {
          $data['driverEdit']=$pump_model->getDriverEdit($id);
          $template = $this->loadView('admin/pump/pump_driver_user_edit');
          $template->set('data', $data);
          $template->render();
        }
      break;
      case'reset-device-model':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $update = $pump_model->getUpdate("tbl_DriverEmployee"," id=".$id, array("device_model"=>NULL));
        if($update===TRUE){
          sAlert($this->lang->alert->success->save);
          jsRedirect($this->baseurl."admin/pump/driver/?p=driver-edit&id=".$id);
        }else{
          sAlert($this->lang->alert->error->save);
        }
      break;
      default:
        $business = isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
        $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
        $data['filter']['business'] = $business;
        $data['filter']['site'] = $site;
        $data['site']=$pump_model->DriverSrtSiteGroupList();
        $data['business']=$pump_model->DriverSrtBusinessGroupList();
        $data['DriverList']=$pump_model->DriverList($data['filter']);
        $template = $this->loadView('admin/pump/pump_driver_user_list');
        $template->set('data',$data);
        $template->render();
    endswitch;
  }

  public function load(){
      $pump_model = $this->loadModel('admin/pump_model');
      $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
      switch ($p):
          case'job-diff-hours':
            $time_in = isset($_POST['time_in']) && !empty($_POST['time_in']) ? $_POST['time_in'] : '';
            $time_out = isset($_POST['time_out']) && !empty($_POST['time_out']) ? $_POST['time_out'] : '';
            $hourdiff =0;
              if(!empty($time_in) && !empty($time_out)){
                $in=strtotime($time_in);
                $out=strtotime($time_out);
                if($out>$in){
                  $hourdiff = round(($out - $in)/3600, 2);
                }
                if(!empty($hourdiff)){
                  echo '<input type="text" class="form-control" name="worklate_hrs" id="worklate_hrs" style="width: 21pc;" value="'.$hourdiff.'" readonly>';
                }
              }elseif($time_in==0 || $time_out==0){
                echo '<input type="text" class="form-control" name="worklate_hrs" id="worklate_hrs" style="width: 21pc;" value="" readonly>';
              }


          break;
          case 'change-status':
            $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
            if(!empty($id)){
              $check=$pump_model->getUserPump($id);
              if(!empty($check['id'])){
                $status=isset($_POST['value']) && !empty($_POST['value']) ? $_POST['value'] : '';
                $update = $pump_model->getUpdate("base_UserRefueler"," id=".$id, array(
                  "status" => $status
                ));
              }
            }
          break;
          case 'change-status-driver':
            $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
            if(!empty($id)){
              $check=$pump_model->getDriverEdit($id);
              if(!empty($check['id'])){
                $status=isset($_POST['value']) && !empty($_POST['value']) ? $_POST['value'] : '';
                $update = $pump_model->getUpdate("tbl_DriverEmployee"," id=".$id, array(
                  "status" => $status
                ));
              }
            }
          break;
          case 'lode-site':
            $business_id = isset($_POST['business_id']) && !empty($_POST['business_id']) ? $_POST['business_id'] : '';
            if(!empty($business_id)){
              $data=$pump_model->GetBusinessSiteOptionList($business_id);
              // print_r($data);exit;
              echo '<select class="form-control select5" id="SrtSite_id" name="SrtSite_id"  onchange="loadTruck(); loaddriver()" style="width: 21pc;"  required="">';
              echo '<option value="">เลือก</option>';
              if(!empty($data)){
                foreach ($data as $key=>$value){
                  echo '<option value="'.$value['id'].'"> '.$value['name'].'</option>';
                }
              }
              echo '</select>';
              echo'<script type="text/javascript">$(\'.select5\').css(\'width\',\'80%\').select2({allowClear:true});</script>';
            }else{
              echo '<select class="form-control" id="SrtSite_id" name="SrtSite_id"  onchange="loadTruck()" required="">';
              echo '<option value="">เลือก</option>';
              echo '</select>';
            }
          break;
          case'lode-truck':
            $site_id = isset($_POST['site_id']) && !empty($_POST['site_id']) ? $_POST['site_id'] : '';
            $business_id = isset($_POST['business_id']) && !empty($_POST['business_id']) ? $_POST['business_id'] : '';
            if(!empty($site_id)){
                $select_api_site = getAPI2($this->api->truck_site.'&site='.$site_id,$this->project->it_token);
                $data= isset($select_api_site['results']) && !empty($select_api_site['results']) ? $select_api_site['results'] : '';
                echo '<select class="form-control select5" id="truck_id" name="truck_id"   required="">';
                echo '<option value="">เลือก</option>';
                if($ref_site_id==16){
                  echo '<option value="CNLKU01,CNLKU01">CNLKU01</option>';
                  echo '<option value="CNLKU02,CNLKU02">CNLKU02</option>';
                }
                if($site_id==1 && $business_id==3){
                  echo '<option value="KONE01,KONE01">KONE01</option>';
                  echo '<option value="KONE02,KONE02">KONE02</option>';
                }
                if($site_id==1 && $business_id==9){
                  echo '<option value="WMHTANK,WMHTANK">WMHTANK</option>';
                  echo '<option value="LCBFL10,LCBFL10">LCBFL10</option>';
                  echo '<option value="LCBFL12,LCBFL12">LCBFL12</option>';
                }
                if($site_id==2 && $business_id==3){
                  echo '<option value="KONE04,KONE04">KONE04</option>';
                  echo '<option value="KONE05,KONE05">KONE05</option>';
                }
                if(!empty($data)){
                  foreach ($data as $skey=>$svalue){
                    echo '<option value="'.$svalue['code'].','.$svalue['license'].' "> '.$svalue['code'].' &raquo; '.$svalue['license'].'</option>';
                  }
                }
                echo '</select>';
                echo'<script type="text/javascript">$(\'.select5\').css(\'width\',\'80%\').select2({allowClear:true});</script>';
            }else{
              echo '<select class="form-control" id="truck_id" name="truck_id"  required="">';
              echo '<option value="">เลือก</option>';
              echo '</select>';
            }
          break;
          case'lode-driver':
            $site_id = isset($_POST['site_id']) && !empty($_POST['site_id']) ? $_POST['site_id'] : '';
            if(!empty($site_id)){
              $data=$pump_model->GetDriverEmployeeOptionList($site_id);
              echo '<select class="form-control select5" id="driver_id" name="driver_id"  required="">';
              echo '<option value="">เลือก</option>';
              if(!empty($data)){
                foreach ($data as $dkey=>$dvalue){
                  echo '<option value="'.$dvalue['id'].'"> '.$dvalue['fullname'].'</option>';
                }
              }
              echo '</select>';
              echo'<script type="text/javascript">$(\'.select5\').css(\'width\',\'80%\').select2({allowClear:true});</script>';
            }else{
              echo '<select class="form-control" id="driver_id" name="driver_id"  required="">';
              echo '<option value="">เลือก</option>';
              echo '</select>';
            }

          break;
          case'lode-customer':
            $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
            if(!empty($SrtBusiness_id) && $SrtBusiness_id !=2){
              $data=$pump_model->GetCustomerOptionList($SrtBusiness_id);
              echo '<select class="form-control select5" id="customr" name="customr">';
              echo '<option value="">เลือก</option>';
              if(!empty($data)){
                foreach ($data as $ddkey=>$ddvalue){
                  echo '<option value="'.$ddvalue['id'].','.$ddvalue['code'].'"> '.$ddvalue['code'].'</option>';
                }
              }
              echo '</select>';
              echo'<script type="text/javascript">$(\'.select5\').css(\'width\',\'80%\').select2({allowClear:true});</script>';
            }else if(!empty($SrtBusiness_id) && $SrtBusiness_id=2){
              $data=$pump_model->GetCustomerOptionList($SrtBusiness_id);
              echo '<select class="form-control select5" id="customr" name="customr">';
              if(!empty($data)){
                foreach ($data as $ddkey=>$ddvalue){
                  echo '<option value="'.$ddvalue['id'].','.$ddvalue['code'].'"> '.$ddvalue['code'].'</option>';
                }
              }
              echo '</select>';
              echo'<script type="text/javascript">$(\'.select5\').css(\'width\',\'80%\').select2({allowClear:true});</script>';
            }else{
              echo '<select class="form-control" id="customr" name="customr">';
              echo '<option value="">เลือก</option>';
              echo '</select>';
            }
          break;
        endswitch;
  }
  function update(){
      $pump_model = $this->loadModel('admin/pump_model');
      $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
      switch ($p):
        case'customer-name-edit':
          $id = isset($_POST['cid']) && !empty($_POST['cid']) ? $_POST['cid'] : '';
          $Customer = isset($_POST['customer_id']) && !empty($_POST['customer_id']) ? explode(",",$_POST['customer_id'] ): '';
          $Customer_id =  isset($Customer['0']) && !empty($Customer['0']) ? $Customer['0'] : '';
          $Customer_code =  isset($Customer['1']) && !empty($Customer['1']) ? $Customer['1'] : '';
          $checkCoupon = $pump_model->CheckCouponApp($id);
          if(!empty($checkCoupon['id'])){
            $update = $pump_model->conponEditSave(array("Customer_id"=>$Customer_id,"Customer_name"=>$Customer_code), " id=".$id);
           if ($update===true) {
             pageReload($this->lang->alert->success->save);
           } else {
               sAlert($this->lang->alert->error->save);
           }
         }

        break;
        case'sil-coupon-app-edit':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $oil_truck = isset($_POST['oil_truck']) && !empty($_POST['oil_truck']) ? $_POST['oil_truck'] : '';
          $oil_gen = isset($_POST['oil_gen']) && !empty($_POST['oil_gen']) ? $_POST['oil_gen'] : '';
          $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
          $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : 0;
          $checkCoupon = $pump_model->CheckCouponApp($id);
          if(!empty($checkCoupon['id'])){
             $update = $pump_model->conponEditSave(array("oil_truck"=>$oil_truck,"oil_gen"=>$oil_gen,"amount"=>$amount,"is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
              pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
          break;
        case'sil-dispatch-oil-coupon-edit':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : '';
          $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
          $check = $pump_model->CheckSilDispatchOilCoupon($id);
          if(!empty($check['id'])){
            $update = $pump_model->TLDispatchOilCouponEditSave(array("amount"=>$amount,"is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
              pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
        break;
        case'oil-coupon-app-edit':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $oil_truck = isset($_POST['oil_truck']) && !empty($_POST['oil_truck']) ? $_POST['oil_truck'] : '';
          $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
          $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : 0;
          $checkCoupon = $pump_model->CheckCouponApp($id);
          if(!empty($checkCoupon['id'])){
             $update = $pump_model->conponEditSave(array("oil_truck"=>$oil_truck,"amount"=>$amount,"is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
              pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
          break;
        case'oil-dispatch-oil-coupon-edit':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : '';
          $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
          $check = $pump_model->CheckOilDispatchOilCoupon($id);
          if(!empty($check['id'])){
            $update = $pump_model->OilDispatchOilCouponEditSave(array("amount"=>$amount,"is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
              pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
        break;
        case'coupon-app-edit':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $oil_truck = isset($_POST['oil_truck']) && !empty($_POST['oil_truck']) ? $_POST['oil_truck'] : '';
          $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
          $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : 0;
          $checkCoupon = $pump_model->CheckCouponApp($id);
          if(!empty($checkCoupon['id'])){
             $update = $pump_model->conponEditSave(array("oil_truck"=>$oil_truck,"amount"=>$amount,"is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
              pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
          break;
        case'dispatch-oil-coupon-edit':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : '';
          $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
          $check = $pump_model->CheckDispatchOilCoupon($id);
          if(!empty($check['id'])){
            $update = $pump_model->DispatchOilCouponEditSave(array("amount"=>$amount,"is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
              pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
        break;
        case'status-edit':
          $id = isset($_POST['sid']) && !empty($_POST['sid']) ? $_POST['sid'] : '';
          $status = isset($_POST['status']) && !empty($_POST['status']) ? $_POST['status'] : '';
          $update = $pump_model->conponEditSave(array("status"=>$status), " id=".$id);
          if ($update===true) {
              pageReload($this->lang->alert->success->save);
          } else {
              sAlert($this->lang->alert->error->save);
          }
        break;
        case'is-expired-update':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $is_expired1 = isset($_POST['is_expired']) && !empty($_POST['is_expired']) ? $_POST['is_expired'] : '';
          $cancel_expired_remark = isset($_POST['cancel_expired_remark']) && !empty($_POST['cancel_expired_remark']) ? $_POST['cancel_expired_remark'] : '';
          $is_expired=1;
          $is_approved=0;
          if($is_expired1=='on'){
            $is_expired=0;
            $is_approved=1;
          }
          $update = $pump_model->conponEditSave(array("status"=>1,"is_expired"=>$is_expired,"is_approved"=>$is_approved,"cancel_expired_remark"=>$cancel_expired_remark,"cancel_expired_at"=>date("Y-m-d H:i:s")), " id=".$id);
          if ($update===true) {
              pageReload($this->lang->alert->success->save);
          } else {
              sAlert($this->lang->alert->error->save);
          }
        break;
        case'refuel-date-edit':
          $id = isset($_POST['rdid']) && !empty($_POST['rdid']) ? $_POST['rdid'] : '';
          $refuel_date = insertDATE($_POST['refuel_date_after']);
          $update = $pump_model->conponEditSave(array("refuel_date"=>$refuel_date), " id=".$id);
          if ($update===true) {
              pageReload($this->lang->alert->success->save);
          } else {
              sAlert($this->lang->alert->error->save);
          }
        break;
        case'is-refuel-edit':
          $id = isset($_POST['irid']) && !empty($_POST['irid']) ? $_POST['irid'] : '';
          $is_refuel = isset($_POST['is_refuel_after']) && !empty($_POST['is_refuel_after']) ? $_POST['is_refuel_after'] : 0;
          $check = $pump_model->getCouponDetail($id);
          if(!empty($check['id'])){
            $Business_id = isset($check['Business_id']) && !empty($check['Business_id']) ? $check['Business_id'] : '';
            $OilCoupon_id = isset($check['OilCoupon_id']) && !empty($check['OilCoupon_id']) ? $check['OilCoupon_id'] : '';
            $update = $pump_model->conponEditSave(array("is_refuel"=>$is_refuel), " id=".$id);
            if ($update===true) {
                if($Business_id==1){
                  $pump_model->DispatchOilCouponEditSave(array("is_refuel"=>$is_refuel), " id=".$OilCoupon_id);
                }
                if($Business_id==2){
                  $pump_model->OilDispatchOilCouponEditSave(array("is_refuel"=>$is_refuel), " id=".$OilCoupon_id);
                }
                if($Business_id==4){
                  $pump_model->TLDispatchOilCouponEditSave(array("is_refuel"=>$is_refuel), " id=".$OilCoupon_id);
                }
                pageReload($this->lang->alert->success->save);
            } else {
                sAlert($this->lang->alert->error->save);
            }
          }
        break;
        case'refuel-at-edit':
          $id = isset($_POST['raid']) && !empty($_POST['raid']) ? $_POST['raid'] : '';
          $refuel_at = insertDATE($_POST['refuel_at_after']);
          $refuel_at_time = isset($_POST['refuel_at_time_after']) && !empty($_POST['refuel_at_time_after']) ? $_POST['refuel_at_time_after'] : '';
          //echo'<pre>'; print_r($refuel_at_time); exit;
          $update = $pump_model->conponEditSave(array("refuel_at"=>$refuel_at.' '.$refuel_at_time), " id=".$id);
          if ($update===true) {
              pageReload($this->lang->alert->success->save);
          } else {
              sAlert($this->lang->alert->error->save);
          }
        break;
        case'delete_UserSiteRefueler':
          $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
          $UserRefueler_id = isset($_GET['UserRefueler']) && !empty($_GET['UserRefueler']) ? $_GET['UserRefueler'] : '';
          if(!empty($id)){
              $id = $pump_model->UserSiteRefuelerDelete(" id=".$id);
              if($id ===TRUE){
            pageReload($this->lang->alert->success->delete,$this->baseurl.'admin/pump/user/?p=edit&id='.$UserRefueler_id);
          }else{
            sAlert($this->lang->alert->fail);
          }
          }
         break;
        case'add_site_business':
          $UserRefueler_id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
          $SrtSite_id = isset($_POST['SrtSite_id']) && !empty($_POST['SrtSite_id']) ? $_POST['SrtSite_id'] : '';
          $check = $pump_model->CheckUserSiteDuplicate($UserRefueler_id,$SrtSite_id,$SrtBusiness_id);
          if($check===FALSE){
            $insert = $pump_model->getSave("base_UserSiteRefueler",array("UserRefueler_id"=>$UserRefueler_id,"SrtSite_id"=>$SrtSite_id,"SrtBusiness_id"=>$SrtBusiness_id));
            if(!empty($insert)){
              pageReload($this->lang->alert->success->add,$this->baseurl.'admin/pump/user/?p=edit&id='.$UserRefueler_id);
            }else{
              sAlert($this->lang->alert->error->add);
            }
          }else if($check===TRUE){
            sAlert($this->lang->alert->duplicate);
          }else{
            sAlert($this->lang->alert->fail);
          }
        break;
        case 'chemical-change-is-paper':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          if(!empty($id)){
            $check=$pump_model->getChemicalRoute($id);
            if(!empty($check['id'])){
              $is_paper=isset($_POST['value']) && !empty($_POST['value']) ? $_POST['value'] : '';
              $update = $pump_model->getUpdate("tbl_Route"," id=".$id, array(
                "is_paper" => $is_paper
              ));
            }
          }
        break;
        case'update-route-sil':
          $route = isset($_POST['route']) && !empty($_POST['route']) ? $_POST['route'] : '';
          if(!empty($route)){
            $no=0;
            $count_route = count($route);
            foreach ($route as $skey) {
              $id=$skey;
              $update = $pump_model->getUpdate("TL_sRoute", " id=".$id, array("use_app" =>1));
              if ($update===true) {
                $no++;
              }
              if ($count_route==$no) {
                pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/route/?p=sil");
              }
            }
          }else{
            pageReload('คุณไม่ได้เลือกเส้นทาง!',$this->baseurl."admin/pump/route/?p=sil-route");
          }
        break;
        case'update-use-app-sil':
          $route = isset($_POST['route']) && !empty($_POST['route']) ? $_POST['route'] : '';
          if(!empty($route)){
            $no=0;
            $count_route = count($route);
            foreach ($route as $skey) {
              $id=$skey;
              $update = $pump_model->getUpdate("TL_sRoute", " id=".$id, array("use_app" =>0,"is_paper" =>0));
              if ($update===true) {
                $no++;
              }
              if ($count_route==$no) {
                pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/route/?p=sil");
              }
            }
          }else{
            pageReload('คุณไม่ได้เลือกเส้นทาง!',$this->baseurl."admin/pump/route/?p=sil");
          }
        break;
        case 'sil-change-is-paper':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          if(!empty($id)){
            $check=$pump_model->getSilRoute($id);
            if(!empty($check['id'])){
              $is_paper=isset($_POST['value']) && !empty($_POST['value']) ? $_POST['value'] : '';
              $update = $pump_model->getUpdate("TL_sRoute"," id=".$id, array(
                "is_paper" => $is_paper
              ));
            }
          }
        break;
        case 'oil-change-is-paper':
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          if(!empty($id)){
            $check=$pump_model->getOilRoute($id);
            if(!empty($check['id'])){
              $is_paper=isset($_POST['value']) && !empty($_POST['value']) ? $_POST['value'] : '';
              $update = $pump_model->getUpdate("tbl_OilRoute"," id=".$id, array(
                "is_paper" => $is_paper
              ));
            }
          }
        break;
        case'update-route-oil':
          $route = isset($_POST['route']) && !empty($_POST['route']) ? $_POST['route'] : '';
          if(!empty($route)){
            $no=0;
            $count_route = count($route);
            foreach ($route as $skey) {
              $id=$skey;
              $update = $pump_model->getUpdate("tbl_OilRoute", " id=".$id, array("use_app" =>1));
              if ($update===true) {
                $no++;
              }
              if ($count_route==$no) {
                pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/route/?p=oil");
              }
            }
          }else{
            pageReload('คุณไม่ได้เลือกเส้นทาง!',$this->baseurl."admin/pump/route/?p=oil-route");
          }
        break;
        case'update-use-app-oil':
          $route = isset($_POST['route']) && !empty($_POST['route']) ? $_POST['route'] : '';
          if(!empty($route)){
            $no=0;
            $count_route = count($route);
            foreach ($route as $skey) {
              $id=$skey;
              $update = $pump_model->getUpdate("tbl_OilRoute", " id=".$id, array("use_app" =>0,"is_paper" =>0));
              if ($update===true) {
                $no++;
              }
              if ($count_route==$no) {
                pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/route/?p=oil");
              }
            }
          }else{
            pageReload('คุณไม่ได้เลือกเส้นทาง!',$this->baseurl."admin/pump/route/?p=oil");
          }
        break;
        case'update-route-chemical':
          $route = isset($_POST['route']) && !empty($_POST['route']) ? $_POST['route'] : '';
          if(!empty($route)){
            $no=0;
            $count_route = count($route);
            foreach ($route as $skey) {
              $id=$skey;
              $update = $pump_model->getUpdate("tbl_Route", " id=".$id, array("use_app" =>1));
              if ($update===true) {
                $no++;
              }
              if ($count_route==$no) {
                pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/route/?p=chemical");
              }
            }
          }else{
            pageReload('คุณไม่ได้เลือกเส้นทาง!',$this->baseurl."admin/pump/route/?p=chemical-route");
          }
        break;
        case'update-use-app-chemical':
          $route = isset($_POST['route']) && !empty($_POST['route']) ? $_POST['route'] : '';
          if(!empty($route)){
            $no=0;
            $count_route = count($route);
            foreach ($route as $skey) {
              $id=$skey;
              $update = $pump_model->getUpdate("tbl_Route"," id=".$id,array("use_app" =>0,"is_paper" =>0));
              if ($update===true) {
                $no++;
              }
              if ($count_route==$no) {
                pageReload($this->lang->alert->success->save,$this->baseurl."admin/pump/route/?p=chemical");
              }
            }
          }else{
            pageReload('คุณไม่ได้เลือกเส้นทาง!',$this->baseurl."admin/pump/route/?p=chemical");
          }
        break;
        case'update-driver':
        //echo'<pre>'; print_r($_POST); exit;
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $username = isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : '';
          $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
          $SrtSite_id = isset($_POST['SrtSite_id']) && !empty($_POST['SrtSite_id']) ? $_POST['SrtSite_id'] : '';
          $status = isset($_POST['status']) && !empty($_POST['status']) ? $_POST['status'] : 0;
          $no_minimum = isset($_POST['no_minimum']) && !empty($_POST['no_minimum']) ? $_POST['no_minimum'] : 0;
          $check = $pump_model->CheckUserDriverDuplicate($username,$id);
          if($check===FALSE){
          $update = $pump_model->SaveDriver(array("SrtSite_id" => $SrtSite_id,"SrtBusiness_id" => $SrtBusiness_id,"status" => $status,"no_minimum" => $no_minimum,"username" => $username, "updated_at"=>date("Y-m-d H:i:s")  ),"id=".$id);
          if($update===TRUE){
            sAlert($this->lang->alert->success->save);
            jsRedirect($this->baseurl."admin/pump/driver/?p=driver-edit&id=".$id);
            }else{
              sAlert($this->lang->alert->error->save);
            }
          }else if($check===TRUE){
              sAlert($this->lang->alert->duplicate);
              jsRedirect($this->baseurl."admin/pump/driver/?p=driver-edit&id=".$id);
            }else{
              sAlert($this->lang->alert->fail);
            }
        break;
        case 'update-pumplist':
          //print_r($_POST);exit;
          $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
          $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '';
          $tel = isset($_POST['tel']) && !empty($_POST['tel']) ? $_POST['tel'] : '';
          $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : '';
          $line_token = isset($_POST['line_token']) && !empty($_POST['line_token']) ? $_POST['line_token'] : '';
          $username = isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : '';
          $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
          $SrtSite_id = isset($_POST['SrtSite_id']) && !empty($_POST['SrtSite_id']) ? $_POST['SrtSite_id'] : '';
          if($SrtSite_id=='N'){
            $SrtSite_id = NULL;
            $SrtBusiness_id=NULL;
          }
          $user_level = isset($_POST['user_level']) && !empty($_POST['user_level']) ? $_POST['user_level'] : '';
          $status = isset($_POST['status']) && !empty($_POST['status']) ? $_POST['status'] : 0;
					$ref_id = isset($_POST['ref_id']) && !empty($_POST['ref_id']) ? $_POST['ref_id'] : '';
          $date = date("Y-m-d H:i:s");
          $check = $pump_model->CheckUserDuplicate($username,$id);
          if($check===FALSE){
            $update = $pump_model->getUpdate("base_UserRefueler"," id=".$id, array(
              "fullname" => $name,
              "telephone"=>$tel,
              "email"=>$email,
              "line_token"=>$line_token,
              "username"=>$username,
              "user_level"=>$user_level,
              "SrtSite_id"=>$SrtSite_id,
              "SrtBusiness_id"=>$SrtBusiness_id,
              "status"=>$status,
							"ref_id"=>$ref_id,
              "updated_at"=>$date
            ));
            if($update===TRUE){
              sAlert($this->lang->alert->success->save);
              jsRedirect($this->baseurl."admin/pump/user/?p=edit&id=".$id);
            }
          }else if($check===TRUE){
            sAlert($this->lang->alert->duplicate);
            jsRedirect($this->baseurl."admin/pump/user/?p=edit&id=".$id);
          }else{
            sAlert($this->lang->alert->fail);
          }
        break;
        case "update_password":
            $token = bin2hex(openssl_random_pseudo_bytes(64));
            $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : '';
            $id = isset($_POST['id2']) && !empty($_POST['id2']) ? $_POST['id2'] : '';
            $hashed_password = password_hash($password , PASSWORD_DEFAULT);
            $update = $pump_model->getUpdate("base_UserRefueler"," id=".$id, array(
              "password" => $hashed_password,
              "token"=>$token,
              "frist_login"=>1,
              "is_pin"=>0,
              "pin_code"=>NULL,
              "updated_at"=>date("Y-m-d H:i:s")
            ));
            if($update===TRUE){
              sAlert($this->lang->alert->success->save);
              jsRedirect($this->baseurl."admin/pump/user/?p=edit&id=".$id);
            }else{
              sAlert($this->lang->alert->error->save);
            }
        break;
        case "update_password_driver":
          $token = bin2hex(openssl_random_pseudo_bytes(64));
          $id = isset($_POST['id2']) && !empty($_POST['id2']) ? $_POST['id2'] : '';
          $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : '';
          $hashed_password = password_hash($password , PASSWORD_DEFAULT);
          $update = $pump_model->getUpdate("tbl_DriverEmployee"," id=".$id, array(
            "password" => $hashed_password,
            "token"=>$token,
            "frist_login"=>1,
            "is_pin"=>0,
            "pin_code"=>NULL,
            "updated_at"=>date("Y-m-d H:i:s")));
          if($update===TRUE){
            sAlert($this->lang->alert->success->save);
            jsRedirect($this->baseurl."admin/pump/driver/?p=driver-edit&id=".$id);
          }else{
            sAlert($this->lang->alert->error->save);
          }
      break;
      endswitch;
  }
  function save(){
    $pump_model = $this->loadModel('admin/pump_model');
    $action = isset($_POST['action']) && !empty($_POST['action']) ? $_POST['action'] : '';
    switch ($action):
      case 'user_driver_save':
        $fullname = isset($_POST['fullname']) && !empty($_POST['fullname']) ? $_POST['fullname'] : '';
        $username = isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : '';
        $identification = isset($_POST['identification']) && !empty($_POST['identification']) ? $_POST['identification'] : '';
        $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
        $SrtSite_id = isset($_POST['SrtSite_id']) && !empty($_POST['SrtSite_id']) ? $_POST['SrtSite_id'] : '';
        $check = $pump_model->CheckDriverEmployeeDuplicate($identification);
        if($check===FALSE){
          $insert = $pump_model->getSave("tbl_DriverEmployee",array("fullname"=>$fullname,"username"=>$username,"identification"=>$identification,"SrtSite_id"=>$SrtSite_id,"SrtBusiness_id"=>$SrtBusiness_id));
          if(!empty($insert)){
            pageReload($this->lang->alert->success->add,$this->baseurl.'admin/pump/driver/');
          }else{
            sAlert($this->lang->alert->error->add);
          }
        }else if($check===TRUE){
          sAlert($this->lang->alert->duplicate);
        }else{
          sAlert($this->lang->alert->fail);
        }
      break;
      case "userpump_save":
          $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '';
          $tel = isset($_POST['tel']) && !empty($_POST['tel']) ? $_POST['tel'] : '';
          $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : '';
          $line_token = isset($_POST['line_token']) && !empty($_POST['line_token']) ? $_POST['line_token'] : '';
          $username = isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : '';
          $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : '';
          $hashed_password = password_hash($password , PASSWORD_DEFAULT);
          $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
          $SrtSite_id = isset($_POST['SrtSite_id']) && !empty($_POST['SrtSite_id']) ? $_POST['SrtSite_id'] : '';
          $user_level = isset($_POST['group_privilege']) && !empty($_POST['group_privilege']) ? $_POST['group_privilege'] : '';
					$ref_id = isset($_POST['ref_id']) && !empty($_POST['ref_id']) ? $_POST['ref_id'] : '';
          $date = date("Y-m-d H:i:s");
          $token = bin2hex(openssl_random_pseudo_bytes(64));
          $check = $pump_model->CheckUserDuplicate($username);
          if($check===FALSE){
            if($SrtBusiness_id=='N') {
              $insert = $pump_model->getSave("base_UserRefueler",array(
              "fullname"=>$name,
              "telephone"=>$tel,
              "email"=>$email,
              "line_token"=>$line_token,
              "username"=>$username,
              "password"=>$hashed_password,
              "user_level"=>$user_level,
              "token"=>$token,
							"ref_id"=>$ref_id,
              "created_at"=>$date
              ));
            }else{
              $insert = $pump_model->getSave("base_UserRefueler",array(
                "fullname"=>$name,
                "telephone"=>$tel,
                "email"=>$email,
                "line_token"=>$line_token,
                "username"=>$username,
                "password"=>$hashed_password,
                "user_level"=>$user_level,
                "SrtSite_id"=>$SrtSite_id,
                "SrtBusiness_id"=>$SrtBusiness_id,
                "token"=>$token,
								"ref_id"=>$ref_id,
                "created_at"=>$date
              ));
            }
          if(!empty($insert)){
            pageReload($this->lang->alert->success->add,$this->baseurl.'admin/pump/user/');
          }else{
            sAlert($this->lang->alert->error->add);
          }
        }else if($check===TRUE){
          sAlert($this->lang->alert->duplicate);
        }else{
          sAlert($this->lang->alert->fail);
        }
      break;
      case "coupon-edit":
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $driver_name = isset($_POST['driver_name']) && !empty($_POST['driver_name']) ? $_POST['driver_name'] : '';
        $SrtSite_id = isset($_POST['site']) && !empty($_POST['site']) ? $_POST['site'] : '';
        $SrtBusiness_id = isset($_POST['business']) && !empty($_POST['business']) ? $_POST['business'] : '';
        $refuel_amount = isset($_POST['refuel_amount']) && !empty($_POST['refuel_amount']) ? $_POST['refuel_amount'] : '';
        $refuel_gen = isset($_POST['refuel_gen']) && !empty($_POST['refuel_gen']) ? $_POST['refuel_gen'] : '';
        $refuel_truck = isset($_POST['refuel_truck']) && !empty($_POST['refuel_truck']) ? $_POST['refuel_truck'] :'';
        $oil_rate = isset($_POST['oil_rate']) && !empty($_POST['oil_rate']) ? $_POST['oil_rate'] : '';
        $oil_total = isset($_POST['oil_total']) && !empty($_POST['oil_total']) ? $_POST['oil_total'] : '';
        $update = $pump_model->conponEditSave(array("DriverEmployee_id"=>$driver_name,"oil_rate"=>$oil_rate,"refuel_amount"=>$refuel_amount,
        "bill_amount"=>$oil_total,"refuel_gen"=>$refuel_gen,"refuel_truck"=>$refuel_truck,"SrtSite_id"=>$SrtSite_id,"SrtBusiness_id"=>$SrtBusiness_id)," id=".$id);
					if($update===TRUE){
						pageReload($this->lang->alert->success->save);
					}else{
						sAlert($this->lang->alert->error->save);
					}
      break;
      case "create_coupon":
        // echo'<pre>'; print_r($_POST); exit;
        $code = isset($_POST['code_dp']) && !empty($_POST['code_dp']) ? $_POST['code_dp'] : '';
        $amount = isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : '';
        $SrtBusiness_id = isset($_POST['SrtBusiness_id']) && !empty($_POST['SrtBusiness_id']) ? $_POST['SrtBusiness_id'] : '';
        $SrtSite_id = isset($_POST['SrtSite_id']) && !empty($_POST['SrtSite_id']) ? $_POST['SrtSite_id'] : '';
        $driver_id = isset($_POST['driver_id']) && !empty($_POST['driver_id']) ? $_POST['driver_id'] : '';
        $truck_id = isset($_POST['truck_id']) && !empty($_POST['truck_id']) ? explode(",", $_POST['truck_id']) : '';
        $is_refuel = isset($_POST['is_refuel']) && !empty($_POST['is_refuel']) ? $_POST['is_refuel'] : '';
        $work_date = isset($_POST['work_date']) && !empty($_POST['work_date']) ? insertDATE($_POST['work_date']) : '';
        $truckcode =  $truck_id[0];
        $trucklicense =  $truck_id[1];
        $customr = isset($_POST['customr']) && !empty($_POST['customr']) ? explode(",",$_POST['customr']) : '';
        $Customr_id = isset($customr[0]) && !empty($customr[0]) ? $customr[0] : 0 ;
        $Customr_name = isset($customr[1]) && !empty($customr[1]) ? $customr[1] : '';
        $route_km = isset($_POST['route_km']) && !empty($_POST['route_km']) ? $_POST['route_km'] : 0;
        $officer_remark = isset($_POST['officer_remark']) && !empty($_POST['officer_remark']) ? $_POST['officer_remark'] : '';
        $is_borrow = isset($_POST['is_borrow']) && !empty($_POST['is_borrow']) ? $_POST['is_borrow'] : 0;
        $coupon_type = isset($_POST['coupon_type']) && !empty($_POST['coupon_type']) ? $_POST['coupon_type'] : 0;
        $delivery_date = isset($_POST['delivery_date']) && !empty($_POST['delivery_date']) ? insertDATE($_POST['delivery_date']) : '';
        $SiteNode_from = isset($_POST['SiteNode_from']) && !empty($_POST['SiteNode_from']) ? $_POST['SiteNode_from'] : '';
        $SiteNode_to = isset($_POST['SiteNode_to']) && !empty($_POST['SiteNode_to']) ? $_POST['SiteNode_to'] : '';
        if($delivery_date == ''){
          $delivery_date=$work_date;
        }
        $is_invoice = isset($_POST['is_invoice']) && !empty($_POST['is_invoice']) ? $_POST['is_invoice'] : 0;
        $time_in = isset($_POST['time_in']) && !empty($_POST['time_in']) ? $_POST['time_in'] : '';
        $time_out = isset($_POST['time_out']) && !empty($_POST['time_out']) ? $_POST['time_out'] : '';
        $worklate_hrs = isset($_POST['worklate_hrs']) && !empty($_POST['worklate_hrs']) ? $_POST['worklate_hrs'] : '';
        $ref_dp = isset($_POST['ref_dp']) && !empty($_POST['ref_dp']) ? $_POST['ref_dp'] : '';
        if($worklate_hrs=='undefined'){
          $worklate_hrs=0;
        }
        $Company_id = isset($_POST['Company_id']) && !empty($_POST['Company_id']) ? $_POST['Company_id'] : '';
        $check = $pump_model->CheckCouponDuplicate($code);
        if(empty($check)){
          $refer_code = getCouponReferCode(12);
          $qr_code = password_hash($refer_code,PASSWORD_DEFAULT);
	          $insert = $pump_model->getSave("tbl_Coupon",array(
	            "code"=>$code,
	            "work_date"=>$work_date,
              "delivery_date"=>$delivery_date,
	            "truck_code"=>$truckcode,
	            "truck_license"=>$trucklicense,
							"oil_truck"=>$amount,
	            "amount"=>$amount,
	            "qr_code"=>$qr_code,
	            "is_refuel"=>$is_refuel,
	            "DriverEmployee_id"=>$driver_id,
	            "SrtSite_id"=>$SrtSite_id,
	            "SrtBusiness_id"=>$SrtBusiness_id,
	            "created_at"=>date("Y-m-d H:i:s"),
	            "status"=>1,
	            "qr_code"=>$qr_code,
	            "refer_code"=>$refer_code,
	            "Dispatch_code"=>$code,
              "Customer_id"=>$Customr_id,
              "Customer_name"=>$Customr_name,
              "SiteNode_from"=>$SiteNode_from,
              "SiteNode_to"=>$SiteNode_to,
	            "route_km"=>$route_km,
              "is_officer"=>1,
              "officer_by"=>1,
              "officer_name"=>'IT',
              "officer_remark"=>$officer_remark,
              "coupon_type"=>$coupon_type,
              "is_borrow"=>$is_borrow,
              "is_invoice"=>$is_invoice,
              "time_in"=>$time_in,
              "time_out"=>$time_out,
              "worklate_hrs"=>$worklate_hrs,
              "ref_dp"=>$ref_dp,
              "Company_id"=>$Company_id
	            ));
            if(!empty($insert)){
              pageReload($this->lang->alert->success->add,$this->baseurl.'admin/pump/coupon/');
            }else{
              sAlert($this->lang->alert->error->add);
            }
        }else{
          sAlert($this->lang->alert->duplicate);
        }
      break;
      case 'paymant_edit':
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $bill_amount = isset($_POST['bill_amount']) && !empty($_POST['bill_amount']) ? $_POST['bill_amount'] : '';
        $refuel_amount = isset($_POST['refuel_amount']) && !empty($_POST['refuel_amount']) ? $_POST['refuel_amount'] : '';
        $card_no_items = isset($_POST['card_no_edit']) && !empty($_POST['card_no_edit']) ? explode(',',$_POST['card_no_edit']) : '';
        $credit_card_id = isset($card_no_items[0]) && !empty($card_no_items[0]) ? $card_no_items[0] : '';
        $card_no = isset($card_no_items[1]) && !empty($card_no_items[1]) ? $card_no_items[1] : '';
        //echo'<pre>'; print_r($_POST); exit;
        $check_conponpayment = $pump_model -> CheckCouponPaymentID($id);
        if(!empty($check_conponpayment)){
            $update = $pump_model->conponPaymentEditSave(array(
            "bill_amount"=>$bill_amount,
            "oil_amount"=>$refuel_amount,
            "credit_card_id"=>$credit_card_id,
            "card_no"=>$card_no,
            ),"id=".$id);
            if($update===TRUE){
              pageReload($this->lang->alert->success->save);
            }else{
              sAlert($this->lang->alert->error->save);
            }
         }else{
          sAlert($this->lang->alert->error->save);
         }
      break;

    endswitch;
  }
  function search(){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
      switch ($p):
        case 'edit':
          $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
          if (!empty($id)) {
            $check_business=$pump_model->CheckSrtBusinessID($id);
            $SrtBusiness_id = isset($check_business['SrtBusiness_id']) && !empty($check_business['SrtBusiness_id']) ? $check_business['SrtBusiness_id'] : '';
            if($SrtBusiness_id ==6){ //น้ำมัน
              $data['customer']=$pump_model->OilCustomerOptionList();
            }else if($SrtBusiness_id ==2){ //คอนกรีต(CPAC)
              $data['customer']=$pump_model->CPACCustomerOptionList();
            }else if($SrtBusiness_id ==4){ //เคมี
              $data['customer']=$pump_model->ChemicalCustomerOptionList();
            }else if($SrtBusiness_id ==1){ //หัวลาก(เทรเลอร์)
              $data['customer']=$pump_model->SilCustomerOptionList();
            }
            $data['coupon_details']=$pump_model->CouponDetails($id);
            $data['driver']=$pump_model->getDriver();
            $data['business']=$pump_model->SrtBusinessOptionList();
            $data['site']=$pump_model->SrtSiteOptionList();
            $template = $this->loadView('admin/pump/pump_search_coupon_edit');
            $template->set('data',$data);
            $template->render();
          }
        break;
        default:
          $code = isset($_GET['code']) && !empty($_GET['code']) ? $_GET['code'] : '';
          $data['filter']['code']=$code;
          $data['coupon']=$pump_model->GetCoupon($data['filter']);
          $template = $this->loadView('admin/pump/pump_search_coupon');
          $template->set('data',$data);
          $template->render();
      endswitch;
  }
  function search_dp_menu(){
    $template = $this->loadView('admin/pump/pump_search_dp_menu');
    $template->render();
  }
  function search_dp_chemical(){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch ($p):
        case 'edit':
          $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
          if (!empty($id)) {
            $data['dispatch'] = $pump_model->GetDispatchCemical($id);
            $data['dispatch_oil_coupon'] = $pump_model->GetDispatchOilCoupon($id);
            $dataCode=$pump_model->GetCodeDispatch($id);
            $Dispatch_code = isset($dataCode['code']) && !empty($dataCode['code']) ? $dataCode['code'] : '';
            $data['coupon']=$pump_model->GetCouponApp($Dispatch_code);
            $template = $this->loadView('admin/pump/pump_search_dp_chemical_edit');
            $template->set('data',$data);
            $template->render();
          }
        break;
      default:
      $code = isset($_GET['code']) && !empty($_GET['code']) ? $_GET['code'] : '';
      $data['filter']['code']=$code;
      $data['dispatch'] = $pump_model->DispatchCemical($data['filter']);
      $template = $this->loadView('admin/pump/pump_search_dp_chemical');
      $template->set('data',$data);
      $template->render();
    endswitch;
  }
  function search_dp_oil(){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch ($p):
        case 'edit':
          $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
          if (!empty($id)) {
            $data['dispatch'] = $pump_model->GetDispatchOil($id);
            $data['dispatch_oil_coupon'] = $pump_model->GetOilDispatchOilCoupon($id);
            $dataCode=$pump_model->GetCodeOilDispatch($id);
            $Dispatch_code = isset($dataCode['code']) && !empty($dataCode['code']) ? $dataCode['code'] : '';
            $data['coupon']=$pump_model->GetCouponApp($Dispatch_code);
            $template = $this->loadView('admin/pump/pump_search_dp_oil_edit');
            $template->set('data',$data);
            $template->render();
          }
        break;
      default:
      $code = isset($_GET['code']) && !empty($_GET['code']) ? $_GET['code'] : '';
      $data['filter']['code']=$code;
      $data['dispatch'] = $pump_model->DispatchOil($data['filter']);
      $template = $this->loadView('admin/pump/pump_search_dp_oil');
      $template->set('data',$data);
      $template->render();
    endswitch;
  }
  function search_dp_sil(){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch ($p):
        case 'edit':
          $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
          if (!empty($id)) {
            $data['dispatch'] = $pump_model->GetDispatchSil($id);
            $data['dispatch_oil_coupon'] = $pump_model->GetSilDispatchOilCoupon($id);
            $dataCode=$pump_model->GetCodeSilDispatch($id);
            $Dispatch_code = isset($dataCode['code']) && !empty($dataCode['code']) ? $dataCode['code'] : '';
            $data['coupon']=$pump_model->GetCouponApp($Dispatch_code);
            $template = $this->loadView('admin/pump/pump_search_dp_sil_edit');
            $template->set('data',$data);
            $template->render();
          }
        break;
      default:
      $code = isset($_GET['code']) && !empty($_GET['code']) ? $_GET['code'] : '';
      $data['filter']['code']=$code;
      $data['dispatch'] = $pump_model->DispatchSil($data['filter']);
      $template = $this->loadView('admin/pump/pump_search_dp_sil');
      $template->set('data',$data);
      $template->render();
    endswitch;
  }
  function paymant (){
    $pump_model = $this->loadModel('admin/pump_model');
    $p = isset($_GET['p']) && !empty($_GET['p']) ? $_GET['p'] : '';
    switch($p):
      case'paymant-edit':
        $id = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : '';
        $data['payment_coupon']=$pump_model->CouponPaymentHistoryList($id);
        $SrtSite_id = isset($data['payment_coupon']['SrtSite_id']) && !empty($data['payment_coupon']['SrtSite_id']) ? $data['payment_coupon']['SrtSite_id'] : '';
        $SrtBusiness_id = isset($data['payment_coupon']['SrtBusiness_id']) && !empty($data['payment_coupon']['SrtBusiness_id']) ? $data['payment_coupon']['SrtBusiness_id'] : '';
        $data['payment_history']=$pump_model->CouponPaymentHistory($id);
        $select_api_card_on = getAPI2($this->api->credit_card.'&site='.$SrtSite_id.'&business='.$SrtBusiness_id,$this->user->token);
        $data['card_no']= isset($select_api_card_on['results']) && !empty($select_api_card_on['results']) ? $select_api_card_on['results'] : '';
        $template = $this->loadView('admin/pump/pump_paymat_edit');
        $template->set('data',$data);
        $template->render();
      break;
      default:
      $business= isset($_GET['business']) && !empty($_GET['business']) ? $_GET['business'] : '';
      $site = isset($_GET['site']) && !empty($_GET['site']) ? $_GET['site'] : '';
      $credit = isset($_GET['credit']) && !empty($_GET['credit']) ? $_GET['credit'] : '';
      $data['filter']['business']=$business;
      $data['filter']['site']=$site;
      $data['filter']['credit']=$credit;
      $data['site']=$pump_model->GroupSiteOptionList();
      $data['business']=$pump_model->GroupBusinessOptionList();
      $data['credit']=$pump_model->GroupCreditOptionList($data['filter']);
      $data['coupon_payment']=$pump_model->CouponPaymentOptionList($data['filter']);
      $data['payment']=$pump_model->CouponPaymentList($data['filter']);
      $template = $this->loadView('admin/pump/pump_paymat_list');
      $template->set('data',$data);
      $template->render();
    endswitch;
  }

}

?>
