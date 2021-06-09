<script type="text/javascript">
$(document).ready(function () {
  if ($('.tgl_dari').length!=0) {
    $('.tgl_dari').daterangepicker({
      locale: {
        format: 'DD-MM-YYYY',
        customRangeLabel: "Custom",
        "daysOfWeek" : [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
        "monthNames" : [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ]
      },
      singleDatePicker: true,
      showDropdowns: true,
      minYear: 2020,
      maxYear: <?= date('Y'); ?>
    });
  }


  if ($('.tgl_sampai').length!=0) {
    $('.tgl_sampai').daterangepicker({
      locale: {
        format: 'DD-MM-YYYY',
        customRangeLabel: "Custom",
        "daysOfWeek" : [ 'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab' ],
        "monthNames" : [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ]
      },
      singleDatePicker: true,
      showDropdowns: true,
      minYear: 2020,
      maxYear: <?= date('Y'); ?>
    });
  }
});
</script>
