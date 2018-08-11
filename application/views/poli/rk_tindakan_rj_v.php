<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tindakan_tambah, #view_tindakan_ubah{
	display: none;
}

#view_diagnosa_tambah, #view_diagnosa_ubah{
	display: none;
}

#view_resep_tambah, #view_resep_ubah{
	display: none;
}

#pindah_rawat_inap, #view_icu, #view_operasi, #view_meninggal{
	display: none;
}

#form_surat_dokter{
	display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
	<?php if($this->session->flashdata('sukses')){?>
		notif_simpan();
	<?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
    	notif_hapus();
    <?php } ?>

    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    $(".num_only").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    //TINDAKAN

    data_tindakan();

    $('#simpanTindakan').click(function(){
    	var tr = $('#tabel_tambah_tindakan tbody tr').length;
		if(tr == 0){
			toastr["error"]("Perhatian! Pilih tindakan dahulu", "Notifikasi");
		}else{
	    	$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_tindakan',
				data : $('#view_tindakan_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(res){
					$('#view_tindakan').show();
					$('#view_tindakan_tambah').hide();
					data_tindakan();
					notif_simpan();
				}
			});
		}
				
    });

	$('#btn_kembali').click(function(){
		window.location = "<?php echo base_url(); ?>poli/poli_home_c";
	});

	$('#btn_tambah').click(function(){
		$('#view_tindakan_tambah').show();
		$('#view_tindakan').hide();
		$('#view_tindakan_ubah').hide();
	});

	$('#batal').click(function(){
		$('#view_tindakan_tambah').hide();
		$('#view_tindakan').show();
		$('#view_tindakan_ubah').hide();
		$('#tabel_tambah_tindakan tbody tr').remove();
	});

	$('.btn_tindakan').click(function(){
		$('#popup_tindakan').click();
		load_tindakan();
	});

	//LABORATURIUM

	$('#btn_kirim_data').click(function(){
		$('#popup_lab_baru').click();
	});

	$('#simpanLab').click(function(){
		var id_pasien = $('#id_pasien_lab').val();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/update_ke_lab',
			data : {id_pasien:id_pasien},
			type : "POST",
			dataType : "json",
			success : function(res){
				toastr["success"]("Data Pasien telah dikirim ke Laborat!", "Notifikasi");
				$('#btn_tutup_lab').click();
			}
		});
	});

	//DIAGNOSA

	$('#dt_diagnosa').click(function(){
		data_diagnosa();
	});

	$('#btn_tambah_dg').click(function(){
		$('#view_diagnosa_tambah').show();
		$('#view_diagnosa').hide();
		$('#view_diagnosa_ubah').hide();
	});

	$('#batalDg').click(function(){
		$('#view_diagnosa_tambah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_ubah').hide();
	});

	$('.btn_penyakit_dg').click(function(){
		$('#popup_penyakit_dg').click();
		load_penyakit_diagnosa();
	});

	$('#simpanDg').click(function(){
		var diagnosa = $('#diagnosa').val();
		var tindakan = $('#tindakan_dg').val();
		var kasus = $('#id_kasus').val();
		var spesialistik = $('#id_spesialistik').val();

		if(diagnosa == ""){
			toastr["error"]("Silahkan isi diagnosa dengan benar!", "Notifikasi");
			$('#diagnosa').focus();
		}else if(tindakan == ""){
			toastr["error"]("Silahkan isi tindakan dengan benar!", "Notifikasi");
			$('#tindakan_dg').focus();
		}else if(kasus == ""){
			toastr["error"]("Silahkan isi kasus dengan benar!", "Notifikasi");
		}else if(spesialistik == ""){
			toastr["error"]("Silahkan isi spesialistik dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_spesialistik',
				data : $('#view_diagnosa_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					$('#diagnosa').val("");
					$('#tindakan_dg').val("");
					$('#id_kasus').val("");
					$('#id_spesialistik').val("");
					notif_simpan();
					data_diagnosa();
					$('#view_diagnosa').show();
					$('#view_diagnosa_tambah').hide();
				}
			});
		}
	});

	$('#simpanDgUbah').click(function(){
		var diagnosa = $('#diagnosa_ubah').val();
		var tindakan = $('#tindakan_dg_ubah').val();
		var kasus = $('#id_kasus_ubah').val();
		var spesialistik = $('#id_spesialistik_ubah').val();

		if(diagnosa == ""){
			toastr["error"]("Silahkan isi diagnosa dengan benar!", "Notifikasi");
			$('#diagnosa').focus();
		}else if(tindakan == ""){
			toastr["error"]("Silahkan isi tindakan dengan benar!", "Notifikasi");
			$('#tindakan_dg').focus();
		}else if(kasus == ""){
			toastr["error"]("Silahkan isi kasus dengan benar!", "Notifikasi");
		}else if(spesialistik == ""){
			toastr["error"]("Silahkan isi spesialistik dengan benar!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/ubah_diagnosa',
				data : $('#view_diagnosa_ubah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(result){
					notif_ubah();
					data_diagnosa();
					$('#view_diagnosa').show();
					$('#view_diagnosa_ubah').hide();
				}
			});
		}
	});

	$('#ya_dg').click(function(){
		var id = $('#id_hapus_dg').val();
		var id_pelayanan = "<?php echo $id; ?>";

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/hapus_diagnosa',
			data : {id:id,id_pelayanan:id_pelayanan},
			type : "POST",
			dataType : "json",
			success : function(result){
				$('#tidak_dg').click();
				notif_hapus();
				data_diagnosa();
			}
		});
	});

	//RESEP

	$('#dt_resep').click(function(){
		data_resep();
	});

	$('#btn_tambah_resep').click(function(){
		$('#view_resep_tambah').show();
		$('#view_resep').hide();
		$('#view_resep_ubah').hide();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/get_kode_resep',
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#kode_resep').val(kode);
			}
		});
	});

	$('#batalResep').click(function(){
		$('#view_resep_tambah').hide();
		$('#view_resep').show();
		$('#view_resep_ubah').hide();
		$('#tabel_tambah_resep tbody tr').remove();
		$('#id_obat').val("");
		$('#diminum_selama').val("");
	});

	$('.btn_obat_resep').click(function(){
		$('#popup_resep').click();
		load_obat();
	});

	$('#simpanResep').click(function(){
		var tr = $("#tabel_tambah_resep tbody tr").length;
		if(tr == 0){
			toastr["error"]("Obat kosong!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_resep',
				data : $('#view_resep_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(kode){
					$('#view_resep_tambah').hide();
					$('#view_resep').show();
					$('#view_resep_ubah').hide();
					$('#tabel_tambah_resep tbody tr').remove();
					$('#id_obat').val("");
					$('#diminum_selama').val("");
					data_resep();
					notif_simpan();
				}
			});
		}
	});

	$('#ya_resep').click(function(){
		var id = $('#id_hapus_resep').val();
		var id_pelayanan = "<?php echo $id; ?>";

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/hapus_resep',
			data : {id:id,id_pelayanan:id_pelayanan},
			type : "POST",
			dataType : "json",
			success : function(kode){
				$('#tidak_resep').click();
				data_resep();
				notif_hapus();
			}
		});
	});

	//KONDISI AKHIR

	$('#dt_kondisi_akhir').click(function(){
		var id_pelayanan = $('#id_rj').val();
		var id_poli = $('#id_poli_ka').val();
		var id_pasien = $('#id_pasien_ka').val();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cek_kondisi_akhir',
			data : {
				id_pelayanan:id_pelayanan,
				id_poli:id_poli,
				id_pasien:id_pasien
			},
			type : "POST",
			dataType : "json",
			success : function(row){
				$('#status_pasien').val(row['KONDISI_AKHIR']);
			}
		});
	});

	$('#kondisi_akhir').change(function(){
        var kondisi_akhir = $('#kondisi_akhir').val();
        if(kondisi_akhir == 'Rawat Inap'){
        	$('#pindah_rawat_inap').show();
        	$('#view_operasi').hide();
        	$('#view_icu').hide();
        	$('#view_meninggal').hide();
        }else if(kondisi_akhir == 'Operasi'){
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').show();
        	$('#view_icu').hide();
        	$('#view_meninggal').hide();
        }else if(kondisi_akhir == 'ICU'){
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').hide();
        	$('#view_icu').show();
        	$('#view_meninggal').hide();
        }else if(kondisi_akhir == 'Meninggal'){
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').hide();
        	$('#view_icu').hide();
        	$('#view_meninggal').show();
        }else{
        	$('#pindah_rawat_inap').hide();
        	$('#view_operasi').hide();
        	$('#view_icu').hide();
        	$('#view_meninggal').hide();
        }
    });

	$('#simpanKA').click(function(){
		$('#popup_load').show();

		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_kondisi',
			data : $('#view_kondisi_akhir').serialize(),
			type : "POST",
			dataType : "json",
			success : function(result){
				notif_simpan();
				$('#dt_kondisi_akhir').click();
				$('#popup_load').fadeOut();
				$('#view_kondisi_akhir').find("input[type='text']").val("");

				$('#pindah_rawat_inap').hide();
				$('#view_operasi').hide();

				$('#pindah_rawat_inap').find("input[type='text']").val("");
				$('#view_operasi').find("input[type='text']").val("");

				$('#pindah_rawat_inap').find("input[type='hidden']").val("");
				$('#view_operasi').find("input[type='hidden']").val("");
			}
		});
	});

	$('#batalKA').click(function(){ 
		$('#pindah_rawat_inap').hide();
		$('#view_icu').hide();
		$('#view_operasi').hide();
		$('#view_meninggal').hide();

		$('#pindah_rawat_inap').find("input[type='text']").val("");
		$('#view_icu').find("input[type='text']").val("");
		$('#view_operasi').find("input[type='text']").val("");
		$('#view_meninggal').find("input[type='text']").val("");

		$('#pindah_rawat_inap').find("input[type='hidden']").val("");
		$('#view_icu').find("input[type='hidden']").val("");
		$('#view_operasi').find("input[type='hidden']").val("");
		$('#view_meninggal').find("input[type='hidden']").val("");
	});

	$('.btn_ruangan').click(function(){
        $('#popup_ruangan').click();
        load_ruangan();
    });

    $('.btn_bed').click(function(){
        $('#popup_bed').click();
        load_bed();
    });

    $('.btn_ruang_icu').click(function(){
		$('#popup_ruang_icu').click();
		load_ruang_icu();
	});

    $('.btn_ruang_opr').click(function(){
		$('#popup_ruang_operasi').click();
		load_ruang_operasi();
	});

	$('.btn_kamar_jenazah').click(function(){
		$('#popup_kamar_jenazah').click();
		load_kamar_jenazah();
	});

	$('.btn_lemari_jenazah').click(function(){
		$('#popup_lemari_jenazah').click();
		load_lemari_jenazah();
	});

    //SURAT DOKTER
    $('#dt_data_surat_dokter').click(function(){
    	data_surat_dokter_ada();
    });

    $('#simpanSD').click(function(){
    	var id_pasien = $('#id_pasien_sd').val();
    	$.ajax({
    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/cek_surat_dokter',
    		data : {id_pasien:id_pasien},
    		type : "POST",
    		dataType : "json",
    		success : function(total){
    			if(total != 0){
    				notif_surat_dokter();
    			}else{
    				$.ajax({
			    		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/simpan_surat_dokter',
			    		data : $('#view_surat_dokter').serialize(),
			    		type : "POST",
			    		dataType : "json",
			    		success : function(result){
			    			window.open('<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/surat_dokter/'+id_pasien,'_blank');
							$('#waktu_sd').val("");
					    	$('#mulai_tgl_sd').val("");
					    	$('#sampai_tgl_sd').val("");    			
			    		}
			    	});
    			}
    		}
    	});
    });

    $('#batalSD').click(function(){
    	$('#waktu_sd').val("");
    	$('#mulai_tgl_sd').val("");
    	$('#sampai_tgl_sd').val("");
    });

});

