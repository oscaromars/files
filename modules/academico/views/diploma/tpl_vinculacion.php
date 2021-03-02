<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        
        body {
            line-height: 1;
            width: 1122px;
            height: 300px;
            background-image: url('data:image/png;base64,<?= base64_encode(file_get_contents(__DIR__ . "/img/dip_vinculacion.png")) ?>');
            background-repeat: no-repeat;
        }
        
        html,
        body,
        div {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        
        #main {
            font-family: Arial, sans-serif;
        }
        
        #container {
            height: 100%;
            position: relative;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .clear {
            clear: both;
        }
        
        .left {
            float: left;
        }
        
        .col1 {
            width: 322px;
            height: 826px;
            position: relative;
        }
        
        .col2 {
            width: 800px;
            height: 826px;
            position: relative;
        }
        
        .title {
            height: 150px;
            position: relative;
            line-height: 30px;
        }
        
        .name {
            height: 20px;
            position: relative;
        }
        
        .body {
            height: 50px;
            position: relative;
        }

        .tcontent, .ncontent, .bcontent {
            margin-right: 20px;
            margin-left: 20px;
        }
        
        .titleContent {
            text-align: center;
            /*width: 800px;*/
            font-family: gothambold;
            font-size: 20px;
            color: #575756FF;
            padding-top: 240px;
        }
        
        .nameContent {
            text-align: center;
            /*width: 800px;*/
            font-family: blacksword;
            font-size: 56px;
            color: #00548bff;
            padding-top: 55px;
        }

        .nameContent2 {
            text-align: center;
            /*width: 800px;*/
            font-family: blacksword;
            font-size: 48px; /*56px*/
            color: #00548bff;
            padding-top: 60px; /*40px*/
        }
        
        .bodyContent {
            text-align: center;
            /*width: 800px;*/
            font-family: gothambook;
            font-size: 17px;
            color: #575756ff;
            line-height: 17px;
        }
        .bodyDates {
            text-align: left;
            /*width: 800px;*/
            font-family: gothambook;
            font-size: 17px;
            color: #575756ff;
            line-height: 17px;
        }
        span {
            font-family: gothambold;
        }
        /* para que el qr quede en la misma hoja mover el margin-top */
        .qr {
            text-align: right;
            margin-top: 10px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div id="main">
        <div id="container">
            <div class="left col1">&nbsp;</div>
            <div class="left col2">
                <?php echo $content; ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>

</html>