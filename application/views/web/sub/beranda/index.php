<div class="row mt-5 pt-1">
  <div class="col-md-12 text-center">
    <h4>List Harga</h4>
    <p>
      <script type='text/javascript'>
      var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
      var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];
      var date = new Date();
      var day = date.getDate();
      var month = date.getMonth();
      var thisDay = date.getDay(),
      thisDay = myDays[thisDay];
      var yy = date.getYear();
      var year = (yy < 1000) ? yy + 1900 : yy;
      document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
      </script>
    </p>
  </div>
</div>

<?php if (view_mobile()): ?>
  <div class="row">
    <?php view('web/sub/beranda/cari'); ?>
  </div>
<?php endif; ?>

<div class="row pt-1">
  <?php view('web/sub/beranda/item'); ?>
</div>