//TINDAKAN

function load_tindakan(){
	var keyword = $('#cari_tindakan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_tindakan',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_tindakan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_tindakan tbody').html($tr);
		}
	});
}

function klik_tindakan(id){
	$('#tutup_tindakan').click();
	var id_ubah = $('#id_ubah').val();

	if(id_ubah == ""){
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_tindakan',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(result){
				$tr = "";

				for(var i=0; i<result.length; i++){
					var jumlah_data = $('#tr_'+result[i].ID).length;

					var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

					if(jumlah_data > 0){
						var jumlah = $('#jumlah_'+result[i].ID).val();
						$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
					}else{
						$tr = "<tr class='trIkiBosTdk' id='tr_"+result[i].ID+"'>"+
								"<input type='hidden' name='id_tindakan[]' value='"+result[i].ID+"'>"+
								"<input type='hidden' id='tarif_"+result[i].ID+"' value='"+result[i].TARIF+"'>"+
								"<input type='hidden' name='subtotal[]' id='subtotal_"+result[i].ID+"' value=''>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td>"+
									"<div class='col-md-12'>"+
					                    "<input type='text' class='form-control' name='jumlah[]' id='jumlah_"+result[i].ID+"' value='1' onkeyup='FormatCurrency(this); hitung_jumlah("+result[i].ID+");'>"+
				                    "</div>"+
								"</td>"+
								"<td style='vertical-align:middle; text-align:right;'><b id='subtotal_txt_"+result[i].ID+"'></b></td>"+
								"<td align='center'>"+aksi+"</td>"+
							  "</tr>";
					}
				}

				$('#tabel_tambah_tindakan tbody').append($tr);
				hitung_jumlah(id);
				hitung_tarif_tindakan();
			}
		});
	}else{
		$.ajax({
			url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/tindakan_id',
			data : {id:id},
			type : "POST",
			dataType : "json",
			success : function(row){
				$('#id_tindakan_ubah').val(row['ID']);
				$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
				$('#tarif_txt').val(formatNumber(row['TARIF']));
				$('#jumlah_ubah').val("");
				$('#subtotal_ubah').val("");
				$('#jumlah_ubah').focus();
			}
		});
	}
}

function deleteRow(btn){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
}

function hitung_jumlah(id){
	var tarif = $('#tarif_'+id).val();
	var jumlah = $('#jumlah_'+id).val();
	jumlah = jumlah.split(',').join('');

	if(jumlah == ""){
		jumlah = 0;
	}

	var subtotal = parseFloat(tarif) * parseFloat(jumlah);
	$('#subtotal_txt_'+id).html(formatNumber(subtotal));
	$('#subtotal_'+id).val(subtotal);
	hitung_tarif_tindakan();
}

