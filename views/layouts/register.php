<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                html, body, div, span, applet, object, iframe,
                h1, h2, h3, h4, h5, h6, p, blockquote, pre,
                a, abbr, acronym, address, big, cite, code,
                del, dfn, em, img, ins, kbd, q, s, samp,
                small, strike, strong, sub, sup, tt, var,
                b, u, i, center,
                dl, dt, dd, ol, ul, li,
                fieldset, form, label, legend,
                table, caption, tbody, tfoot, thead, tr, th, td,
                article, aside, canvas, details, embed, 
                figure, figcaption, footer, header, hgroup, 
                menu, nav, output, ruby, section, summary,
                time, mark, audio, video {
                    margin: 0;
                    padding: 0;
                    border: 0;
                    font-size: 100%;
                    font: inherit;
                    vertical-align: baseline;
                    z-index:9999;
                }
                article, aside, details, figcaption, figure, 
                footer, header, hgroup, menu, nav, section {
                    display: block;
                }
                body {
                    line-height: 1;
                }
                ol, ul {
                    list-style: none;
                }
                blockquote, q {
                    quotes: none;
                }
                blockquote:before, blockquote:after,
                q:before, q:after {
                    content: '';
                    content: none;
                }
                table {
                    border-collapse: collapse;
                    border-spacing: 0;
                }

                /* ---Css para las tablas--- */
                #main{
                    font-family: Arial, sans-serif;
                    margin:30px auto auto 30px;
                    padding:0;   

                }
                #container{
                    height:100%;
                    position:relative;
                }
                .bold{
                    font-weight: bold;
                }
                .clear{
                    clear: both;
                }


                .div_modInfo{
                    float: left;
                    width: 50%;
                }
               
                #nameLbl{
                    text-transform: uppercase;
                }

                .tituloLabel{                    
                    font-size: 16px;
                }
                #codigo_barra{                       
                    margin: 30px 0px 30px 0px;
                    width: 260px;
                    float: right;

                }
                .logo_cab{
                    margin: 30px 0px 30px 0px;
                    width: 200px;
                }

                 .imgr{
                    margin: 30px 0px 30px 0px;
                    width: 300px;
                }
                  .imgl{
                    margin: 15px 0px 15px 0px;
                    width: 300px;
                }

                  .imgb{
                    margin: 15px 0px 15px 0px;
                    width: 100px;
                }

                  .markone{
                   position: absolute;
                   top:96px;
                   right: 0px;
                }

                  .marktwo{
                   position: absolute;
                   top:0px;
                   left: 0px;
                }

                  .markback{
                   position: absolute;
                   bottom:0px;
                   left: 0px;
                   z-index:1;
                }

                    .markbackp{
                   position: fixed;
                   bottom:0px;
                   left: 0px;
                   z-index:1;
                }

                .tcolr_num_aut {
                    width: 100%;                      
                    color: #000000;
                    float: left;
                    font-size: 10.5px;                   
                    text-align: left;
                }                   
            </style>
    </head>
    <body>
    <div class="markone">
               <?php echo yii\helpers\Html::img(
                                    Yii::$app->basePath . "/themes/" . Yii::$app->view->theme->themeName . "/assets/img/logos/back2png.png", 
                                    array("class" => "imgr", "alt" => Yii::$app->params["copyright"])); ?>

        </div> 
    <div class="marktwo">
               <?php echo yii\helpers\Html::img(
                                    Yii::$app->basePath . "/themes/" . Yii::$app->view->theme->themeName . "/assets/img/logos/backpng.png", 
                                    array("class" => "imgl", "alt" => Yii::$app->params["copyright"])); ?>

        </div> 

        <div id="main">
            <div id="container">
                <div>
                    <div class="div_modInfo">                            
                        <div class="logo_cab"> 
                        </div>
                    </div>
                    <div class="div_modInfo">
                        <div class="posright" id="codigo_barra">
                          
                        </div>
                    </div> 
                </div>
                <div class="clear"></div>
                <?php echo $content; ?>                  
            </div>
        </div>
        
    </body>
</html>