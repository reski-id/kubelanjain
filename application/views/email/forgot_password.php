<?php
$time = tgl_format(tgl_now(), 'Y-m-d H:i:s', '+1 days');
$id   = encode("$id_user | $time", '64');
$url  = email('web_default')."/auth/reset-password/$id";
?>
<b>Yth Bapak/Ibu <?= get_field('user_biodata',array('id_user'=>$id_user))['nama_lengkap']; ?></b>
<br><br>

Pada tanggal <?= tgl_id(tgl_now(),'d-m-Y'); ?> pada pukul <?php echo date('H:i:s'); ?> sistem kami menerima permintaan reset password untuk akun Anda. Untuk mereset password Anda, silahkan klik tautan berikut ini:
<br>
<b><a href="<?= $url; ?>" target="_blank">link reset password</a></b>
<br>
<br>
Jika Anda tidak dapat mengakses link diatas secara langsung, silahkan salin tautan berikut ini dan copy kan ke browser anda:
<br>
<b><a href="<?= $url; ?>" target="_blank"><?= $url; ?></a></b>
<br><br>

Link di atas hanya akan berlaku selama <b>1x24</b> jam setelah email ini kami kirimkan.
<br>
Jika anda tidak merasa melakukan permintaan untuk mereset password anda, maka abaikan email ini.
<br>
<br>
Hormat Kami, <br>
<?= email('nama_pengirim'); ?> <br>
<a href="<?= web('website'); ?>" target="_blank"><?= web('website'); ?></a>

<br>
<br>
Email ini dikirimkan secara otomatis oleh sistem, kami tidak melakukan pengecakan email yang dikirimkan ke email ini. Jika ada pertanyaan, silahkan hubungi kami <b><?= web('no_hp'); ?></b>. Terimakasih!