function hitung_tarif_tindakan(){
	var total = 0;
	$("input[name='subtotal[]']").each(function(idx,elm){
		var tarif = elm.value;
		total += parseFloat(tarif);
	});
	$('#tot_tarif_tindakan').val(formatNumber(total));
}

function data_tindakan(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_tindakan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";
			var total = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					total += parseFloat(result[i].SUBTOTAL);

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_tindakan('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_tindakan('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>'; 

					var tanggal = formatTanggal(result[i].TANGGAL)+" - "+result[i].WAKTU;

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle;'>"+tanggal+"</td>"+
								"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+formatNumber(result[i].JUMLAH)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_tindakan tbody').html($tr);
			$('#grandtotal_tindakan').html(formatNumber(total));
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_tindakan(id){
	$('#view_tindakan_ubah').show();
	$('#view_tindakan_tambah').hide();
	$('#view_tindakan').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_tindakan_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#tanggal_ubah').val(formatTanggal(row['TANGGAL']));
			$('#id_tindakan_ubah').val(row['TINDAKAN']);
			$('#tindakan_txt').val(row['NAMA_TINDAKAN']);
			$('#tarif_txt').val(formatNumber(row['TARIF']));
			$('#jumlah_ubah').val(formatNumber(row['JUMLAH']));
			$('#subtotal_ubah').val(formatNumber(row['SUBTOTAL']));
		}
	});

	$('#batal_ubah').click(function(){
		$('#id_ubah').val("");
		$('#view_tindakan_ubah').hide();
		$('#view_tindakan_tambah').hide();
		$('#view_tindakan').show();
	});
}

function hapus_tindakan(id){
	$('#popup_hapus').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_tindakan_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus').val(id);
			$('#msg').html('Apakah tindakan ini <b>'+row['NAMA_TINDAKAN']+'</b> ingin dihapus?');
			$('#ket_hapus').val('Tindakan');
		}
	});
}

function hitung_jumlah2(){
	var tarif = $('#tarif_txt').val();
	var jumlah = $('#jumlah_ubah').val();

	tarif = tarif.split(',').join('');
	jumlah = jumlah.split(',').join('');

	if(tarif == ""){
		tarif = 0;
	}

	if(jumlah == ""){
		jumlah = 0;
	}

	var subtotal = parseFloat(tarif) * parseFloat(jumlah);
	$('#subtotal_ubah').val(formatNumber(subtotal));
}

//DIAGNOSA

function load_penyakit_diagnosa(){
	var keyword = $('#cari_penyakit_dg').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_penyakit',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_penyakit("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].URAIAN+"</td>"+
							"</tr>";
				}
			}

			$('#tb_penyakit_dg tbody').html($tr);
		}
	});

	$('#cari_penyakit_dg').off('keyup').keyup(function(){
		load_penyakit_diagnosa();
	});
}

function klik_penyakit(id){
	$('#tutup_penyakit_dg').click();
	
	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_penyakit_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			var id_ubah = $('#id_ubah_dg').val();
			if(id_ubah == ""){
				$('#id_penyakit').val(id);
				$('#nama_penyakit').val(row['URAIAN']);
				$('#id_penyakit_ubah').val("");
				$('#nama_penyakit_ubah').val("");
			}else{
				$('#id_penyakit').val("");
				$('#nama_penyakit').val("");
				$('#id_penyakit_ubah').val(id);
				$('#nama_penyakit_ubah').val(row['URAIAN']);
			}
		}
	});
}

function data_diagnosa(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_diagnosa',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="ubah_diagnosa('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_diagnosa('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td>"+result[i].DIAGNOSA+"</td>"+
								"<td>"+result[i].TINDAKAN+"</td>"+
								"<td>"+result[i].URAIAN+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_diagnosa tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function ubah_diagnosa(id){
	$('#view_diagnosa_ubah').show();
	$('#view_diagnosa').hide();
	$('#view_diagnosa_tambah').hide();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah_dg').val(id);
			$('#diagnosa_ubah').val(row['DIAGNOSA']);
			$('#tindakan_dg_ubah').val(row['TINDAKAN']);
			$('#id_penyakit_ubah').val(row['ID_PENYAKIT']);
			$('#nama_penyakit_ubah').val(row['URAIAN']);
		}
	});

	$('#batalDgUbah').click(function(){
		$('#view_diagnosa_ubah').hide();
		$('#view_diagnosa').show();
		$('#view_diagnosa_tambah').hide();
		$('#id_ubah_dg').val("");
	});
}

function hapus_diagnosa(id){
	$('#popup_hapus_dg').click();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_diagnosa_id',
		data : {id:id,id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_dg').val(id);
			$('#msg_dg').html('Apakah data <b>'+row['DIAGNOSA']+'</b> ingin dihapus?');
		}
	});
}

//RESEP

function load_obat(){
	var keyword = $('#cari_resep').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_resep',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_obat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE_OBAT+"</td>"+
								"<td>"+result[i].NAMA_OBAT+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].HARGA_JUAL)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_resep tbody').html($tr);
		}
	});
}

function deleteRowObat(btn,id){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
	var grandtotal = 0;
	$("input[name='total_obat[]']").each(function(id,elm){
		var t = elm.value;
		t = t.split(',').join('');
		if(t == ""){
			t = '0';
		}
		grandtotal += parseFloat(t);
	});

	$('#grandtotal_resep').html(formatNumber(grandtotal));
	$('#grandtotal_resep_txt').val(grandtotal);
}

function klik_obat(id){
	$('#tutup_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_resep',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_resep2_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRowObat(this,"+result[i].ID+");'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					
				}else{
					$tr = "<tr id='tr_resep2_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_obat_resep[]' value='"+result[i].ID+"'>"+
							"<input type='hidden' name='harga_obat[]' id='harga_obat_"+result[i].ID+"' value='"+result[i].HARGA_JUAL+"'>"+
							"<td>"+result[i].KODE_OBAT+"</td>"+
							"<td>"+result[i].NAMA_OBAT+"</td>"+
							"<td style='text-align:right;'>"+formatNumber(result[i].HARGA_JUAL)+"</td>"+
							"<td align='center'><input type='text' class='form-control' name='jumlah_obat[]' value='' id='jumlah_obat_"+result[i].ID+"' style='width:125px;' onkeyup='FormatCurrency(this); hitung_resep("+result[i].ID+")'></td>"+
							"<td align='center'><input type='text' class='form-control' name='total_obat[]' value='' id='total_obat_"+result[i].ID+"' style='width:125px;' readonly></td>"+
							"<td align='center'><input type='text' class='form-control' name='takaran_resep[]' value='' style='width:125px;'></td>"+
							"<td align='center'><input type='text' class='form-control' name='aturan_minum[]' value='' style='width:125px;'></td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}

			$('#tabel_tambah_resep tbody').append($tr);
		}
	});
}

function data_resep(){
	$('#popup_load').show();
	var id_pelayanan = "<?php echo $id; ?>";

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_resep',
		data : {id_pelayanan:id_pelayanan},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="detail_resep('+result[i].ID+');">'+
									'<i class="fa fa-eye"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_resep('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE_RESEP+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
								"<td style='text-align:center;'>"+result[i].DIMINUM_SELAMA+" Hari</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TOTAL)+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_resep tbody').html($tr);
			$('#popup_load').fadeOut();
		}
	});
}

