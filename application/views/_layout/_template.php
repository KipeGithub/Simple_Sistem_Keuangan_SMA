<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="description" content=" " /> 
        <meta name="keywords" content=" " />
        
        <link rel="shorcut icon" type="text/css" href="<?php echo base_url('assets/dist/img/tut_wuri_handayani.png')?>">

        <!-- File Css -->
        <?php echo @$_css; ?>
        <script src="<?php echo base_url('assets/')?>bower_components/jquery/dist/jquery.min.js"></script>
    </head>
    <SCRIPT language=Javascript>
        function Angka(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
    </SCRIPT>

<?php 
  $masuk = $this->db->get_where('users',['id'=> $this->session->userdata('id')])->row_array();
  $roleName = 'Wali Murid';
  $roleBadge = 'label-default';
  if ($masuk['role'] == 1) {
    $roleName = 'Admin TU';
    $roleBadge = 'label-primary';
  } elseif ($masuk['role'] == 2) {
    $roleName = 'Bendahara';
    $roleBadge = 'label-success';
  } elseif ($masuk['role'] == 3) {
    $roleName = 'Kepala Sekolah';
    $roleBadge = 'label-warning';
  }
?>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                 <!-- Header -->
                <?php echo @$_header; ?>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <div class="user-panel" style="padding: 12px 10px; background: rgba(0,0,0,0.18); border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
                        <div class="pull-left image" style="flex-shrink: 0;">
                          <img src="<?php echo base_url('assets/dist/img/').($masuk['gambar'] ?: 'user.png') ?>" class="img-circle" alt="User Image" style="width: 40px; height: 40px; border: 2px solid rgba(255,255,255,0.25);">
                        </div>
                        <div class="info" style="position: static; padding: 0; line-height: 1.25; overflow: hidden; width: calc(100% - 50px);">
                          <p style="margin: 0 0 3px 0; font-weight: 700; font-size: 12.5px; color: #ffffff; white-space: normal; word-break: break-word; line-height: 1.3;">
                            <?= htmlspecialchars($masuk['name'] ?? 'Pengguna') ?>
                          </p>
                          <span class="label <?= $roleBadge ?>" style="font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px; display: inline-block;">
                            <i class="fa fa-circle text-green" style="font-size: 7px; vertical-align: middle;"></i> <?= $roleName ?>
                          </span>
                        </div>
                    </div>
                    <!-- Sidebar -->
                    <?php echo @$_sidebar; ?>
                </section>
            </aside>
            <div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
                <section class="content-header" style="padding: 18px 25px 10px 25px;">
                    <h1 style="font-size: 20px; font-weight: 700; color: #0F294A; margin: 0 0 5px 0;"> <i class="<?php echo $icon;?>"></i> <?php echo $judul; ?></h1>
                    <?php echo $this->breadcrumb->output(); ?>
                </section>
                <section class="content" style="padding: 10px 25px 25px 25px;">
                    <div class="content-container-fluid" style="width: 100%;">
                        <?php echo @$_content; ?>        
                    </div>      
                </section>
            </div>
            <!-- Footer -->
            <?php echo @$_footer;?>
          <div class="control-sidebar-bg"></div>
      </div>
      <!-- File Js -->
      <?php echo @$_js; ?>

    <script>
        $(document).ready(function () {

            $('.select2').css('width','100%').select2({allowClear:false})
                .on('change', function(){
                    $(this).closest('form').validate().element($(this));
            });

            $('.datepicker').datepicker({
                autoclose:true,format: "yyyy-mm-dd",
                todayHighlight: true,})
                .on('changeDate', function(ev) {
                    $(this).closest('form').validate().element($(this));
            });

        $('.tabel').DataTable({
            "oLanguage": {
                "sSearch"       :"<i class='fa fa-search fa-fw'></i> Cari: ",
                "sLengthMenu"   :"Tampilkan _MENU_ data",
                "sInfo"         :"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "sInfoFiltered" :"(disaring dari _MAX_ total data)", 
                "sZeroRecords"  :"Data Pencarian kosong", 
                "sEmptyTable"   :"Data kosong", 
                "sInfoEmpty"    :"Menampilkan 0 sampai 0 data",
                "sProcessing"   :"Sedang memproses...", 
                "oPaginate": {
                    "sPrevious" :"Sebelumnya",
                    "sNext"     :"Selanjutnya",
                    "sFirst"    :"Pertama",
                    "sLast"     :"Terakhir"
                }
            },
            "processing": true
        });

            $('.sidebar-menu').tree();

            $('input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass   : 'iradio_minimal-blue'
            });

                            $(document).one('ajaxloadstart.page', function(e) {
                    //in ajax mode, remove remaining elements before leaving page
                    $('[class*=select2]').remove();
                });

        });
        </script>
    </body>
</html>
