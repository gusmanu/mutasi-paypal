# mutasi-paypal
Library CodeIgniter / Native untuk mengambil data mutasi paypal dan proses refund (PayPal Akun Bisnis)

## Tutorial Mendapatkan ID dan Signature
- Silahkan daftar akun paypal bisnis terlebih dahulu [Daftar PayPal Bisnis Gratis!](https://ww.paypal.com)

- Buka [Dashboard!](https://www.paypal.com/mep/dashboard) anda, silahkan pilih menu Alat -> Semua Alat (Tools -> All Tools), atau Klik [Disini!](https://www.paypal.com/merchantapps/myapps);

- Pada kolom pencarian silahkan cari "API Credential", lalu klik "Buka"  <br />
![Screenshot](ss1.PNG)
- Scroll ke bawah sampai anda menemukan "Integrasi NVP/SOAP API (Klasik)", lalu klik "Kelola kredensial API" <br />
![Screenshot](ss2.PNG)
- Pilih opsi "Minta Tanda Tangan API" dan klik "Setuju dan Lanjutkan" <br />
![Screenshot](ss3.PNG)
- Silahkan copy Nama Pengguna API (user) , Sandi API (pwd) , dan Tanda Tangan (signature) <br />
![Screenshot](ss4.PNG)



## Tutorial Penggunaan Library

### CodeIgniter
  Silahkan Copy Paypal.php ke directory library
  Pemanggilan library :
  $this->load->library('paypal', ['user' => USER, 'pwd' => PASSWORD, 'signature' => SIGNATURE]);
  silahkan ganti menggunakan data anda
  
  #### Cek Mutasi :
  Gunakan strtotime untuk menentukan rentang waktu dari sekarang <br />
  $start_date = strtotime("now"); //cek mutasi hari ini <br />
  $start_date = strtotime("-7days"); //cek mutasi seminggu yg lalu sd sekarang <br />
  
  $cek = $this->paypal->mutasi($start_date); <br />
  print_r($cek); <br />
  
  #### Refund Pembayaran :
  $refund = $this->paypal->refund($L_TRANSACTIONID, $PAYERID, $catatan); <br />
  L_TRANSACTIONID dan PAYERID bisa anda dapatkan di fungsi mutasi, tambahkan catatan / note jika diperlukan <br />
  
 ### PHP Native
 
  require("Paypal.php"); <br />
  
  $data = array('user' => USER, 'pwd' => PASSWORD, 'signature' => SIGNATURE); <br />
  silahkan ganti menggunakan data anda <br />
  $paypal = new Paypal($data); <br />
  
  $start_date = strtotime("now"); //cek mutasi hari ini <br />
  $start_date = strtotime("-7days"); //cek mutasi seminggu yg lalu sd sekarang <br />
  
  $cek = $paypal->mutasi($start_date); <br />
  print_r($cek); <br />
  
  Refund Pembayaran : <br />
  $refund = $paypal->refund($L_TRANSACTIONID, $PAYERID, $catatan);
  
  



