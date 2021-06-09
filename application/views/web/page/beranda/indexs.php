<style>
.kotak {
    color: #000;
    /* background-color: aqua; */
    width: 100%;
    height: 60%;
    margin-bottom: 25pc;
}

.text_depan {
    color: black;
    font-size: 20px;
    text-align: center;
    margin-top: 30%;
}
.text_pesan {
    color: black;
    font-size: 18px;
    text-align: center;
    margin-top: 22%;
}
</style>

<?php $url_pdf = 'web/cek_pdf'; ?>
<div class="row mt-4 pt-1">
  <div class="col-md-6 ">
    <?php view('web/page/beranda/slide'); ?>
  </div>
  
  <div class="col-md-6">
    <div class="text_depan">
    <p>Kubelanjain memberikan solusi kebutuhan <br> bahan pokok dengan <br> harga yang pas dan produk berkualitas <br> untuk semua konsumen</p>
    </div>
  </div>          
</div>

<div class="row mb-2 mt-2">
<img src="img/slide/slide.jpg" alt="slide" srcset="">
<!-- <div class="kotak" id="box1">
        
                      
</div> -->
</div>

<!-- link gmap -->
<div class="row">
  <div class="col-md-12 m-0 p-0">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d498.62758701927527!2d104.0044286!3d1.1456909!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwMDgnNDQuOCJOIDEwNMKwMDAnMTYuNyJF!5e0!3m2!1sid!2sid!4v1614738821377!5m2!1sid!2sid" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6 mt-1">
      <p class="text_pesan">
      Tinggalkan pesan Anda !
      </p>
  </div>
  <div class="col-md-6">
      
<form action="pesan/simpan/pesan" method="post" data-parsley-validate='true' enctype="multipart/form-data">

<!-- <form id="sync_form" action="javascript:simpan('sync_form','https://localhost/pasar-backend/pesan/simpan/pesan','','swal','3','','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data"> -->

<!-- <form id="sync_form" method="post" data-parsley-validate='true' enctype="multipart/form-data"> -->

<!-- <form name="frm" method="POST" action=""> -->
  <div id="pesannya"></div>
  <div class="form-group">
    <label for="nama">Nama</label>
    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama"  required>
  </div>

  
  <div class="form-group">
    <label for="no_hp">No Hp</label>
    <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="08.." minlength="10" onkeydown="return ( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46) )" required>
  </div>

  
   <?php
   $datanya[] = array('type'=>'textarea','name'=>'pesan','id'=>'pesan','nama'=>'Pesan','icon'=>'label','html'=>'required style="text-transform: uppercase;"', 'value'=>'');
 

  data_formnya($datanya);

  ?>
  <!-- <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button> -->
  <button class="btn btn-primary glow" name="simpan" id="simpan"> <span>SIMPAN</span> </button>
</form>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script>

$("#simpan").click(function(e) {
  e.preventDefault();
  var nama = $("#nama").val(); 
  var no_hp = $("#no_hp").val();
  var pesan = $("#pesan").val();

  var dataString = 'nama='+nama+'&no_hp='+no_hp;

  $.ajax({
    type:'POST',
    data:dataString,
    url:('<?php $urlnya;?>pesan/simpan/pesan','','swal','3','','1'),
    success:function(data) {
      alert('save');
    }
  });
});
    ////////
    // function simpan() {
    //   simpan('','<?php $urlnya;?>pesan/simpan/pesan','','swal','3','','1');
    // }
  </script>
  </div>
</div>
<br>
