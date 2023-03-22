<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipay88backendpost extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/Travellerapimodel');
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->helper('timezone');
        //header("content-type:application/json");
    }
    public function index() {
        $merchantcode   = $_REQUEST["MerchantCode"];
        $paymentid      = $_REQUEST["PaymentId"];
        $refno          = $_REQUEST["RefNo"];
        $amount         = $_REQUEST["Amount"];
        $ecurrency      = $_REQUEST["Currency"];
        $remark         = $_REQUEST["Remark"];
        $transid        = $_REQUEST["TransId"];
        $authcode       = $_REQUEST["AuthCode"];
        $estatus        = $_REQUEST["Status"];
        $errdesc        = $_REQUEST["ErrDesc"];
        $signature      = $_REQUEST["Signature"];
        $ccname         = $_REQUEST["CCName"];
        $ccno           = $_REQUEST["CCNo"];
        $s_bankname     = $_REQUEST["S_bankname"];
        $s_country      = $_REQUEST["S_country"];
        $createdon      = date("Y-m-d H:i:s");
        $today          = date("Y-m-d");

        if($estatus == 1) {
            $statustxt = 'RECEIVEOK';
            //COMPARE Return Signature with Generated Response Signature
            $getIPay88Info    = $this->Commonapimodel->getNotCompletedIPay88InfoByTRI( $refno );
            if($getIPay88Info){
                $serviceID    = $getIPay88Info->serviceID;
                $serviceInfo  = $this->Serviceapimodel->serviceInfo( $serviceID );
                $guiderID     = $serviceInfo->service_guider_id;
                $travellerID  = $serviceInfo->service_traveller_id;
                $service_date = $serviceInfo->service_date;
                $data22     = array(
                                    "iPay88TaransactionID"  => $transid,
                                    "iPay88refNo"           => $refno,
                                    "iPay88AuthCode"        => $authcode,
                                    "iPay88Remarks"         => $remark,
                                    "iPay88PaidAmount"      => $amount,
                                    "iPay88ErrDescription"  => $errdesc,
                                    "transactionPaymentId"  => $paymentid,
                                    "transactionBankName"   => $s_bankname,
                                    "Status"                => $estatus,
                                    "cronUpdated"           => 2,
                                    "iPayUpdated"           => $createdon
                                    );
                /******UPDATE IPAY88 SUCCESS********/
                $update     = $this->Commonapimodel->updatePayment($data22, $refno);
                /******UPDATE IPAY88 FORCED CANCELLED********/
                $this->Commonapimodel->updatePaymentCancel($serviceID);
                /******UPDATE SERVICE PAYMENT COMPLETED********/
                $data3  = array('status' => 4,'transactionID' => $transid);
                $this->Serviceapimodel->updateServiceRequest($data3, $serviceID);
                //INSERT/UPDATE JOURNEY
                if($today == $service_date){
                    $jny_status = 2; //ONGOING
                }else{
                    $jny_status = 1; //UPCOMING
                }
                /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                if ( $this->Serviceapimodel->journeyInfo( $serviceID ) ){
                    $data11   = array('jny_status' => $jny_status);
                    $this->Serviceapimodel->updateJourney($data11, $serviceID);
                }else{
                    $data2  = array(
                                'jny_traveller_id'  => $travellerID,
                                'jny_guider_id'     => $guiderID,
                                'jny_service_id'    => $serviceID,
                                'createdon'         => $createdon,
                                'payment_status'    => 'paid',
                                'jny_transactionID' => $transid,
                                'jny_status'        => $jny_status
                                );
                    $this->Serviceapimodel->insertJourney($data2);
                }
            }
            //update order to PAID
            echo "RECEIVEOK";
        }else{
            $getIPay88Info    = $this->Commonapimodel->getNotCompletedIPay88InfoByTRI( $refno );
            if($getIPay88Info){
                $serviceID    = $getIPay88Info->serviceID;
                //$Amount     = $getIPay88Info->transactionAmount;
                //$transid      = $getIPay88Info->iPay88TaransactionID;
                $serviceInfo  = $this->Serviceapimodel->serviceInfo( $serviceID );
                $guiderID     = $serviceInfo->service_guider_id;
                $travellerID  = $serviceInfo->service_traveller_id;
                $service_date = $serviceInfo->service_date;
                $requestRes = $this->Requery($refno,$amount); //$requestRes == '00'
                if($requestRes == '00'){
                    $data22     = array(
                                        "iPay88TaransactionID"  => $transid,
                                        "iPay88refNo"           => $refno,
                                        "iPay88AuthCode"        => $authcode,
                                        "iPay88Remarks"         => $remark,
                                        "iPay88PaidAmount"      => $amount,
                                        "iPay88ErrDescription"  => $errdesc,
                                        "transactionPaymentId"  => $paymentid,
                                        "transactionBankName"   => $s_bankname,
                                        "Status"                => $estatus,
                                        "cronUpdated"           => 2,
                                        "iPayUpdated"           => $createdon
                                        );
                    /******UPDATE IPAY88 SUCCESS********/
                    $update     = $this->Commonapimodel->updatePayment($data22, $refno);
                    /******UPDATE IPAY88 FORCED CANCELLED********/
                    $this->Commonapimodel->updatePaymentCancel($serviceID);
                    /******UPDATE SERVICE PAYMENT COMPLETED********/
                    $data3  = array('status' => 4,'transactionID' => $transid);
                    $this->Serviceapimodel->updateServiceRequest($data3, $serviceID);
                    //INSERT/UPDATE JOURNEY
                    if($today == $service_date){
                        $jny_status = 2; //ONGOING
                    }else{
                        $jny_status = 1; //UPCOMING
                    }
                    /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                    if ( $this->Serviceapimodel->journeyInfo( $serviceID ) ){
                        $data11   = array('jny_status' => $jny_status);
                        $this->Serviceapimodel->updateJourney($data11, $serviceID);
                    }else{
                        $data2  = array(
                                    'jny_traveller_id'  => $travellerID,
                                    'jny_guider_id'     => $guiderID,
                                    'jny_service_id'    => $serviceID,
                                    'createdon'         => $createdon,
                                    'payment_status'    => 'paid',
                                    'jny_transactionID' => $transid,
                                    'jny_status'        => $jny_status
                                    );
                        $this->Serviceapimodel->insertJourney($data2);
                    }
                }elseif ($requestRes == 'Invalid parameters' || $requestRes == 'Record not found' || $requestRes == 'Incorrect amount' || $requestRes == 'Payment fail' || $requestRes == 'M88Admin') {
                    $data2 = array(
                                    "requeryRes"    => $requestRes,
                                    "Status"        => 3,
                                    "cronUpdated"   => 2
                                );
                    $update2 = $this->Commonapimodel->updatePayment($data2, $refno);
                    /******UPDATE SERVICE PAYMENT CANCELLED********/
                    $data6  = array('status' => 2);
                    $this->Serviceapimodel->updateServiceRequest($data6, $serviceID);
                }else{
                    //IPAY88 PENDING
                    $data3 = array(
                                    "iPay88TaransactionID"  => $transid,
                                    "iPay88refNo"           => $refno,
                                    "iPay88AuthCode"        => $authcode,
                                    "iPay88Remarks"         => $remark,
                                    "iPay88PaidAmount"      => $amount,
                                    "iPay88ErrDescription"  => $errdesc,
                                    "transactionPaymentId"  => $paymentid,
                                    "transactionBankName"   => $s_bankname,
                                    "requeryRes"            => $requestRes,
                                    "Status"                => 4
                                );
                    $update3 = $this->Commonapimodel->updatePayment($data3, $refno);
                    /******UPDATE SERVICE PAYMENT PROCESSING********/
                    $data5  = array('status' => 5);
                    $this->Serviceapimodel->updateServiceRequest($data5, $serviceID);
                }
            }
            //update order to FAIL
            $statustxt = 'FAIL';
        }
        $dataString  = date("Y-m-d H:i:s").'-'.$statustxt.'|merchantcode:'.$merchantcode.'|paymentid:'.$paymentid;
        $dataString .= '|refno:'.$refno.'|amount:'.$amount.'|ecurrency:'.$ecurrency.'|remark:'.$remark;
        $dataString .= '|transid:'.$transid.'|authcode:'.$authcode.'|estatus:'.$estatus.'|errdesc:'.$errdesc;
        $dataString .= '|signature:'.$signature.'|ccname:'.$ccname.'|ccno:'.$ccno.'|s_bankname:'.$s_bankname.'|s_country:'.$s_country;
        $dataString .= "\n";
        $fWrite      = fopen(FCPATH . '/uploads/ipay88.txt','a');
        $wrote       = fwrite($fWrite, $dataString);
        fclose($fWrite);
    }
    public function Requery($RefNo,$Amount){
        $MerchantCode   = 'M10845';
        //$RefNo          = 'TXN1920180527234506048111';
        //$Amount         = '1';
        $query      = "https://www.mobile88.com/epayment/enquiry.asp?MerchantCode=" . $MerchantCode . "&RefNo=" . str_replace(" ","%20",$RefNo) . "&Amount=" . $Amount;
        $url        = parse_url($query);
        $host       = $url["host"];
        $sslhost    = "ssl://".$host;
        $path       = $url["path"] . "?" . $url["query"];
        $timeout    = 1;
        $fp         = fsockopen ($sslhost, 443, $errno, $errstr, $timeout);
        $buf = '';
        if ($fp) {
            fputs ($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
            while (!feof($fp)) {
                $buf .= fgets($fp, 128);
            }
            $lines  = preg_split("/\n/", $buf);
            $Result = $lines[count($lines)-1];
            fclose($fp);
        } else {
            # enter error handing code here
        }
        return $Result;
    }
}
?>