function detail_resep(id){
	$('#popup_det_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/detail_resep',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].NAMA_OBAT+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].HARGA)+"</td>"+
								"<td style='text-align:center;'>"+result[i].TAKARAN+"</td>"+
								"<td>"+result[i].ATURAN_MINUM+"</td>"+
							"</tr>";
				}
			}

			$('#tb_det_resep tbody').html($tr);
		}
	});
}

function hitung_resep(id){
	var harga = $('#harga_obat_'+id).val();
	var jumlah = $('#jumlah_obat_'+id).val();
	harga = harga.split(',').join('');
	jumlah = jumlah.split(',').join('');
	if(jumlah == ""){
		jumlah = '0';
	}
	var total = parseFloat(harga) * parseFloat(jumlah);
	$('#total_obat_'+id).val(formatNumber(total));

	var grandtotal = 0;
	$("input[name='total_obat[]']").each(function(id,elm){
		var t = elm.value;
		t = t.split(',').join('');
		if(t == ""){
			t = '0';
		}
		grandtotal += parseFloat(t);
	});

	$('#grandtotal_resep').html(formatNumber(grandtotal));
	$('#grandtotal_resep_txt').val(grandtotal);
}

function hapus_resep(id){
	$('#popup_hapus_resep').click();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_resep_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_hapus_resep').val(id);
			$('#msg_resep').html('Apakah resep <b>'+row['KODE_RESEP']+'</b> ingin dihapus?');
		}
	});
}

// KONDISI AKHIR

function load_ruangan(){
    var kelas = $('#kelas_kamar').val();
    var keyword = $('#cari_kamar').val();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_ruangan',
        data : {kelas:kelas,keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruangan("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td>"+result[i].NAMA_KAMAR+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KATEGORI+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KELAS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar tbody').html($tr);
        }
    });

    $('#cari_kamar').off('keyup').keyup(function(){
        load_ruangan();
    });
}

function klik_ruangan(id){
    $('#tutup_kamar').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_ruangan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruangan').val(id);
            var txt = row['KODE_KAMAR']+' - '+row['NAMA_KAMAR'];
            $('#ruang_tujuan').val(txt);
            $('#biaya').val(NumberToMoney(row['BIAYA']));
        }
    });
}

function load_bed(){
    var id_kamar = $('#id_ruangan').val();
    var keyword = $('#cari_bed').val();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_bed',
        data : {id_kamar:id_kamar,keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                for(var i=0; i<result.length; i++){
                    var stt = "";
                    var warna = "";
                    var disabled = "";
                    var diklik = "";

                    if(result[i].STATUS_PAKAI == 0){
                        stt = '<span class="label label-success">KOSONG</span>';
                        warna = "";
                        disabled = "";
                        diklik = "style='cursor:pointer;' onclick='klik_bed("+result[i].ID+");'";
                    }else{
                        stt = '<span class="label label-danger">TERPAKAI</span>';
                        warna = "terpakai";
                        disabled = "disabled='disabled'";
                        diklik = "";
                    }

                    $tr += "<tr class='"+warna+"' "+disabled+" "+diklik+">"+
                                "<td style='text-align:center;'>"+result[i].NO+"</td>"+
                                "<td>"+result[i].NOMOR_BED+"</td>"+
                                "<td style='text-align:center;'>"+stt+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_bed tbody').html($tr);
        }
    });

    $('#cari_bed').off('keyup').keyup(function(){
        load_bed();
    });
}

function klik_bed(id){
    $('#tutup_bed').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_bed',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_bed').val(id);
            $('#bed').val(row['NOMOR_BED']);
        }
    });
}

function load_ruang_icu(){
	var keyword = $('#cari_ruang_icu').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_ruang_icu',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruang_icu("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE+"</td>"+
                                "<td>"+result[i].NAMA_RUANG+"</td>"+
                                "<td style='text-align:center;'>Level "+angkaRomawi(result[i].LEVEL)+"</td>"+
                                "<td style='text-align:center;'>Tipe "+result[i].TIPE+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_ruang_icu tbody').html($tr);
        }
    });

    $('#cari_ruang_icu').off('keyup').keyup(function(){
        load_ruang_icu();
    });
}

function klik_ruang_icu(id){
	$('#tutup_ruang_icu').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_ruang_icu',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruang_icu').val(id);
            $('#ruang_icu').val(row['NAMA_RUANG']);
            $('#level_icu').val('Level '+angkaRomawi(row['LEVEL']));
            $('#tipe_icu').val('Tipe '+row['TIPE']);
        }
    });
}

function load_ruang_operasi(){
	var keyword = $('#cari_ruang_operasi').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_ruang_operasi',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruang_operasi("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE+"</td>"+
                                "<td>"+result[i].NAMA_RUANG+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_ruang_operasi tbody').html($tr);
        }
    });

    $('#cari_ruang_operasi').off('keyup').keyup(function(){
        load_ruang_operasi();
    });
}

function klik_ruang_operasi(id){
	$('#tutup_ruang_operasi').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_ruang_operasi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruang_opr').val(id);
            $('#ruang_operasi').val(row['NAMA_RUANG']);
        }
    });
}

function load_kamar_jenazah(){
	var keyword = $('#cari_kamar_jenazah').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_kamar_jenazah',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_kamar_jenazah("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td>"+result[i].NAMA_KAMAR+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar_jenazah tbody').html($tr);
        }
    });

    $('#cari_kamar_jenazah').off('keyup').keyup(function(){
        load_kamar_jenazah();
    });
}

function klik_kamar_jenazah(id){
	$('#tutup_kamar_jenazah').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_kamar_jenazah',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_kamar_jenazah').val(id);
            $('#kamar_jenazah').val(row['NAMA_KAMAR']);
            $('#tarif_kamar_jenazah').val(formatNumber(row['BIAYA']));
        }
    });
}

function load_lemari_jenazah(){
	var id_kamar = $('#id_kamar_jenazah').val();

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/load_lemari_jenazah',
        data : {id_kamar:id_kamar},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                	var stt = "";
                	var disabled = "";
                	if(result[i].STATUS_PAKAI == '0'){
                		stt = '<span class="label label-danger">Kosong</span>';
                		disabled = "style='cursor:pointer;' onclick='klik_lemari_jenazah("+result[i].ID+");'";
                	}else{
                		stt = '<span class="label label-success">Terpakai</span>';
                		disabled = "class='info'";
                	}

                    $tr += "<tr "+disabled+" >"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].NOMOR_LEMARI+"</td>"+
                                "<td style='text-align:center;'>"+stt+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_lemari_jenazah tbody').html($tr);
        }
    });
}

function klik_lemari_jenazah(id){
	$('#tutup_lemari_jenazah').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/klik_lemari_jenazah',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_lemari_jenazah').val(id);
            $('#lemari_jenazah').val(row['NOMOR_LEMARI']);
        }
    });
}

// SURAT DOKTER

function data_surat_dokter(){
	$('#popup_load').show();
	var id = "<?php echo $id; ?>";

	$.ajax({
        url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_surat_dokter',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
            	var no = 0;

                for(var i=0; i<result.length; i++){
                	no++;

                	result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";

                	var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="surat_dokter('+result[i].ID+');">'+
									'<i class="fa fa-print"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_surat_dokter('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

                    $tr += "<tr>"+
                    			"<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL)+"</td>"+
                                "<td>"+result[i].NAMA+"</td>"+
                                "<td style='text-align:center;'>"+result[i].JENIS_KELAMIN+"</td>"+
                                "<td style='text-align:center;'>"+result[i].UMUR+" Tahun</td>"+
                                "<td align='center'>"+aksi+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_surat_dokter tbody').html($tr);
            $('#popup_load').fadeOut();
        }
    });
}

