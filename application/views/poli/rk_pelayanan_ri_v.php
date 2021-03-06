<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset{
	display: none;
}

#tombol_reset2{
	display: none;
}
</style>

<script type="text/javascript">
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

$(document).ready(function(){
	data_rawat_inap();

	$('#tombol_cari').click(function(){
        data_rawat_inap();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_pasien_belum').val("");
        data_rawat_inap();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });

	$('#jumlah_tampil').change(function(){
		data_rawat_inap();
	});

});

// PASIEN BELUM

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pasien_belum tbody tr");
    }

    window.tp = new Pagination('#tablePaging', {
        itemsCount:$selector.length,
        pageSize : parseInt(jumlah_tampil),
        onPageSizeChange: function (ps) {
            console.log('changed to ' + ps);
        },
        onPageChange: function (paging) {
            //custom paging logic here
            //console.log(paging);
            var start = paging.pageSize * (paging.currentPage - 1),
                end = start + paging.pageSize,
                $rows = $selector;

            $rows.hide();

            for (var i = start; i < end; i++) {
                $rows.eq(i).show();
            }
        }
    });
}

function data_rawat_inap(){
	$('#popup_load').show();
	var keyword = $('#cari_pasien_belum').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/data_rawat_inap',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='11' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					result[i].WAKTU = result[i].WAKTU==null?"-":result[i].WAKTU;
					result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";
					result[i].NAMA_DOKTER = result[i].NAMA_DOKTER==null?"-":result[i].NAMA_DOKTER;

					var encodedString = Base64.encode(result[i].ID);
					var aksi = '';
					var tgl_mrs = result[i].TANGGAL_MRS;

					var tgl_krs = '';
					if(result[i].TANGGAL_KELUAR == null && result[i].WAKTU_KELUAR == null){
						tgl_krs = '-';
					}else{
						tgl_krs = result[i].TANGGAL_KELUAR;
						// Here are the two dates to compare
						var date1 = "<?php echo date('Y-m-d'); ?>";
						var date2 = result[i].TGL_KELUAR_BALIK;
						// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
						date1 = date1.split('-');
						date2 = date2.split('-');
						// Now we convert the array to a Date object, which has several helpful methods
						date1 = new Date(date1[0], date1[1], date1[2]);
						date2 = new Date(date2[0], date2[1], date2[2]);
						// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
						date1_unixtime = parseInt(date1.getTime() / 1000);
						date2_unixtime = parseInt(date2.getTime() / 1000);
						// This is the calculated difference in seconds
						var timeDifference = date2_unixtime - date1_unixtime;
						// in Hours
						var timeDifferenceInHours = timeDifference / 60 / 60;
						// and finaly, in days :)
						var timeDifferenceInDays = timeDifferenceInHours  / 24;
						//in month
						var timeDifferenceInMonth = (date2.getFullYear() - date1.getFullYear()) * 12 + (date2.getMonth() - date1.getMonth());

						var hari = parseFloat(timeDifferenceInDays)-1;
						var warna = '';
						var keterangan = '';

						// console.log(hari);

						if(parseFloat(hari) > 0 && parseFloat(hari) <= 3){
							warna = "class='deezer_hj'";
							keterangan = "Sisa Rawat Inap tinggal <b>"+hari+" hari</b>";
						}else{
							warna = '';
							keterangan = keterangan;
						}
					}

					var sistem_bayar = '';
					if(result[i].SISTEM_BAYAR == '1'){
						sistem_bayar = 'Umum';
					}else{
						sistem_bayar = 'Asuransi';
					}

					var bed = '';
					if(result[i].STATUS_SUDAH == '1'){
						bed = result[i].KODE_KAMAR+' - '+result[i].KELAS+' / '+result[i].NOMOR_BED;
						aksi = '<span class="label label-success">Selesai Ditangani</span>';
						warna = "class='success'";
					}else{
						bed = result[i].KODE_KAMAR+' - '+result[i].KELAS+' / '+result[i].NOMOR_BED;
						aksi = '<a href="<?php echo base_url(); ?>poli/rk_pelayanan_ri_c/tindakan_ri/'+encodedString+'" class="btn btn-primary waves-effect waves-light btn-sm"><i class="fa fa-user-md"></i>&nbsp;Tindakan</a>';
					}

					// <button data-original-title="Tooltip on top" title="" data-placement="top" data-toggle="tooltip" class="btn btn-default" type="button">Tooltip on top</button>

					$tr += "<tr "+warna+" >"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+tgl_mrs+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+tgl_krs+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KODE_PASIEN+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_PASIEN+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].JENIS_KELAMIN+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+bed+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].DIRAWAT_SELAMA+" Hari</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_DOKTER+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+sistem_bayar+"</td>"+
								"<td style='vertical-align:middle;' align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_pasien_belum tbody').html($tr);
			paging();
			$('#popup_load').fadeOut(); 
		}
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_pasien_belum();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
	<div class="col-lg-12" id="view_data">
		<div class="card-box">
			<div class="row">
				<ul class="nav nav-tabs">
	                <li role="presentation" class="active">
	                    <a href="#pasien_belum" role="tab" data-toggle="tab"><i class="fa fa-list"></i> Data Pasien</a>
	                </li>
	                <!-- <li role="presentation" id="dt_pasien_sudah">
	                    <a href="#pasien_sudah" role="tab" data-toggle="tab"><i class="fa fa-upload"></i> Pasien Sudah</a>
	                </li> -->
	            </ul>
	            <div class="tab-content">
	                <div role="tabpanel" class="tab-pane fade in active" id="pasien_belum">
	                	<form class="form-horizontal" role="form">
	                		<div class="form-group">
	                			<div class="col-md-12">
					                <div class="input-group">
					                    <input type="text" class="form-control" id="cari_pasien_belum" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
					                    <span class="input-group-btn">
					                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
					                    		<i class="fa fa-search"></i>
					                    	</button>
					                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
					                    		<i class="fa fa-refresh"></i>
					                    	</button>
					                    </span>
					                </div>
					                <small><i><b>*pencarian berdasarkan No. RM, Nama Pasien dan Nama Dokter</b></i></small>
				                </div>
	                		</div>
	                	</form>
	                	<div class="table-responsive scroll-x">
				            <table id="tabel_pasien_belum" class="table table-bordered">
				                <thead>
				                    <tr class="merah">
				                        <th style="color:#fff; text-align:center;">No</th>
				                        <th style="color:#fff; text-align:center;">Tgl MRS / Waktu</th>
				                        <th style="color:#fff; text-align:center;">Tgl KRS / Waktu</th>
				                        <th style="color:#fff; text-align:center;">No. RM</th>
				                        <th style="color:#fff; text-align:center;">Nama</th>
				                        <th style="color:#fff; text-align:center;">Jenis Kelamin</th>
				                        <th style="color:#fff; text-align:center;">Kamar / Bed</th>
				                        <th style="color:#fff; text-align:center;">Dirawat Selama</th>
				                        <th style="color:#fff; text-align:center;">Dokter</th>
				                        <th style="color:#fff; text-align:center;">Sistem Bayar</th>
				                        <th style="color:#fff; text-align:center;">Aksi</th>
				                    </tr>
				                </thead>

				                <tbody>
				                    
				                </tbody>
				            </table>
				        </div>
				        <form class="form-horizontal" role="form">
				        	<div class="form-group">
				        		<div class="col-md-10">
				        			<div id="tablePaging"> </div>
				        		</div>
		                    </div>
		                    <div class="form-group">
				        		<div class="col-md-9">
				        			&nbsp;
				        		</div>
		                        <label class="col-md-2 control-label">Jumlah Tampil</label>
		                        <div class="col-md-1 pull-right">
					                <select class="form-control" id="jumlah_tampil">
		                                <option value="10">10</option>
		                                <option value="20">20</option>
		                                <option value="50">50</option>
		                                <option value="100">100</option>
		                            </select>
				                </div>
		                    </div>
		                    <div class="form-group">
	                            <div class="col-md-6">
	                                <table class="table table-bordered">
	                                    <thead>
	                                        <tr class="biru">
	                                            <th style="text-align:center; color: #fff;">Warna</th>
	                                            <th style="text-align:center; color: #fff;">Keterangan</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <tr>
	                                            <td class="success"><b>Hijau</b></td>
	                                            <td>Pasien Rawat Inap yang sudah selesai ditangani</td>
	                                        </tr>
	                                        <tr>
	                                            <td class="kuning"><b>Kuning</b></td>
	                                            <td>Pasien Rawat Inap yang waktu rawat inapnya kurang dari 3 hari dari tanggal KRS</td>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
				        </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>