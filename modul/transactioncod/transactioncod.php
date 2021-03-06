<?php
 if (empty($_SESSION['pelanggan_username']) AND empty($_SESSION['pelanggan_password'])){
    echo "<script>location='media.php?module=home';</script>";
} else { 
    $action="modul/transactioncod/action.php?module=".$_GET['module']."&act=".$_GET['act'];
    switch($_GET['act']) {
        default:
        ?>

    <div class="page-content container-fluid">
        <div class="row">
        <div class="col-md-3">
            <div class="panel">
                <div class="panel-heading">
                    <h5 class="panel-title">Histori</h5>
                </div>
                <ul class="list">
                    <li class="list-item">
                        <a href="?module=transactionprocess" class="animsition-link">
                           Pemesanan Toko - Proses
                        </a>
                    </li>
                    <li class="list-item">
                        <a href="?module=transactiondone" class="animsition-link">
                            Pemesanan Toko - Selesai
                        </a>
                    </li>
                    <li class="list-item">
                        <a href="?module=transactioncancel" class="animsition-link">
                            Pemesanan Toko - Cancel
                        </a>
                    </li>
                    <li class="list-item">
                        <a href="?module=transactioncod" class="animsition-link">
                            Pemesanan Toko - COD
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Pemesanan - COD</h5>
                    </div>
                    <div class="panel-body container-fluid table-detail">
                        <div class="table-full table-view">
                            <div class="navigation-btn">
                                <form method="get" action='<?php echo $_SERVER[' PHP_SELF '] ?>'>
                                    <div class='row margin-bottom'>
                                        <div class='btn-search'>Cari Berdasarkan ID: &nbsp; &nbsp; &nbsp;</div>
                                        <div class='col-md-4 col-sm-12 select-search'>
                                            <div class="input-group">
                                                <input type="text" name="filter" class="form-control" value="" />
                                                <input type=hidden name=module value=transactioncod>
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary" type="button">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='btn-navigation'>
                                        <div class='pull-right'>
                                            <a class="btn btn-primary" href="?module=transactioncod">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <th>Order ID</th>
                                        <th class="text-center">COD</th>
                                        <th>Pemesan</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Total</th>
                                        <th>Dipesan</th>
                                        <th class="text-center">Status</th>
                                        <th class="action text-center">Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty(isset($_GET['filter']))) {
                                            $p      = new Paging;
                                            $batas  = 10;
                                            $posisi = $p->cariPosisi($batas);

                                            $result=mysqli_query($connect,"SELECT * FROM invoice WHERE invoice_status='process' AND invoice_statustoko='processcod' OR invoice_statustoko='processcodtoko' LIMIT $posisi,$batas");
                                            $jmldata = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM invoice WHERE invoice_status='process' AND invoice_statustoko='processcod' OR invoice_statustoko='processcodtoko'"));
                                            if(mysqli_num_rows($result) === 0) {
                                    ?>
                                            <tr>
                                                <td class="text-center" colspan="10">Data kosong...</td>
                                            </tr>
                                            <?php } else {
                                                $no = $posisi+1;
                                                $no2 = 1;
                                                while ($r=mysqli_fetch_array($result)) {
                                                    $tanggal=tgl_indo($r['invoice_created']);
                                                    $invoice_price=format_rupiah($r['invoice_total']);
                                                    $multiple_names = $r['toko_id'];  
                                                    $multiple_names_array = explode(',',$multiple_names);
                                                    
                                                    foreach($multiple_names_array as $resulttoko)
                                                    {
                                                      $tokos = mysqli_query($connect, "SELECT * FROM toko WHERE toko_id='$resulttoko'");
                                                      $toko      = mysqli_fetch_array($tokos);
                                                   if ($toko['toko_id'] == $_SESSION['toko_id']) {
                                                ?>
                                            <tr>
                                                <td>
                                                    <?php echo $r['invoice_id'];?>
                                                </td>
                                          
                                                <td>
                                                    Tanggal: <?php echo tgl_indo($r['invoice_date']);?><br>
                                                    Jam <?php echo $r['invoice_clock'];?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $result2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE pelanggan_username='$r[pelanggan_username]'");
                                                    $pelanggan      = mysqli_fetch_array($result2);
                                                    ?> Username :
                                                    <?php echo $pelanggan['pelanggan_username']; ?>
                                                    <br> Nama :
                                                    <?php echo $pelanggan['pelanggan_name']; ?>
                                                    <br> Alamat :
                                                    <?php echo $pelanggan['pelanggan_alamat']; ?>
                                                    <br> No Telp :
                                                    <?php echo $pelanggan['pelanggan_notelp']; ?>
                                                    <br>
                                                    <br> Alamat Pengiriman :
                                                    <?php echo $r['invoice_address']; ?>
                                                </td>
                                                <td>
                                                    Jenis : <?php echo $r['invoice_payment']; ?> <br>
                                                </td>
                                                <td>
                                                    Total Item:
                                                    <?php echo $r['invoice_item'];?>
                                                    <br> Total Toko:
                                                    <?php echo $r['invoice_totaltoko'];?>
                                                    <br> Total Berat:
                                                    <?php echo $r['invoice_weight'];?>
                                                    <br> Total Harga:
                                                    <?php echo format_rupiah($r['invoice_total']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $tanggal?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($r['invoice_statustoko'] == 'processcod') { ?>
                                                        Menunggu Konfirmasi Toko
                                                    <?php } ?>
                                                    <?php if ($r['invoice_statustoko'] == 'processcodtoko') { ?>
                                                        Anda Setuju COD
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center action">
                                                    <a class="btn-update" href="?module=transactioncod&act=cancel&id=<?php echo $r['invoice_id'];?>">
                                                        <i class="icon wb-close"></i>
                                                    </a>
                                                    <?php if ($r['invoice_statustoko'] == 'processcod') { ?>
                                                    <a class="btn-update" href="javascript:;" data-id="<?php echo $r['invoice_id'];?>" data-toggle="modal" data-target="#modal-konfirmasi"
                                                        title="">
                                                        <i class="icon wb-check"></i>
                                                    </a>
                                                    <?php } ?>
                                                    <a class="btn-detail" href="?module=transactioncod&act=detail&id=<?php echo $r['invoice_id'];?>">
                                                        <i class="icon wb-search"></i>
                                                    </a>
                                                    
                                                    <?php if ($r['invoice_statustoko'] == 'processcodtoko') { ?>
                                                    <div>
                                                    <a class="btn-primary btn" href="javascript:;" data-id="<?php echo $r['invoice_id'];?>" data-toggle="modal" data-target="#modal-konfirmasi2"
                                                        title="">
                                                        Selesai
                                                    </a>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                                <?php $no++; }}} ?>
                                            </tr>
                                            <?php }
                                                $jmldata = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM invoice WHERE invoice_status='process' AND invoice_statustoko='processcod' OR invoice_statustoko='processcodtoko'"));
                                                $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
                                                $linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman);
                                            } else {
                                            $p      = new Paging;
                                            $batas  = 10;
                                            $posisi = $p->cariPosisi($batas);
                                            $search = $_GET['filter'];

                                            $result=mysqli_query($connect,"SELECT * FROM invoice WHERE invoice_id LIKE '%$search%' AND invoice_status='process' AND invoice_statustoko='processcod' OR invoice_statustoko='processcodtoko' LIMIT $posisi,$batas");
                                            $jmldata = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM invoice WHERE invoice_id LIKE '%$search%' AND invoice_status='process' AND invoice_statustoko='processcod' OR invoice_statustoko='processcodtoko'"));
                                            if(mysqli_num_rows($result) === 0) {
                                    ?>
                                            <tr>
                                                <td class="text-center" colspan="10">Pencarian tidak ditemukan...</td>
                                            </tr>
                                            <?php } else {
                                                $no = $posisi+1;
                                                while ($r=mysqli_fetch_array($result)) {
                                                    $tanggal=tgl_indo($r['invoice_created']);
                                                    $invoice_price=format_rupiah($r['invoice_total']);
                                                    $multiple_names = $r['toko_id'];  
                                                    $multiple_names_array = explode(',',$multiple_names);
                                                    
                                                    foreach($multiple_names_array as $resulttoko)
                                                    {
                                                      $tokos = mysqli_query($connect, "SELECT * FROM toko WHERE toko_id='$resulttoko'");
                                                      $toko      = mysqli_fetch_array($tokos);
                                                   if ($toko['toko_id'] == $_SESSION['toko_id']) {
                                                ?>
                                            <tr>
                                                <td>
                                                    <?php echo $r['invoice_id'];?>
                                                </td>
                                        
                                                <td>
                                                Tanggal: <?php echo tgl_indo($r['invoice_date']);?><br>
                                                Jam <?php echo $r['invoice_clock'];?>
                                            </td>
                                                <td>
                                                    <?php 
                                                $result2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE pelanggan_username='$r[pelanggan_username]'");
                                                $pelanggan      = mysqli_fetch_array($result2);
                                                    ?> Username :
                                                    <?php echo $pelanggan['pelanggan_username']; ?>
                                                    <br> Nama :
                                                    <?php echo $pelanggan['pelanggan_name']; ?>
                                                    <br> Alamat :
                                                    <?php echo $pelanggan['pelanggan_alamat']; ?>
                                                    <br> No Telp :
                                                    <?php echo $pelanggan['pelanggan_notelp']; ?>
                                                    <br>
                                                    <br> Alamat Pengiriman :
                                                    <?php echo $r['invoice_address']; ?>
                                                </td>
                                                <td>
                                                    Jenis : <?php echo $r['invoice_payment']; ?> <br>
                                                </td>
                                                <td>
                                                    Total Item:
                                                    <?php echo $r['invoice_item'];?>
                                                    <br> Total Toko:
                                                    <?php echo $r['invoice_totaltoko'];?>
                                                    <br> Total Berat:
                                                    <?php echo $r['invoice_weight'];?>
                                                    <br> Total Harga:
                                                    <?php echo format_rupiah($r['invoice_total']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $tanggal?>
                                                </td>
                                                <td class="text-center">
                                                <?php if ($r['invoice_statustoko'] == 'processcod') { ?>
                                                    Menunggu Konfirmasi Toko
                                                <?php } ?>
                                                <?php if ($r['invoice_statustoko'] == 'processcodtoko') { ?>
                                                    Anda Setuju COD
                                                <?php } ?>
                                            </td>
                                            <td class="text-center action">
                                                <a class="btn-update" href="?module=transactioncod&act=cancel&id=<?php echo $r['invoice_id'];?>">
                                                    <i class="icon wb-close"></i>
                                                </a>
                                                <?php if ($r['invoice_statustoko'] == 'processcod') { ?>
                                                <a class="btn-update" href="javascript:;" data-id="<?php echo $r['invoice_id'];?>" data-toggle="modal" data-target="#modal-konfirmasi"
                                                    title="">
                                                    <i class="icon wb-check"></i>
                                                </a>
                                                <?php } ?>
                                                <a class="btn-detail" href="?module=transactioncod&act=detail&id=<?php echo $r['invoice_id'];?>">
                                                    <i class="icon wb-search"></i>
                                                </a>
                                                
                                                <?php if ($r['invoice_statustoko'] == 'processcodtoko') { ?>
                                                <div>
                                                <a class="btn-primary btn" href="javascript:;" data-id="<?php echo $r['invoice_id'];?>" data-toggle="modal" data-target="#modal-konfirmasi2"
                                                    title="">
                                                    Selesai
                                                </a>
                                                </div>
                                                <?php } ?>
                                            </td>
                                                <?php $no++; }}} ?>
                                            </tr>
                                            <?php }
                                                $jmldata = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM invoice WHERE invoice_id LIKE '%$search%' AND invoice_status='process' AND invoice_statustoko='processcod' OR invoice_statustoko='processcodtoko'"));
                                                $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
                                                $linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman);
                                            } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="wrapper">
                                <div class="paging">
                                    <div id='pagination'>
                                        <div class='pagination-right'>
                                            <ul class="pagination">
                                                <?php echo $linkHalaman ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="total">Total :
                                    <?php echo $jmldata;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ========== Modal Konfirmasi ========== -->
    <div id="modal-konfirmasi2" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Konfirmasi</h4>
                </div>

                <div class="modal-body" style="background:green;color:#fff">
                    Apakah anda yakin akan datang untuk COD ini?
                </div>

                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success" id="cod2-invoice">Ya</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                </div>

            </div>
        </div>
    </div>
    <!-- ========== End Modal Konfirmasi ========== -->
    <!-- ========== Modal Konfirmasi ========== -->
    <div id="modal-konfirmasi" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Konfirmasi</h4>
                </div>

                <div class="modal-body" style="background:green;color:#fff">
                    Apakah anda yakin akan datang untuk COD ini?
                </div>

                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success" id="cod-invoice">Ya</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                </div>

            </div>
        </div>
    </div>
    <!-- ========== End Modal Konfirmasi ========== -->
        <?php
        break;
        case "cancel":
        $result = mysqli_query($connect, "SELECT * FROM invoice WHERE invoice_id='$_GET[id]'");
        $p      = mysqli_fetch_array($result);
        ?>
        <form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" id="exampleStandardForm" autocomplete="off">
            <div class="page-content container-fluid">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="panel">
                        <div class="panel-heading">
                            <h5 class="panel-title">Cancel COD #
                                <?php echo $p['invoice_id']?>
                            </h5>
                        </div>
                        <div class="panel-body container-fluid">
                            <div class="form-group form-material">
                                <label class="control-label" for="inputText">Order ID</label>
                                <input type="text" readonly class="form-control input-sm" id="invoice_id" name="invoice_id" placeholder="Masukan Order ID"
                                    value="<?php echo $p['invoice_id']?>" required/>
                            </div>
                            <div class="form-group form-material">
                                <label class="control-label" for="inputText">Alasan Cancel</label>
                                <input type="text" class="form-control input-sm" id="invoice_cancel" name="invoice_cancel" placeholder="Masukan Alasan Cancel"
                                    value="" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-12">
                    <div class='button text-center'>
                        <input class="btn btn-primary btn-sm" type="submit" name="simpan" value="Cancel COD" id="validateButton2">
                        <a class="btn btn-default btn-sm" href="?module=transactioncod">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
        <?php
        break;
        case "detail":   
        $results = mysqli_query($connect, "SELECT * FROM invoice WHERE invoice_id='$_GET[id]'");
        $invoice      = mysqli_fetch_array($results);
        $result2 = mysqli_query($connect, "SELECT * FROM pelanggan WHERE pelanggan_username='$invoice[pelanggan_username]'");
        $pelanggan      = mysqli_fetch_array($result2);
        ?>
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h5 class="panel-title">Detail Pemesanan #
                                    <?php echo $_GET['id'] ?>
                                    <br>
                                    <br>
                                    <small>
                                        Username :
                                        <?php echo $pelanggan['pelanggan_username']; ?>
                                        <br> Nama :
                                        <?php echo $pelanggan['pelanggan_name']; ?>
                                        <br> Alamat :
                                        <?php echo $pelanggan['pelanggan_alamat']; ?>
                                        <br> No Telp :
                                        <?php echo $pelanggan['pelanggan_notelp']; ?>
                                        <br>
                                        <br> Alamat Pengiriman :
                                        <?php echo $invoice['invoice_address']; ?>
                                    </small>
                                    <br>
                                               
                                    <small>   
                                    Jenis : <?php echo $invoice['invoice_payment']; ?> <br>
                                    </small>                                 
                                </h5>
                            </div>
                            <div class="panel-body container-fluid table-detail">
                                <div class="table-full table-view">
                                    <div class="navigation-btn">
                                        <div class='btn-navigation'>
                                            <a class="btn btn-primary" href="?module=transactioncod">
                                                Kembali
                                            </a>
                                        </div>
                                        </form>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <th>ID</th>
                                                <th>Nama</th>
                                                <th>QTY</th>
                                                <th>Harga</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th>Dibuat</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                            $result=mysqli_query($connect,"SELECT * FROM transaction WHERE invoice_id='$_GET[id]'");
                                            $jmldata = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM transaction WHERE invoice_id='$_GET[id]'"));
                                            if(mysqli_num_rows($result) === 0) {
                                    ?>
                                                    <tr>
                                                        <td class="text-center" colspan="10">Data kosong...</td>
                                                    </tr>
                                                    <?php } else {
                                                $no = 1;
                                                while ($r=mysqli_fetch_array($result)) {
                                                $tanggal=tgl_indo($r['transaction_created']);
                                                $transaction_price=format_rupiah($r['transaction_price']);
                                                $transaction_totalprice=format_rupiah($r['transaction_totalprice']);
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $r['transaction_id'];?>
                                                        </td>
                                                        <td>
                                                            <?php echo $r['transaction_name'];?>
                                                        </td>
                                                        <td>
                                                            <?php echo $r['transaction_qty'];?>
                                                        </td>
                                                        <td>
                                                            <?php echo $transaction_price;?>
                                                        </td>
                                                        <td>
                                                            <?php echo $transaction_totalprice;?>
                                                        </td>
                                                        <td>
                                                        <?php if ($r['transaction_statustoko'] == 'processcod') { ?>
                                                            Menunggu Konfirmasi Toko
                                                        <?php } ?>
                                                        <?php if ($r['transaction_statustoko'] == 'processcodtoko') { ?>
                                                            Anda Setuju COD
                                                        <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $tanggal?>
                                                        </td>
                                                        <?php $no++; } ?>
                                                    </tr>
                                                    <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        break;
    }}
?>