function data_surat_dokter_ada(){
	var id_pasien = $('#id_pasien_dt_sd').val();

	$.ajax({
		url : '<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/data_surat_dokter_id',
		data : {id_pasien:id_pasien},
		type : "POST",
		dataType : "json",
		success : function(row){
			row['NAMA'] = row['NAMA']==null?"-":row['NAMA'];
			row['UMUR'] = row['UMUR']==null?"-":row['UMUR'];
			row['UMUR_BULAN'] = row['UMUR_BULAN']==null?"-":row['UMUR_BULAN'];
			row['ALAMAT'] = row['ALAMAT']==null?"-":row['ALAMAT'];
			row['WAKTU_ISTIRAHAT'] = row['WAKTU_ISTIRAHAT']==null?"-":row['WAKTU_ISTIRAHAT'];
			row['MULAI_TANGGAL'] = row['MULAI_TANGGAL']==null?"-":row['MULAI_TANGGAL'];
			row['SAMPAI_TANGGAL'] = row['SAMPAI_TANGGAL']==null?"-":row['SAMPAI_TANGGAL'];

			if(row.length == 0){
				$('#btn_cetak').attr('disabled','disabled');
			}else{
				$('#btn_cetak').removeAttr('disabled');
			}

			$tr = '<tr>'+
                  '    <td>Nama</td>'+
                  '    <td>: '+row['NAMA']+'</td>'+
                  '</tr>'+
                  '<tr>'+
                  '    <td>Umur</td>'+
                  '    <td>: '+row['UMUR']+' Tahun '+row['UMUR_BULAN']+' Bulan</td>'+
                  '</tr>'+
                  '<tr>'+
                  '    <td>Alamat</td>'+
                  '    <td>: '+row['ALAMAT']+'</td>'+
                  '</tr>'+
                  '<tr>'+
                  '    <td>Waktu Istirahat</td>'+
                  '    <td>: '+row['WAKTU_ISTIRAHAT']+' Hari</td>'+
                  '</tr>'+
                  '<tr>'+
                  '    <td>Mulai Tanggal</td>'+
                  '    <td>: '+row['MULAI_TANGGAL']+'</td>'+
                  '</tr>'+
                  '<tr>'+
                  '    <td>Sampai Tanggal</td>'+
                  '    <td>: '+row['SAMPAI_TANGGAL']+'</td>'+
                  '</tr>';

            $('#tabel_data_surat_dokter').html($tr);
		}
	});
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
	<div class="row">
		<div class="col-md-6">
            <div class="card-box">
            	<h4><i class="fa fa-user"></i> Rekam Medik Pasien</h4>
            	<hr/>
            	<table class="table">
            		<tbody>
            			<tr>
            				<td>NO. RM</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $dt->KODE_PASIEN; ?></span></td>
            				<td>NAMA</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $dt->NAMA_PASIEN; ?></span></td>
            			</tr>
            			<tr>
            				<?php
	                    		$jk = "";
	                    		if($dt->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
	                    	?>
            				<td>JENIS KELAMIN</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $jk; ?></span></td>
            				<td>UMUR</td>
            				<td>:</td>
            				<td><span style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span></td>
            			</tr>
            			<tr>
            				<td>ALAMAT</td>
            				<td>:</td>
            				<td colspan="4">
            					<span style="color:#0066b2;">
            						<?php echo $dt->ALAMAT; ?> Kec. <?php echo $dt->KECAMATAN; ?><br>
            						Kel. <?php echo $dt->KELURAHAN; ?> <br>
            						Kec. <?php echo $dt->KOTA; ?>
            					</span>
            				</td>
            			</tr>
            		</tbody>
            	</table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-box">
            	<h4><i class="fa fa-user-md"></i> Dokter</h4>
            	<hr/>
            	<table class="table">
            		<tbody>
	            		<tr>
	            			<td>ASAL RUJUKAN</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->ASAL_RUJUKAN; ?></td>
	            		</tr>
	            		<tr>
	            			<td>PELAYANAN</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->STATUS; ?></td>
	            		</tr>
	            		<tr>
	            			<td>POLI</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->NAMA_POLI; ?></td>
	            		</tr>
	            		<tr>
	            			<td>DOKTER</td>
	            			<td>:</td>
	            			<td><span style="color:#0066b2;"><?php echo $dt->NAMA_DOKTER; ?></td>
	            		</tr>
            		</tbody>
            	</table>
            </div>
        </div>
	</div>
</div>

<div class="col-lg-12">
	<div class="card-box">
		<div class="row">
			<ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#tindakan1" role="tab" data-toggle="tab"><i class="fa fa-stethoscope"></i>&nbsp;Tindakan</a>
                </li>
                <li role="presentation" id="dt_diagnosa">
                    <a href="#diagnosa1" role="tab" data-toggle="tab"><i class="fa fa-heartbeat"></i>&nbsp;Diagnosa</a>
                </li>
                <li role="presentation" id="dt_resep">
                    <a href="#resep1" role="tab" data-toggle="tab"><i class="fa fa-medkit"></i>&nbsp;Resep</a>
                </li>
                <li role="presentation" id="dt_laborat">
                    <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laboraturium</a>
                </li>
                <li role="presentation" id="dt_kondisi_akhir">
                    <a href="#kondisi_akhir1" role="tab" data-toggle="tab"><i class="fa fa-check-square-o"></i>&nbsp;Kondisi Akhir</a>
                </li>
                <li role="presentation" id="dt_surat_dokter">
                    <a href="#surat_dokter1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i>&nbsp;Surat Dokter</a>
                </li>
                <li role="presentation" id="dt_data_surat_dokter">
                    <a href="#data_surat_dokter1" role="tab" data-toggle="tab"><i class="fa fa-file-text-o"></i>&nbsp;Data Surat Dokter</a>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tindakan1">
                    <form class="form-horizontal" id="view_tindakan">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Tindakan</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Tindakan</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12"> 
			                    <div class="table-responsive">
						            <table id="tabel_tindakan" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Sub Total</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-8">
                    			&nbsp;
                    		</div>
                    		<div class="col-md-4">
                    			<div class="card-box widget-user" style="background-color:#cee3f8;">
		                            <div>
		                                <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/Money_44325.png">
		                                <div class="wid-u-info">
		                                    <small class="text-primary"><b>Grand Total</b></small>
		                                    <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_tindakan">0</h4>
		                                </div>
		                            </div>
		                        </div>
                    		</div>
                    	</div>
                    </form>

					<form class="form-horizontal" id="view_tindakan_tambah" action="" method="post">
						<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
						<h4><i class="fa fa-plus"></i> Tambah Tindakan</h4>
						<hr>
	                    <!--
	                    <div class="form-group">
	                    	<label class="col-md-1 control-label">Perawat</label>
                    		<?php
		                	// $perawat = $this->model->data_poli_perawat($dt->ID_POLI);
		                	// $no = 0;
		                	// if($perawat == null || $perawat == ""){
		                		
		                	// }else{
			                // 	foreach ($perawat as $value) {
			                // 		$no++;
		                	?>
		                	<div class="col-md-2">
                    			<div class="inbox-widget nicescroll">
				                 	<a href="javascript:void(0);" style="cursor:default;">
	                                    <div class="inbox-item">
	                                        <div class="inbox-item-img">
	                                        	<img src="<?php //echo base_url(); ?>picture/nurse-icon.png" class="img-circle">
	                                        </div>
	                                        <p class="inbox-item-author"><?php //echo $value->NAMA_PEGAWAI; ?></p>
	                                        <p class="inbox-item-text"><?php //echo $value->NIP; ?></p>
	                                    </div>
	                                </a> 
	                            </div>
                    		</div>
		                	<?php
		                	// 	}
		                	// }
			                ?>
	                    </div> 
	                    -->
						<div class="form-group">
	                        <label class="col-md-1 control-label">Tindakan</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                                <input type="text" class="form-control" value="" readonly="readonly" required="required">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_tindakan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">&nbsp;</label>
	                        <div class="col-md-9">
	                            <div class="table-responsive">
						            <table id="tabel_tambah_tindakan" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Subtotal</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Total Tarif</label>
	                        <div class="col-md-5">
	                        	<input type="text" class="form-control" name="tot_tarif_tindakan" id="tot_tarif_tindakan" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanTindakan"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batal"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
					</form>

					<form class="form-horizontal" id="view_tindakan_ubah" action="<?php echo $url_ubah; ?>" method="post">
						<input type="hidden" name="id_ubah" id="id_ubah" value="">
						<input type="hidden" name="id_pelayanan" value="<?php echo $id; ?>">
						<h4><i class="fa fa-pencil"></i> Ubah Tindakan</h4>
						<hr>
						<div class="form-group">
	                        <label class="col-md-1 control-label">Tanggal</label>
	                        <div class="col-md-3">
	                        	<div class="input-group">
	                                <span class="input-group-addon">
	                                    <i class="fa fa-calendar"></i>
	                                </span>
	                                <input type="text" class="form-control" name="tanggal_ubah" id="tanggal_ubah" value="" readonly>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Tindakan</label>
	                        <div class="col-md-5">
	                            <div class="input-group">
	                            	<input type="hidden" name="id_tindakan_ubah" id="id_tindakan_ubah" value="">
	                                <input type="text" class="form-control" id="tindakan_txt" value="" readonly="readonly">
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_tindakan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Tarif</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" id="tarif_txt" value="" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Jumlah</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="jumlah_ubah" id="jumlah_ubah" value="" onkeyup="FormatCurrency(this); hitung_jumlah2();">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-1 control-label">Sub Total</label>
	                        <div class="col-md-5">
	                            <input type="text" class="form-control" name="subtotal_ubah" id="subtotal_ubah" value="" readonly>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanTindakan_ubah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batal_ubah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
					</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="diagnosa1">
                	<form class="form-horizontal" id="view_diagnosa">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Diagnosa</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_dg">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Diagnosa</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_diagnosa" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Diagnosa</th>
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Jenis Penyakit</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_diagnosa_tambah" action="" method="post">
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
						<h4><i class="fa fa-plus"></i> Tambah Diagnosa</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Diagnosa</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="diagnosa" name="diagnosa"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Tindakan</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="tindakan_dg" name="tindakan_dg"></textarea>
							</div>
						</div>
						<div class="form-group">
	                        <label class="col-md-2 control-label">Jenis Penyakit</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_penyakit" id="id_penyakit" value="">
	                                <input type="text" class="form-control" id="nama_penyakit" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_penyakit_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDg"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDg"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>

                    <form class="form-horizontal" id="view_diagnosa_ubah" action="" method="post">
                    	<input type="hidden" name="id_ubah_dg" id="id_ubah_dg" value="">
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
						<h4><i class="fa fa-plus"></i> Ubah Diagnosa</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Diagnosa</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="diagnosa_ubah" name="diagnosa_ubah"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Tindakan</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="5" id="tindakan_dg_ubah" name="tindakan_dg_ubah"></textarea>
							</div>
						</div>
						<div class="form-group">
	                        <label class="col-md-2 control-label">Jenis Penyakit</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_penyakit_ubah" id="id_penyakit_ubah" value="">
	                                <input type="text" class="form-control" id="nama_penyakit_ubah" value="" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_penyakit_dg" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanDgUbah"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalDgUbah"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="resep1">
                	<form class="form-horizontal" id="view_resep">
                    	<div class="form-group">
                    		<div class="col-md-6">
                    			<h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Resep</b></h4>
                    		</div>
                    		<div class="col-md-6">
			                    <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_resep">
									<i class="fa fa-plus"></i>&nbsp;<b>Tambah Resep</b>
								</button>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_resep" class="table table-bordered">
						                <thead>
						                    <tr class="merah">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Kode Resep</th>
						                        <th style="color:#fff; text-align:center;">Tanggal</th>
						                        <th style="color:#fff; text-align:center;">Diminum Selama</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
                    		</div>
                    	</div>
                    </form>

                    <form class="form-horizontal" id="view_resep_tambah" action="" method="post">
                    	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
						<input type="hidden" name="grandtotal_resep" id="grandtotal_resep_txt" value="">
						<h4><i class="fa fa-plus"></i> Tambah Resep</h4>
						<hr>
						<div class="form-group">
							<label class="col-md-2 control-label">Alergi / Obat</label>
							<div class="col-md-5">
								<div class="radio radio-inline radio-success">
	                                <input type="radio" id="inlineRadio1" value="Ya" name="alergi">
	                                <label for="inlineRadio1"> Ya </label>
	                            </div>
	                            <div class="radio radio-inline radio-success">
	                                <input type="radio" id="inlineRadio2" value="Tidak" name="alergi">
	                                <label for="inlineRadio2"> Tidak </label>
	                            </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Kode Resep</label>
							<div class="col-md-5">
								<input type="text" class="form-control" name="kode_resep" id="kode_resep" value="" readonly>
							</div>
						</div>
						<div class="form-group">
	                        <label class="col-md-2 control-label">Obat</label>
	                        <div class="col-md-5">
	                        	<div class="input-group">
	                        		<input type="hidden" name="id_obat" id="id_obat" value="">
	                                <input type="text" class="form-control btn_obat_resep" id="obat_resep" value="" placeholder="klik disini" readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-primary btn_obat_resep" style="cursor:cursor;"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">&nbsp;</label>
	                    	<div class="col-md-10">
	                    		<div class="table-responsive">
						            <table id="tabel_tambah_resep" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Kode Obat</th>
						                        <th style="color:#fff; text-align:center;">Nama Obat</th>
						                        <th style="color:#fff; text-align:center;">Harga</th>
						                        <th style="color:#fff; text-align:center;">Jumlah</th>
						                        <th style="color:#fff; text-align:center;">Total</th>
						                        <th style="color:#fff; text-align:center;">Takaran</th>
						                        <th style="color:#fff; text-align:center;">Aturan Minum</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    
						                </tbody>
						                <tfoot>
						                	<tr class="info">
						                		<td colspan="7" style="text-align: center;"><b>GRANDTOTAL</b></td>
						                		<td><b id="grandtotal_resep">0</b></td>
						                	</tr>
						                </tfoot>
						            </table>
						        </div>
	                    	</div>
	                    </div>
	                    <div class="form-group">
	                    	<label class="col-md-2 control-label">Diminum Selama</label>
	                    	<div class="col-sm-5">
                				<div class="input-group">
                                    <input type="text" class="form-control num_only" name="diminum_selama" id="diminum_selama" value="">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                			</div>
	                    </div>
	                    <hr>
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanResep"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalResep"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="laborat1">
                    <form class="form-horizontal" action="" method="post">
						<div class="form-group">
							<div class="col-md-5">
								<div class="alert alert-success fade in m-b-0">
                                    <h4 style="color: #11862f;">Jika pasien memerlukan CEK LABORAT</h4>
                                    <p style="color: #11862f;">
                                        Silahkan tekan tombol "Cek Laborat" dibawah untuk mengirim Data Pasien<br>
                                        kepada bagian Laborat.
                                    </p>
                                    <p class="m-t-10">
                                        <button type="button" class="btn btn-success" id="btn_kirim_data"><i class="fa fa-send"></i> <b>Cek Laborat</b></button>
                                    </p>
                                </div>
							</div>
						</div>
                    </form>
                </div>
                
                <div role="tabpanel" class="tab-pane fade" id="kondisi_akhir1">
                	<form class="form-horizontal" id="view_kondisi_akhir">
                		<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" id="id_poli_ka" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" id="id_dokter_ka" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" id="id_pasien_ka" value="<?php echo $dt->ID; ?>">
						<input type="hidden" name="asal_rujukan" value="<?php echo $dt->ASAL_RUJUKAN; ?>">
                		<div class="form-group">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="kondisi_akhir" id="kondisi_akhir">
                                    <option value="Pulang">Pulang</option>
                                    <option value="Atas Permintaan Sendiri">Atas Permintaan Sendiri</option>
                                    <option value="Dirujuk">Dirujuk</option>
                                    <option value="Ke Laborat">Ke Laborat</option>
                                    <option value="Pindah Poli">Pindah Poli</option>
                                    <option value="Rawat Inap">Rawat Inap</option>
                                    <option value="ICU">ICU</option>
                                    <option value="Operasi">Operasi</option>
                                    <option value="Meninggal">Meninggal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                        	<label class="col-sm-2 control-label">&nbsp;</label>
                        	<div class="col-sm-4">
                        		<input type="text" name="status_pasien" class="form-control" id="status_pasien" value="" readonly>
                        	</div>
                        </div>

                        <hr>

                        <div id="pindah_rawat_inap">
                        	<h4>Pindah Rawat Inap</h4>
                        	<hr>
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Kelas</label>
		                        <div class="col-md-4">
		                            <select class="form-control select2" name="kelas_kamar" id="kelas_kamar">
		                                <option value="Semua">Semua</option>
		                                <option value="Kelas 1">Kelas 1</option>
		                                <option value="Kelas 2">Kelas 2</option>
		                                <option value="Kelas 3">Kelas 3</option>
		                                <option value="VIP">VIP</option>
		                                <option value="VVIP">VVIP</option>
		                            </select>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Ruang Tujuan</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_ruangan" id="id_ruangan" value="">
		                                <input type="text" class="form-control" id="ruang_tujuan" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_ruangan"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Biaya Kamar</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="biaya" id="biaya" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Kamar & Bed</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_bed" id="id_bed" value="">
		                                <input type="text" class="form-control" id="bed" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_bed"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Nama P. Jawab</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="nama_pjawab" id="nama_pjawab" value="" required="required">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Telepon</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control num_only" name="telepon" id="telepon" value="" maxlength="12" required="required">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Sistem Bayar</label>
		                        <div class="col-md-4">
		                            <select class="form-control select2" name="sistem_bayar">
		                                <option value="Umum">Umum</option>
		                                <option value="BPJS">BPJS Kesehatan</option>
		                                <option value="PJKA">PJKA</option>
		                                <option value="JAMKESDA">JAMKESDA</option>
		                            </select>
		                        </div>
		                    </div>
		                    <hr>
                        </div>

                        <div id="view_icu">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Ruang ICU</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_ruang_icu" id="id_ruang_icu" value="">
		                                <input type="text" class="form-control" id="ruang_icu" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_ruang_icu"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Level</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="level_icu" id="level_icu" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tipe</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tipe_icu" id="tipe_icu" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tarif</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tarif_icu" id="tarif_icu" value="" onkeyup="FormatCurrency(this);">
		                        </div>
		                    </div>
		                    <hr>
                        </div>

                        <div id="view_operasi">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Ruang Operasi</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_ruang_opr" id="id_ruang_opr" value="">
		                                <input type="text" class="form-control" id="ruang_operasi" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_ruang_opr"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tarif</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tarif_operasi" id="tarif_operasi" value="" onkeyup="FormatCurrency(this);">
		                        </div>
		                    </div>
		                    <hr>
                        </div>

                        <div id="view_meninggal">
                        	<div class="form-group">
		                        <label class="col-md-2 control-label">Kamar Jenazah</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_kamar_jenazah" id="id_kamar_jenazah" value="">
		                                <input type="text" class="form-control" id="kamar_jenazah" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-danger btn_kamar_jenazah"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Tarif</label>
		                        <div class="col-md-4">
		                            <input type="text" class="form-control" name="tarif_kamar_jenazah" id="tarif_kamar_jenazah" value="" readonly>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-2 control-label">Lemari Jenazah</label>
		                        <div class="col-md-4">
		                            <div class="input-group">
		                                <input type="hidden" name="id_lemari_jenazah" id="id_lemari_jenazah" value="">
		                                <input type="text" class="form-control" id="lemari_jenazah" value="" readonly>
		                                <span class="input-group-btn">
		                                    <button type="button" class="btn btn-primary btn_lemari_jenazah"><i class="fa fa-search"></i></button>
		                                </span>
		                            </div>
		                        </div>
		                    </div>
		                    <hr>
                        </div>
                       
	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanKA"><i class="fa fa-save"></i> <b>Simpan</b></button>
	                        <button type="button" class="btn btn-danger" id="batalKA"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
                	</form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="surat_dokter1">
	                <form class="form-horizontal" method="post" id="view_surat_dokter">
	                	<input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
						<input type="hidden" name="id_poli" value="<?php echo $dt->ID_POLI; ?>">
						<input type="hidden" name="id_dokter" value="<?php echo $dt->ID_DOKTER; ?>">
						<input type="hidden" name="id_pasien" id="id_pasien_sd" value="<?php echo $dt->ID; ?>">
	                	<div class="form-group">
	                        <label class="col-md-2 control-label">Nama</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="nama_sd" id="nama_sd" value="<?php echo $dt->NAMA_PASIEN; ?>" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
                            <label class="col-md-2 control-label">Umur</label>
                            <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="text" class="form-control" name="umur_sd" id="umur_sd" value="<?php echo $dt->UMUR; ?>" readonly>
	                                <span class="input-group-btn">
	                                	<button class="btn btn-primary" type="button" style="cursor:default;">Tahun</button>
	                                </span>
	                            </div>
                            </div>
                        </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Pekerjaan</label>
	                        <div class="col-md-4">
	                        	<?php
	                        		$dt->PEKERJAAN = $dt->PEKERJAAN==null?"-":$dt->PEKERJAAN;
	                        	?>
	                            <input type="text" class="form-control" name="pekerjaan_sd" id="pekerjaan_sd" value="<?php echo $dt->PEKERJAAN; ?>" readonly>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Alamat</label>
	                        <div class="col-md-4">
	                            <textarea class="form-control" name="alamat_sd" id="alamat_sd" rows="3" readonly><?php echo $dt->ALAMAT; ?></textarea>
	                        </div>
	                    </div>
	                    <div class="form-group">
                            <label class="col-md-2 control-label">Waktu Istirahat</label>
                            <div class="col-md-4">
	                            <div class="input-group">
	                                <input type="text" class="form-control num_only" name="waktu_sd" id="waktu_sd" value="" required="required">
	                                <span class="input-group-btn">
	                                	<button class="btn btn-primary" type="button" style="cursor:default;">Hari</button>
	                                </span>
	                            </div>
                            </div>
                        </div>
                        <div class="form-group">
	                        <label class="col-md-2 control-label">Mulai Tanggal</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="mulai_tgl_sd" id="mulai_tgl_sd" value="" data-mask="99-99-9999" required="required">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Sampai Tanggal</label>
	                        <div class="col-md-4">
	                            <input type="text" class="form-control" name="sampai_tgl_sd" id="sampai_tgl_sd" value="" data-mask="99-99-9999" required="required">
	                        </div>
	                    </div>
	                    
	                    <hr>

	                    <center>
	                    	<button type="button" class="btn btn-success" id="simpanSD"><i class="fa fa-save"></i> <b>Simpan & Cetak</b></button>
	                        <button type="button" class="btn btn-danger" id="batalSD"><i class="fa fa-times"></i> <b>Batal</b></button>
	                    </center>
	                </form>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="data_surat_dokter1">
                	<form class="form-horizontal" method="post" target="_blank" action="<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/surat_dokter_ada">
                		<input type="hidden" name="id_pasien" id="id_pasien_dt_sd" value="<?php echo $dt->ID; ?>">
                		<input type="hidden" name="id_poli" id="id_poli_dt_sd" value="<?php echo $dt->ID_POLI; ?>">
	                	<div class="form-group">
	                		<div class="col-md-4">
			                	<div class="table-responsive">
			                        <table class="table m-0" id="tabel_data_surat_dokter">
			                            <tbody>
			                                
			                            </tbody>
			                        </table>
			                    </div>
	                		</div>
	                	</div>
	                    <hr>

	                    <center>
	                    	<button type="submit" class="btn btn-danger" id="btn_cetak"><i class="fa fa-print"></i> <b>Cetak</b></button>
	                    </center>
                	</form>
                </div>
            </div>
		</div>
		<div class="row">
			<form class="form-horizontal">
				<div class="form-group">&nbsp;</div>
				<div class="form-group">
					<div class="col-md-4">
						<button class="btn btn-purple btn-block m-b-5" type="button" id="btn_kembali">
							<i class="fa fa-arrow-circle-left"></i>&nbsp;<b>Kembali</b>
						</button>	
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- TINDAKAN -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1" id="popup_tindakan" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Tindakan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_tindakan" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_tindakan">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Tindakan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_tindakan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- // -->

<button id="popup_hapus" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
            	<form action="<?php echo $url_hapus; ?>" method="post">
            		<input type="hidden" name="id_hapus" id="id_hapus" value="">
            		<input type="hidden" name="id_pelayanan" value="<?php echo $id; ?>">
            		<input type="hidden" name="ket_hapus" id="ket_hapus" value="">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
	                <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- LABORAT -->

<button id="popup_lab_baru" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-sm" style="display: none;">Small modal</button>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btn_tutup_lab">×</button>
                <h4 class="modal-title" id="mySmallModalLabel">Konfirmasi</h4>
            </div>
            <div class="modal-body">
            	<input type="hidden" id="id_pasien_lab" value="<?php echo $dt->ID; ?>">
            	<p>Kirim data ke Laborat?</p>
            	<hr>
            	<center>
	            	<button id="simpanLab" class="btn btn-success">Ya</button>
	            	<button id="batalLab" class="btn btn-danger">Tidak</button>
            	</center>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- DIAGNOSA -->
<button id="popup_penyakit_dg" class="btn btn-primary" data-toggle="modal" data-target="#myModal2_hasil_dg" style="display:none;">Standard Modal</button>
<div id="myModal2_hasil_dg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Penyakit</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_penyakit_dg" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_penyakit_dg">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Penyakit</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_penyakit_dg">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_dg" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modalDg" style="display:none;">Custom width Modal</button>
<div id="custom-width-modalDg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_dg"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_dg">
            		<input type="hidden" name="id_hapus_dg" id="id_hapus_dg" value="">
            		<input type="hidden" name="id_pelayanan_dg" value="<?php echo $id; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_dg">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_dg">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- RESEP -->
<button id="popup_resep" class="btn btn-primary" data-toggle="modal" data-target="#myModal2_resep" style="display:none;">Standard Modal</button>
<div id="myModal2_resep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_resep" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_resep">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Nama Obat</th>
		                            <th style="text-align:center; color: #fff;">Harga</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_resep">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_det_resep" class="btn btn-primary" data-toggle="modal" data-target="#myModal3_resep" style="display:none;">Standard Modal</button>
<div id="myModal3_resep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Detail Resep</h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-bordered table-hover" id="tb_det_resep">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Nama Obat</th>
		                            <th style="text-align:center; color: #fff;">Harga</th>
		                            <th style="text-align:center; color: #fff;">Takaran</th>
		                            <th style="text-align:center; color: #fff;">Aturan Minum</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_det_resep">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_resep" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modalResep" style="display:none;">Custom width Modal</button>
<div id="custom-width-modalResep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_resep"></p>
            </div>
            <div class="modal-footer">
            	<form action="" method="post" id="form_hapus_resep">
            		<input type="hidden" name="id_hapus_resep" id="id_hapus_resep" value="">
            		<input type="hidden" name="id_pelayanan_resep" value="<?php echo $id; ?>">
	                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_resep">Tidak</button>
	                <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_resep">Ya</button>
            	</form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- KONDISI AKHIR -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_ruangan" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_kamar" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_kamar">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Kamar</th>
                                    <th style="text-align:center; color: #fff;">Nama Kamar</th>
                                    <th style="text-align:center; color: #fff;">Kategori</th>
                                    <th style="text-align:center; color: #fff;">Kelas</th>
                                    <th style="text-align:center; color: #fff;">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kamar">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_bed" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Bed Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_bed" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_bed">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Bed</th>
                                    <th style="text-align:center; color: #fff;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_bed">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" id="popup_ruang_icu" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Ruang ICU</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_ruang_icu" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_ruang_icu">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Ruang</th>
                                    <th style="text-align:center; color: #fff;">Nama Ruang</th>
                                    <th style="text-align:center; color: #fff;">Level</th>
                                    <th style="text-align:center; color: #fff;">Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_ruang_icu">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal4" id="popup_ruang_operasi" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Ruang Operasi</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_ruang_operasi" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_ruang_operasi">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Ruang</th>
                                    <th style="text-align:center; color: #fff;">Nama Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_ruang_operasi">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal6" id="popup_kamar_jenazah" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Kamar Jenazah</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_kamar_jenazah" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_kamar_jenazah">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Kamar</th>
                                    <th style="text-align:center; color: #fff;">Nama Kamar</th>
                                    <th style="text-align:center; color: #fff;">Tarif</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kamar_jenazah">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal7" id="popup_lemari_jenazah" style="display:none;">Standard Modal</button>
<div id="myModal7" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Lemari Jenazah</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_lemari_jenazah" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_lemari_jenazah">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nomor Lemari</th>
                                    <th style="text-align:center; color: #fff;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_lemari_jenazah">Tutup</button>
            </div>
        </div>
    </div>
</div>