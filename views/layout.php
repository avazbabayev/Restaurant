<html lang="de">
<head>
    <title><?php echo $title;?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <link rel="stylesheet" href="/lib/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="/lib/js/nav.js"></script>
  </html>
<body style="padding-left: 1%">

<div class="topnav" id="myTopnav">
    <a href="/" <?php echo ($_SERVER['REQUEST_URI'] =='/')?'class="active"':''; ?> >
        HOME

    </a>
    <a href="/zufalls/load_xlsx" <?php echo ($_SERVER['REQUEST_URI'] =='/zufalls/load_xlsx')?'class="active"':''; ?>  >Daten hochladen</a>
    <a href="/zufalls/index" <?php echo ($_SERVER['REQUEST_URI'] =='/zufalls/index')?'class="active"':''; ?>  >Zufallsgenerator</a>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
    </a>
</div>


<br>
<h3 style="margin-top:0;"><?php echo $title; ?></h3>

<br>
<?php
echo $content;
?>

</body>
</html>