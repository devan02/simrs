<?PHP  
ob_start();
// header("Cache-Control: no-cache, no-store, must-revalidate");  
// header("Content-Type: application/vnd.ms-excel");  
// header("Content-Disposition: attachment; filename=tes.xls");       
?>

<style type="text/css">
.tabel {
    background: #FAEBD7;
    table-layout: fixed;
    border-collapse: collapse;
}
.tabel th {
	background: #1793d1;
	vertical-align: middle;
	color : #FFF;
    height: 30px;
    border: 1px solid #fff;
}
.tabel td {
	background: #FFFFF0;
    vertical-align: middle;
    font: 11px/15px sans-serif;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
    border: 1px solid #ddd;
}

.footer{
    position:absolute;
    left:0;
    bottom:0;
}

.ttd{
    border-collapse: collapse;
    /*border: 1px solid black;*/
}
.ttd td{
    background: #fff;
    height: 30px;
    font-size: 14px;
    /*border: 1px solid black;*/
}
</style>

<?PHP 
function getOld($birthDate){
	$birthDate = explode("-", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
      ? ((date("Y") - $birthDate[2]) - 1)
      : (date("Y") - $birthDate[2]));

    return $age;
}

function formatTanggal($tgl){
	//22-11-2016
	$d = substr($tgl,0,2);
	$m = substr($tgl,3,2);
	$y = substr($tgl,6);

	$strBulan = "";

	if($m == '01'){
		$strBulan = "Januari";
	}else if($m == '02'){
		$strBulan = "Februari";
	}else if($m == '03'){
		$strBulan = "Maret";
	}else if($m == '04'){
		$strBulan = "April";
	}else if($m == '05'){
		$strBulan = "Mei";
	}else if($m == '06'){
		$strBulan = "Juni";
	}else if($m == '07'){
		$strBulan = "Juli";
	}else if($m == '08'){
		$strBulan = "Agustus";
	}else if($m == '09'){
		$strBulan = "September";
	}else if($m == '10'){
		$strBulan = "Oktober";
	}else if($m == '11'){
		$strBulan = "November";
	}else if($m == '12'){
		$strBulan = "Desember";
	}

	return $d." ".$strBulan." ".$y;
}

function terbilang($a) {
	$ambil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($a < 12)
	    return " " . $ambil[$a];
	elseif ($a < 20)
	    return Terbilang($a - 10) . " belas";
	elseif ($a < 100)
	    return Terbilang($a / 10) . " puluh" . Terbilang($a % 10);
	elseif ($a < 200)
	    return " seratus" . Terbilang($a - 100);
	elseif ($a < 1000)
	    return Terbilang($a / 100) . " ratus" . Terbilang($a % 100);
	elseif ($a < 2000)
	    return " seribu" . Terbilang($a - 1000);
	elseif ($a < 1000000)
	    return Terbilang($a / 1000) . " ribu" . Terbilang($a % 1000); 
	elseif ($a < 1000000000)
	    return Terbilang($a / 1000000) . " juta" . Terbilang($a % 1000000);
}
?>

<table align="left">
	<tr>
		<td>
			<img src="<?php echo base_url(); ?>picture/Indonesian_Red_Cross_Society_logo.png" style="width:100px; height:100px;">
		</td>
        <td>&nbsp;</td>
        <td>
            <b style="font-size:20px;">RS Panti Nirmala</b><br/>
            <h5>
                Jl. Kolonel Sugiono No. 1 Malang
                <br/>
                (0341) 411745
            </h5>
        </td>
	</tr>
</table>

<br/>
<hr>
<br/>

<table align="left">
    <tr>
        <td style="width:100px;">No. Transaksi</td>
        <td>:</td>
        <td style="width:150px;">TR-<?php echo $nomor_bill->NOMOR; ?></td>
        <td style="width:400px;">&nbsp;</td>
        <td style="width:100px;">No. Pasien</td>
        <td>:</td>
        <td style="width:230px;"><?php echo $dt->KODE_PASIEN; ?></td>
    </tr>
    <tr>
        <td style="width:100px;">Tgl Pelayanan</td>
        <td>:</td>
        <td style="width:150px;"><?php echo $dt->TANGGAL_DAFTAR; ?></td>
        <td style="width:400px;">&nbsp;</td>
        <td style="width:100px;">Nama Pasien</td>
        <td>:</td>
        <td style="width:230px;"><?php echo $dt->NAMA; ?></td>
    </tr>
    <tr>
        <td style="width:100px;">Sistem Bayar</td>
        <td>:</td>
        <td style="width:150px;"><?php echo $dt->SISTEM_BAYAR; ?></td>
        <td style="width:400px;">&nbsp;</td>
        <td style="width:100px;">Alamat</td>
        <td>:</td>
        <td style="width:230px;"><?php echo $dt->ALAMAT; ?></td>
    </tr>
    <tr>
        <td style="width:100px;">Status</td>
        <td>:</td>
        <td style="width:150px;">
        	<?PHP if($dt->STS_BAYAR == 0){
				echo '<font style="color:red; font-weight:bold;">Belum Lunas</font> ';
			} else {
				echo '<font style="color:green; font-weight:bold;">Lunas</font> ';
			} ?>
        </td>
        <td style="width:400px;">&nbsp;</td>
        <td style="width:100px;">Umur</td>
        <td>:</td>
        <td style="width:230px;"><?php echo getOld($dt->TANGGAL_LAHIR); ?> Tahun</td>
    </tr>
