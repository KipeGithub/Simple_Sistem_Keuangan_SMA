<div class="col-xs-12">
	<div class="box box-primary" style="border-radius: 8px;">
        <div class="box-header with-border" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <h3 class="box-title" style="font-weight: 700; color: #0F294A;">
                <i class="fa fa-money"></i> Data Kas Masuk Lainnya (Di Luar SPP)
            </h3>
            <div class="box-tools pull-right">
            	<a href="#" onclick="Tambah()" class="btn btn-primary btn-sm" style="background-color: #0F294A; border-color: #0F294A; border-radius: 4px;">
                    <i class="fa fa-plus-circle"></i> Tambah Pemasukan Baru
                </a>
            </div>
        </div>
	    <div class="box-body">
	    	<div class="table-responsive">    	
		        <table id="list-data" class="table table-bordered table-striped table-hover tabel" style="width: 100%;">
		            <thead style="background: #0F294A; color: #fff; font-size: 12px;">
			            <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th style="width: 100px;">Tanggal</th>
                            <th style="width: 150px;">Jenis Pemasukan</th>
                            <th>Keterangan</th>
                            <th style="width: 160px;" class="text-right">Nominal Pemasukan</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
			            </tr>
		            </thead>
		            <tbody>
		            </tbody>
		        </table>
	       	</div>
	    </div>
    </div>
</div>

<!-- Modal Tambah / Edit -->
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="background-color: #0F294A; color: #ffffff; border-radius: 8px 8px 0 0;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.9;">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" style="font-weight: 700;"></h4>
            </div>
<?= form_open('','role = "form" id = "form"')?>
            <div class="modal-body">
            	<input type="hidden" name="id" value="">
            	<div class="form-group">
            		<label class="control-label">Nominal Pemasukan (Rp) <span class="text-danger">*</span></label>
            		<div><input type="text" required="" placeholder="Contoh: 1500000" onkeypress="return Angka(this)" autocomplete="off" name="nominal" class="form-control" style="border-radius: 6px;"></div>
            	</div>
                <div class="form-group">
                    <label class="control-label">Jenis Pemasukan <span class="text-danger">*</span></label>
                    <select name="jenis" required="" data-placeholder="-- Pilih Jenis --" class="form-control" style="border-radius: 6px;">
                        <option value="">-- Pilih Jenis Pemasukan --</option>
                        <option value="Dana BOS">Dana BOS</option>
                        <option value="Dana BOP">Dana BOP</option>
                        <option value="Sewa Kantin/Koperasi">Sewa Kantin / Koperasi</option>
                        <option value="Sumbangan / Donasi">Sumbangan / Donasi</option>
                        <option value="Sewa Aula / Gedung">Sewa Aula / Gedung</option>
                        <option value="Penjualan Produk Praktik SMK">Penjualan Produk Praktik SMK</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Keterangan / Uraian <span class="text-danger">*</span></label>
                    <div><textarea required="" placeholder="Rincian sumber atau peruntukan penerimaan kas..." autocomplete="off" name="keterangan" rows="3" class="form-control" style="border-radius: 6px;"></textarea></div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 8px 8px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" id="simpan" class="btn btn-primary" style="background-color: #0F294A; border-color: #0F294A;">
                    <i class="fa fa-save"></i> Simpan Data
                </button>
            </div>
<?= form_close()?>
        </div>
    </div>
</div>

