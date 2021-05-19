<?php 
    use \CodeItNow\BarcodeBundle\Utils\QrCode;

    $qrCode = new QrCode();
    $qrCode
        ->setText($code)
        ->setSize(95)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
        //->setLabel(Yii::t("formulario", "Scan QR Code"))
        ->setLabelFontSize(16)
        ->setImageType(QrCode::IMAGE_TYPE_PNG);
    
?>

<div class="title">
    <div class="tcontent">
        <div class="titleContent"><?= $title ?></div>
    </div>
</div>
<div class="name">
    <div class="ncontent">
        <div class="<?= (strlen($name) > 27)?"nameContent2":"nameContent" ?>">
            <?= $name ?>
        </div>
    </div>
</div>
<div class="body">
    <div class="bcontent">
        <div class="bodyContent"><?= $body ?></div> 
        <div class="bodyDates"><?= $dates ?></div>                
    </div>
    
</div>
<div class="qr">
    <?=  '<img class="imgQR" src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />'; ?>
</div>