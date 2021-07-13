<?php
use yii\helpers\Html;
use yii\helpers\Url;
//print_r($dash);
?>
<style>
.centered {
  text-align: center;
  font-size: 0;
}
.centered > div {
  float: none;
  display: inline-block;
  text-align: left;
  font-size: 13px;
}
.middle {
  transition: .5s ease;
  opacity: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}

.opacity-img:hover .image {
  opacity: 0.3;
}

.opacity-img:hover .middle {
  opacity: 1;
}

.text {
  background-color: rgb(0, 82, 138, 0.8);
  color: white;
  font-size: 16px;
  padding: 16px 32px;
}
</style>
<!DOCTYPE html>
<html lang="en">
<header>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="menu.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</header>
<div class="container-fluid">
    <div class="row centered ">
      <div class="col-md-5">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1 class="font-weight-light text-center text-lg-left mt-4 mb-0 text-uppercase"><?=$dash[0]['dash_title']?></h1>
            <hr class="mt-2 mb-5">
            <div class="text-center">
            <?php foreach ($modules as $item => $values) { ?>
              <?php foreach($dash_items as $key => $ditem){
                        if(($ditem['dash_id'] == $values->dash_id)  && ($ditem['dite_estado'] == '1' && $ditem['dite_estado_logico'] == '1') ) :
              ?>
              <?php if(empty($ditem->dite_detail) && $ditem['dash_id'] == 1)  { ?>
              <div class="col-lg-6 col-md-6 col-6 opacity-img">
                <a href="<?= Url::base() . $ditem->dite_link?>" target="_blank" class="d-block mb-4 h-100">
                  <img class="img-fluid img-thumbnail image" src="/asgard/<?= $ditem->dite_ruta_banner?>" width="250" height="250" alt="">
                  <div class="middle">
                    <div class="text"><?= $ditem->dite_title?></div>
                  </div>
                </a>
              </div>
              <?php } if($ditem->dite_detail == 1 && $ditem['dash_id'] == 1) { ?>
                <div class="col-lg-6 col-md-6 col-6 opacity-img">
                <a href="<?= (isset($ditem->dite_link) && $ditem->dite_link != "")?( $ditem->dite_link):"javascript:" ?>" target="_blank" class="d-block mb-4 h-100">
                  <img class="img-fluid img-thumbnail image" src="/asgard/<?= $ditem->dite_ruta_banner?>" width="250" height="250" alt="">
                  <div class="middle">
                    <div class="text"><?= $ditem->dite_title ?></div>
                  </div>
                </a>
              </div>
              <?php } ?>
              <?php endif; } ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1 class="font-weight-light text-center text-lg-left mt-4 mb-0 text-uppercase"><?=$dash[4]['dash_title']?></h1>
            <hr class="mt-2 mb-5">
            <div class="text-center">
            <?php foreach ($modules as $item => $values) { ?>
              <?php foreach($dash_items as $key => $ditem){
                        if(($ditem['dash_id'] == $values->dash_id)  && ($ditem['dite_estado'] == '1' && $ditem['dite_estado_logico'] == '1') ) :
              ?>
              <?php if(empty($ditem->dite_detail) && $ditem['dash_id'] == 5)  { ?>
              <div class="col-lg-6 col-md-6 col-6 opacity-img">
                <a href="<?= Url::base() . $ditem->dite_link?>" target="_blank" class="d-block mb-4 h-100">
                  <img class="img-fluid img-thumbnail image" src="/asgard/<?= $ditem->dite_ruta_banner?>" width="250" height="250" alt="">
                  <div class="middle">
                    <div class="text"><?= $ditem->dite_title?></div>
                  </div>
                </a>
              </div>
              <?php } if($ditem->dite_detail == 1 && $ditem['dash_id'] == 5) { ?>
                <div class="col-lg-6 col-md-6 col-6 opacity-img">
                <a href="<?= (isset($ditem->dite_link) && $ditem->dite_link != "")?( $ditem->dite_link):"javascript:" ?>" target="_blank" class="d-block mb-4 h-100">
                  <img class="img-fluid img-thumbnail image" src="/asgard/<?= $ditem->dite_ruta_banner?>" width="250" height="250" alt="">
                  <div class="middle">
                    <div class="text"><?= $ditem->dite_title ?></div>
                  </div>
                </a>
              </div>
              <?php } ?>
              <?php endif; } ?>
              <?php } ?>
          </div>
        </div>
      </div>
    </div>
</div>
</html>