<script type="text/javascript">
	var label;
	var table;
	$(document).ready(function(){
		$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings){
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

       table = $("#list-data").DataTable({
            initComplete: function() {
                var api = this.api();
                $('#list-data_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        api.search(this.value).draw();
                    });
            },
            oLanguage: {
                sSearch       :"<i class='fa fa-search fa-fw'></i> Cari: ",
                sLengthMenu   :"Tampilkan _MENU_ data",
                sInfo         :"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoFiltered :"(disaring dari _MAX_ total data)", 
                sZeroRecords  :"Data tidak ditemukan", 
                sEmptyTable   :"Data kas masuk lainnya masih kosong.", 
                sInfoEmpty    :"Menampilkan 0 sampai 0 data",
                sProcessing   :"Sedang memproses...", 
                oPaginate: {
                    sPrevious :"Sebelumnya",
                    sNext     :"Selanjutnya",
                    sFirst    :"Pertama",
                    sLast     :"Terakhir"
                }
            },
            processing: true,
            serverSide: true,
            ajax: {
                "url": "<?= base_url('Lainnya/getData') ?>",
                "type": "POST"
            },
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-center"
                },
                {"data": "Tgl"},
                {
                    "data": "jenis",
                    "render": function(data, type, row) {
                        return '<span class="label label-info" style="font-size: 11px;">' + (data || 'Lainnya') + '</span>';
                    }
                },
                {
                    "data": "keterangan",
                    "render": function(data, type, row) {
                        return data ? '<strong>' + data + '</strong>' : '<em class="text-muted">-</em>';
                    }
                },
                {
                    "data": "nominal",
                    "className": "text-right",
                    "render": function(data, type, row) {
                        var num = parseFloat(data) || 0;
                        return '<strong style="color: #0D6E52; font-size: 13.5px;">Rp ' + num.toLocaleString('id-ID') + '</strong>';
                    }
                },
                {
                    "data": "id",
                    "orderable": false,
                    "searchable": false,
                    "className": "text-center",
                    "render": function(data, type, row) {
                        return '<button type="button" class="btn btn-xs btn-default" onclick="Edit(' + data + ')" title="Edit Data" style="margin-right: 4px;"><i class="fa fa-edit text-blue"></i></button>' +
                               '<button type="button" class="btn btn-xs btn-default" onclick="Hapus(' + data + ')" title="Hapus Data"><i class="fa fa-trash text-red"></i></button>';
                    }
                }
            ],
            order: [[0, 'desc']],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });

		$('#form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('has-error');
				$(e).remove();
			},
			errorPlacement: function (error, element) {
				if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
					var controls = element.closest('div[class*="col-"]');
					if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
					else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
				}
				else if(element.is('.select2')) {
					error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
				}
				else if(element.is('.chosen-select')) {
					error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
				}
				else error.insertAfter(element.parent());
			},
			submitHandler: function (form) {
				$('#simpan').text('Menyimpan...');
				$('#simpan').attr('disabled', true);
				var url;
				if(label == 'simpan'){
					url = '<?= base_url('Lainnya/Simpan') ?>';
				} else {
					url = '<?= base_url('Lainnya/Simpan') ?>';
				}
				var formData = new FormData($('#form')[0]);
				$.ajax({
					url: url,
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					dataType: "JSON",
					success: function (data) {
						if(data.status) {
							$('#modal-form').modal('hide');
							ReloadTable();
                            swal("Berhasil!", "Data kas masuk lainnya berhasil disimpan.", "success");
						} else {
							swal("Peringatan!", "Gagal menyimpan data.", "warning");
						}
						$('#simpan').text('Simpan Data');
						$('#simpan').attr('disabled', false);
					},
					error: function (jqXHR, textStatus, errorThrown) {
						swal("Gagal!", "Terjadi kesalahan pada sistem.", "error");
						$('#simpan').text('Simpan Data');
						$('#simpan').attr('disabled', false);
					}
				});
			}
		});
	});

	function ReloadTable(){
		table.ajax.reload(null, false);
	}

	function Tambah(){
		label = 'simpan';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty();
		$('input[name="id"]').val('');
		$('#modal-form').modal('show');
		$('.modal-title').html('<i class="fa fa-plus-circle"></i> Tambah Data Pemasukan Kas Lainnya');
	}

	function Edit(id){
		label = 'ubah';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty();

		$.ajax({
			url: "<?= base_url('Lainnya/edit/') ?>" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data){
				$('[name="id"]').val(data.id);
				$('[name="nominal"]').val(data.nominal);
				$('[name="jenis"]').val(data.jenis);
				$('[name="keterangan"]').val(data.keterangan);
				$('#modal-form').modal('show');
				$('.modal-title').html('<i class="fa fa-edit"></i> Edit Data Pemasukan Kas Lainnya');
			},
			error: function (jqXHR, textStatus, errorThrown){
				swal("Error!", "Gagal mengambil data dari server", "error");
			}
		});
	}

	function Hapus(id){
		swal({
			title: "Hapus Pemasukan Kas Ini?",
			text: "Data yang dihapus akan otomatis memotong catatan rekap saldo kas masuk!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Ya, Hapus!",
			cancelButtonText: "Batal",
			closeOnConfirm: false
		}, function(){
			$.ajax({
				url : "<?= base_url('Lainnya/Hapus/') ?>" + id,
				type: "POST",
				dataType: "JSON",
				success: function(data){
					ReloadTable();
					swal("Terhapus!", "Data berhasil dihapus dari sistem.", "success");
				},
				error: function (jqXHR, textStatus, errorThrown){
					swal("Gagal!", "Terjadi kesalahan saat menghapus data.", "error");
				}
			});
		});
	}
</script>
