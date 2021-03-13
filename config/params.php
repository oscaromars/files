<?php

return [
    'copyright' => 'Uteg',
    'alias' => 'UT',
    'web' => 'http://www.uteg.edu.ec',
    'version' => '1.0',
    'adminEmail' => 'analistadesarrollo01@uteg.edu.ec',//'web@uteg.edu.ec',

    'soporteEmail' => /*'dlopez@uteg.edu.ec', */ 'analistadesarrollo01@uteg.edu.ec',
    'admisiones' => /*'admisionesonline@uteg.edu.ec',*/  'analistadesarrollo01@uteg.edu.ec',
    'colecturia' => /*'colecturia@uteg.edu.ec',*/     'analistadesarrollo01@uteg.edu.ec',
    'jefetalento' => /*'directortalento@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'analistatalento' => /*'kmunoz@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'analistanomina' => /*'glamota@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec', 
    'lidercontact' => /*'lidercontactcenter@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec', 
    'adminlider' => /*'coordinacionadmisiones@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',     
    'contact1' => /*'contactcenter01@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec', 
    'contact2' => /*'contactcenter02@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec', 
    'contact3' => /*'contactcenter03@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec', 
    'contact4' => /*'contactcenter04@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec', 
    'admisiones1' => /*'admisiones01@uteg.edu.ec,*/ 'analistadesarrollo01@uteg.edu.ec',
    'admisiones2' => /*'admisiones02@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'admisiones3' => /*'admisiones03@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'admisiones4' => /*'admisiones04@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'ventasposgrado1' => /*'ventasposgrado01@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'ventasposgrado2' => /*'ventasposgrado03@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    'ventasposgrado3' => /*'ventasposgrado03@uteg.edu.ec',*/ 'analistadesarrollo01@uteg.edu.ec',
    
    'contactoEmail' => 'pruebacontacto@uteg.edu.ec',
    'culture' => 'es-ES',
    'dateTimeByDefault' => 'Y-m-d H:i:s',
    'TimeByDefault' => 'H:i:s',
    'dateByDefault' => 'Y-m-d',
    'dateByDatePicker' => 'yyyy-mm-dd',
    'cookieSession' => 3600 * 24 * 30,
    'logfile' => __DIR__ . '/../runtime/logs/pb.log',
    'limitRow' => 10,
    'pageSize' => 20,
    'userWebServer' => getenv('APACHE_RUN_USER'),
    'documentFolder' => '/uploads/',
    'repositorioFolder' => '/opt/files/',
    'imgFolder' => '/site/getimage/?route=/uploads/',
    'FileExtensions' => ['jpg', 'png', 'pdf'],
    'MaxFileSize' => 81920,//20480, //Tamaño 1 MB
    'MaxFileLogSize' => 80000000, // 50000000 bytes -> 50 MB
    'timeRecursive' => '2', // segundos
    'numRecursive' => '3',
    'currency' => '$',
    'keywordEncription' => 'PBdoHUHYU909854874HNGFGKO',
    'tokenid' => 'HU787390kdnhyyejkKJHWFRDERD3573LOSNQ2JKTDCA67253',
    'numbersecret' => '29839813213464',
    'socialNetworks' => [
        'facebook' => 'https://www.facebook.com/uteg.ec',
        'twitter' => 'https://twitter.com/uteg_ec',
        'youtube' => 'https://www.youtube.com/channel/UC8_6Fr2MGrNkr-kM7BZzkdQ',
    ],
    // Variables VPOS                     
    
    'Vposvector' => "1EBCFD349F229E00",
    'VposacquirerId' => '8',
    
    'VposcommerceId' => '7687',
    'VpospurchaseCurrencyCode' => '840',
 
    'VposcommerceMallId' => '000001',
    'Vposlanguage' => 'SP',
    'VposbillingAddress' => 'Direccion ABC',
    'VposbillingZIP' => '1234567890',
    'VposbillingCity' => 'Quito',
    'VposbillingState' => 'Quito',
    'VposbillingCountry' => 'EC',
    'VposbillingPhone' => '123456789',
    'VposshippingAddress' => 'Direccion ABC',
    'VposterminalCode' => '000001',
    'VposIVA' => 0.12,
    //desarrollo
    'VposllaveVPOSCryptoPub' => "-----BEGIN PUBLIC KEY-----\n".
"MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDTJt+hUZiShEKFfs7DShsXCkoq\n".
"TEjv0SFkTM04qHyHFU90Da8Ep1F0gI2SFpCkLmQtsXKOrLrQTF0100dL/gDQlLt0\n".
"Ut8kM/PRLEM5thMPqtPq6G1GTjqmcsPzUUL18+tYwN3xFi4XBog4Hdv0ml1SRkVO\n".
"DRr1jPeilfsiFwiO8wIDAQAB\n".
"-----END PUBLIC KEY-----",
    
  
    'VposllaveComercioFirmaPriv' => "-----BEGIN RSA PRIVATE KEY-----\n".
"MIICWwIBAAKBgQCwKB2x2HztWmG0Z1gLTIcKhckC6L2ftaJlyCfjNXy/HQcinFxf\n".
"pyb595y9QePxCbJrYa3RD0ZAFaKvBLqA1rrG2uZuverrOG4KTr/1unXgEjfOIexq\n".
"n0lufFIPG4Ymc5M8RoAE49OZ8dATri6vNQefG3EunsG+xbBBFQhlL+HpBwIDAQAB\n".
"AoGAAMbcgqlCu8U3QxVllP8sZUZ7wXCHTYn+glZknqLgvvMA0g1AdweEq3uFDGya\n".
"9bgmOkT/ADoCBExUIFN0AxdvUWlKhshVPtDunZn25uP1sCHdTkqY0g6mDbulEEZV\n".
"T+1R+HWMlw6jmwWxXYJhda8sswpJYFkmzQUXWoVFy1pJH6ECQQDnF3iGunaC6F/N\n".
"Ad210osPatPqGzc1pgV/TNm7KjBGVQ7QhI8AU9N5zFEBF6bYrOPHyb1zC8rHYerX\n".
"baitFsz3AkEAwyTSBY8bFbF77kBv1hA0odvl7jIPkIQRj1wW6EEoG54KdCrB3Zjg\n".
"x2UFVk7IBTRzlptdNxggYS+tYaTP7Y4QcQJAXWLKvgdUJQqqzDnY0sVGlPBiutRM\n".
"t01kI1F3G3+tCn8NAY7QCx3U8/9xLLPWJPGZCv+no3o8c95J/My/wVLZeQJAL7IG\n".
"KxmpEwpIMhlJvFZFpvHqJufRag79g76MUsPqG6XrBC2XKKQ+/D0yqr+in7MMNVlP\n".
"1TFuaJKQm/b+Yx9/4QJAX+YoHflg5tBY4QQ1vTEmt4Q1511O4TaC64TdjaEZDadK\n".
"qedOMrLOYzIk4xKqNkoixE5f1oFhF2rtrRuHyD2m4Q==\n".
"-----END RSA PRIVATE KEY-----",

    //desarrollo
    'VposllaveVPOSFirmaPub' => "-----BEGIN PUBLIC KEY-----\n".
"MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvJS8zLPeePN+fbJeIvp/jjvLW\n".
"Aedyx8UcfS1eM/a+Vv2yHTxCLy79dEIygDVE6CTKbP1eqwsxRg2Z/dI+/e14WDRs\n".
"g0QzDdjVFIuXLKJ0zIgDw6kQd1ovbqpdTn4wnnvwUCNpBASitdjpTcNTKONfXMtH\n".
"pIs4aIDXarTYJGWlyQIDAQAB\n".
"-----END PUBLIC KEY-----",
   
    'VposllaveComercioCryptoPriv' => "-----BEGIN RSA PRIVATE KEY-----\n".
"MIICXAIBAAKBgQCrRYRy6pqSuM2FEuO+0V8R83rOt7UFyMNw5toOIxda8IuziOj/\n".
"XyU7S9BDLyGYNc/GY/4tZ25b5JclNrg6dI9Kwt05UYSKazX+0EKQYscga/ZtiEVv\n".
"fL7lksO7ENB7CQe2dfzduOBsQGU9P9XVvnEw+qa6Traq3QqghxV6Pulb6wIDAQAB\n".
"AoGAfhTV9RbZpYsf2IfYWl+dIgTgcg7w1wo9Pf7jpSaWCd8sqITwKRZsvSMJdHvc\n".
"ukVa6EwyEFClAEbeMYn/wyCNXqKNZ7PsDkhi+YWkqXMVoF+qnc5QygtIdQodLCbV\n".
"wkrm2vqRVh2HnoSuI53Cw1xvubxfj64RpF5wCTGWuy4P0GECQQDVaHIGyAa/lb3i\n".
"Swd2QmGJ1a+irH1L3UoUkPTafYXKP07qZMjsa4VXEfudo4/yIeASsNzggVaOiarE\n".
"pWDPgF/bAkEAzXQ29HVCzJZIns6tHoIgkd71LCQQOq8/1yP7/bI8f9YveM7q7t0g\n".
"iSQCCekKWz5qKyr0JgU4dYNdnYybEsH5MQJBAJphjmuddFRQTSdRQ7qnVsxRi1dR\n".
"FOs20IqEOr18pLakicBC3J87QSC136IwWse8/c5Hp+G5bxZ6PNE5GgnCQpUCQAbd\n".
"kVCN8pN/miGkamiQlKILP0ogUmKDpLB9xVfu8tKax69Tysn8na+3glHudagi581V\n".
"fB8hIYfbwe8X2b7AuKECQCtubYGegG2LhuSm28tRGLjB/Xe3aQMSr8IJedwsVOLg\n".
"mjyNn76/ccvjT7ptBMUMLN9Cm+feZ0HmdMBzFHO+dgA=\n".
"-----END RSA PRIVATE KEY-----",    


//comentario añadido para pruebas
];