</table>

<br/><br/>

<table align="left" class="tabel">
	<thead>
        <tr>
            <th rowspan="2" style="text-align:center; vertical-align:middle; width:50px;">NO.</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle; width:120px;">PERAWATAN</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle; width:120px;">KAMAR</th>
            <th colspan="2" style="text-align:center; vertical-align:middle; width:120px;">TANGGAL</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle; width:70px;">LAMA<br>RAWAT</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle; width:100px;">HARI / KAMAR</th>
            <th rowspan="2" style="text-align:center; vertical-align:middle; width:120px;">AKUMULASI BIAYA<br>(Rp)</th>
        </tr>
        <tr>
        	<th style="text-align:center; vertical-align:middle;">MRS</th>
        	<th style="text-align:center; vertical-align:middle;">KRS</th>
        </tr>
    </thead>
    <tbody>
    	<tr>
    		<td colspan="8"><b>Kamar Rawat Inap</b></td>
    	</tr>
    	<tr>
        	<td style="text-align:center;">1</td>
        	<td>Perawatan Pasien</td>
        	<td style="white-space:nowrap;">
        		Nama Kamar : <?php echo $dt2->NAMA_KAMAR; ?><br>
        		Kelas : <?php echo $dt2->KELAS; ?>
        	</td>
        	<td style="text-align:center;"><?php echo $dt2->TANGGAL_MASUK; ?></td>
        	<td style="text-align:center;">
        	<?php
        		$i = $dt2->DIRAWAT_SELAMA;
        		$tgl = $dt2->TANGGAL_MASUK_BALIK;
        		$date = strtotime("+".$i." days", strtotime($tgl));
        		echo date("d-m-Y", $date);
        	?>
        	</td>
        	<td style="text-align:center;"><?php echo $i; ?> Hari</td>
        	<td style="text-align:right;"><?php echo number_format($dt2->BIAYA,0,'.',','); ?></td>
        	<td style="text-align:right;">
        	<?php
        		$biaya = $dt2->BIAYA;
        		$hari = $dt2->DIRAWAT_SELAMA;
        		$total = $biaya * $hari;
        		echo number_format($total,0,'.',',');
        	?>
        	</td>
        </tr>

        <tr>
    		<td colspan="8"><b>Tindakan</b></td>
    	</tr>
        <?php
        	$dt_detail = $this->model->getDetailLayananRI($id_pasien);

	        $no = 0;
        	$total_td = 0;
        	foreach ($dt_detail as $key => $row) {
        		$no++;
        		$total_td += $row->TARIF;
        ?>
        <tr>
            <td style="text-align:center;"> <?=$no;?> </td>
            <td style="width:380px;"> <?=$row->KET;?> </td>
            <td colspan="5">&nbsp;</td>
            <td align="right"> <?=number_format($row->TARIF,0,'.',',');?> </td>
        </tr> 
        <?PHP } ?>

        <tr>
    		<td colspan="8"><b>Visite</b></td>
    	</tr>
    	<?php
    		$dt_visite = $this->model->dataDetVisite_RI($id_pasien);

        	$no = 0;
        	$total_vt = 0;
        	foreach ($dt_visite as $key => $val_vt) {
        		$no++;
        		$total_vt += $val_vt->TARIF;
        ?>
        <tr>
        	<td style="text-align:center;"><?php echo $no; ?></td>
        	<td><?php echo $val_vt->NAMA_VISITE; ?></td>
        	<td colspan="5">
        		Dokter : <?php echo $val_vt->NAMA_DOKTER; ?>
        	</td>
        	<td align="right"> <?=number_format($val_vt->TARIF,0,'.',',');?> </td>
    	</tr>
        <?php
        	}
    	?>

    	<tr>
    		<td colspan="8"><b>Gizi</b></td>
    	</tr>
    	<?php
    		$dt_gizi = $this->model->dataDetGizi_RI($id_pasien);

        	$no = 0;
        	$total_gz = 0;
        	foreach ($dt_gizi as $val_gz) {
        		$no++;
        		$total_gz += $val_gz->TARIF;
        ?>
        	<tr>
        	<td style="text-align:center;"><?php echo $no; ?></td>
        	<td><?php echo $val_gz->NAMA_GIZI; ?></td>
        	<td colspan="5">&nbsp;</td>
        	<td align="right"> <?php echo number_format($val_gz->TARIF,0,'.',',');?> </td>
    	</tr>
        <?php
        	}
        ?>

        <tr>
    		<td colspan="8"><b>Oksigen</b></td>
    	</tr>
    	<?php
    		$dt_oksigen = $this->model->dataDetOksigen_RI($id_pasien);

        	$no = 0;
        	$total_ok = 0;
        	foreach ($dt_oksigen as $val_ok) {
        		$no++;
        		$total_ok += $val_ok->TOTAL;
        ?>
        	<tr>
        	<td style="text-align:center;"><?php echo $no; ?></td>
        	<td><?php echo $val_ok->KETERANGAN; ?></td>
        	<td colspan="5">
        		Jumlah : <?php echo $val_ok->JUMLAH; ?> Tabung x <?php echo number_format($val_ok->TARIF,0,'.',','); ?>
        	</td>
        	<td align="right"> <?php echo number_format($val_ok->TOTAL,0,'.',',');?> </td>
    	</tr>
        <?php
        	}
        ?>
        <tr>
    		<td colspan="8"><b>Jasa Perawat</b></td>
    	</tr>
    	<?php
    		$dt_diagnosa = $this->model->dataDetDiagnosa_RI($id_pasien);

        	$no = 0;
        	foreach ($dt_diagnosa as $val_dg) {
        		$no++;
        ?>
        	<tr>
        	<td style="text-align:center;"><?php echo $no; ?></td>
        	<td><?php echo $val_dg->TINDAKAN; ?></td>
        	<td colspan="5">&nbsp;</td>
        	<td align="right">-</td>
    	</tr>
        <?php
        	}
        ?>
        <tr>
    		<td colspan="8"><b>Resep</b></td>
    	</tr>
    	<?php
    		$dt_resep = $this->model->dataDetResep_RI($id_pasien);

        	$no = 0;
        	$total_rs = 0;
        	foreach ($dt_resep as $val_rs) {
        		$no++;
        		$total_rs += $val_rs->SUBTOTAL;
        ?>
        	<tr>
        	<td style="text-align:center;"><?php echo $no; ?></td>
        	<td><?php echo $val_rs->NAMA_OBAT; ?></td>
        	<td colspan="5">
        		Jumlah : <?php echo $val_rs->JUMLAH_BELI." ".$val_rs->NAMA_SATUAN; ?>
        	</td>
        	<td align="right"><?php echo number_format($val_rs->SUBTOTAL,0,'.',',');?></td>
    	</tr>
        <?php
        	}
        ?>
        <tr>
            <td style="text-align:right;" colspan="7"><b>TOTAL</b></td>
            <td style="text-align:right;">
                <b style="font-size:18px;">
                <?php
                    $grandtotal = $total + $total_td + $total_vt + $total_gz + $total_ok + $total_rs;
                    echo number_format($grandtotal,0,'.',',');
                ?>
                </b>
            </td>
        </tr>
    </tbody>
</table>

<br/>

<table align="center" class="tabel">
	<tr>
		<td style="text-align:center; width:700px;">
			TERBILANG <i><b><?php echo strtoupper(terbilang($grandtotal)); ?> RUPIAH</b></i>
		</td>
	</tr>
</table>

<div class="footer">
    <table align="right" class="ttd">
        <tr>
            <td style="text-align:right; width:130px;"><?php echo formatTanggal(date('d-m-Y')); ?></td>
        </tr>
        <tr>
        	<td style="height:90px;"></td>
        </tr>
    </table>
</div>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 11.69;
    $height_custom = 8.27;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    // $content = ob_get_clean();
    // $width_in_inches = $width_custom;
    // $height_in_inches = $height_custom;
    // $width_in_mm = $width_in_inches * 25.4;
    // $height_in_mm = $height_in_inches * 25.4;
    // $html2pdf = new HTML2PDF('L',array($width_in_mm,$height_in_mm),'en');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');

    $content = ob_get_clean();
    $html2pdf = new HTML2PDF('L','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>