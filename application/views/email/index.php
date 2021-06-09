<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $judul; ?></title>
    <style>
      th { text-align: left;}
    </style>
  </head>
  <body>

    <?php $this->load->view("email/$view"); ?>

  </body>
</html>
