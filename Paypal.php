<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Codeigniter Paypal Mutation Library
 *
 * Library ini saya buat untuk mempermudah pemanggilan API pada EndPoints PayPal NVP/SOAP 
 *
 * @author gusmanu
 * @github github.com/gusmanu
 * @version 1.0
 */
 
 
class Paypal{
    
    const url = "https://api-3t.paypal.com/nvp";
    
    private $user;
    private $pwd;
    private $signature;
    
    function __construct($params){
        if(empty($params['user']) || empty($params['pwd']) || empty($params['signature'])){
            echo "silahkan lengkapi data yang kosong";
		    die;
			exit;
        } else {
            $this->user = $params['user'];
            $this->pwd = $params['pwd'];
            $this->signature = $params['signature'];
        }
    }
    
    
    private function curl($postdata){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        parse_str($result, $params);
        return $params;
    }
    
    private function cek_mutasi($stamp_start_date){
        
        $start_date = date('Y-m-d', $stamp_start_date) . 'T00:00:00Z';
        
        $postdata = implode('&', array(
            'USER=' . urlencode($this->user),
            'PWD=' . urlencode($this->pwd),
            'SIGNATURE=' . urlencode($this->signature),
            'METHOD=TransactionSearch',
            'TRANSACTIONCLASS=Received',
            'STATUS=Success',
            'CURRENCYCODE=USD',
            'STARTDATE=' . urlencode($start_date),
            'VERSION=94',
        ));
        
        return $this->curl($postdata);
        
    }
    
    private function cek_detail_mutasi($L_TRANSACTIONID){
        
        $postdata = implode('&', array(
            'USER=' . urlencode($this->user),
            'PWD=' . urlencode($this->pwd),
            'SIGNATURE=' . urlencode($this->signature),
            'METHOD=GetTransactionDetails',
            'TRANSACTIONID=' . $L_TRANSACTIONID,
            'VERSION=94',
            ));
            
        $detail = $this->curl($postdata);
        $detail['L_TRANSACTIONID'] = $L_TRANSACTIONID;
        return $detail;
    }
    
    public function mutasi($stamp_start_date){
        
        $mutasi = $this->cek_mutasi($stamp_start_date);
        if($mutasi['ACK'] == 'Success'){
            $total = (count($mutasi) - 5) / 11;
            if($total > 0){
                $detail = [];
                for($i = 0; $i < $total; $i++){
                    $L_TRANSACTIONID = $mutasi["L_TRANSACTIONID" . $i];
                    $detail[$L_TRANSACTIONID] = $this->cek_detail_mutasi($L_TRANSACTIONID);
                }
                return array('status' => 'Sukses', 'jumlah' => $total, 'detail' => $detail);
            } else {
                return array('status' => 'Sukses', 'jumlah' => 0, 'detail' => null);
            
            }
        } else {
            return array('status' => 'Gagal', 'detail' => $mutasi);
        }
    }
    
    
    
    public function refund($L_TRANSACTIONID, $PAYERID ,$catatan){
        
        $postdata = implode('&', array(
            'USER=' . urlencode($this->user),
            'PWD=' . urlencode($this->pwd),
            'SIGNATURE=' . urlencode($this->signature),
            'METHOD=RefundTransaction',
            'TRANSACTIONID=' . $L_TRANSACTIONID,
            'PAYERID=' . $PAYERID,
            'CURRENCYCODE=USD',
            'REFUNDTYPE=Full',
            'NOTE=' . urlencode($catatan),
            'VERSION=94',
        ));
        
        return $this->curl($postdata);
        
    }
    
    
}


/* end of file Paypal.php */
/* location ./application/libraries/Paypal.php */
