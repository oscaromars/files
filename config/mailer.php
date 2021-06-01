<?php

/*
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by
 * Yii Software LLC (http://www.yiisoft.com) Copyright Â© 2008
 *
 * Authors:
 *
 * Eduardo Cueva <ecuava@penblu.com>
 */

return [
    'class' => 'yii\swiftmailer\Mailer',
//    'useFileTransport' => true, // false if use other transport
    'useFileTransport' => false,
    /* 'transport' => [
      'class' => 'Swift_SmtpTransport',
      'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
      'username' => 'prueba@gmail.com',
      'password' => 'xxxxxxxxxx',
      'port' => '465', // Port 25 is a very common port too
      'encryption' => 'ssl', // tls, It is often used, check your provider or mail server specs
      ], */
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com', // e.g. smtp.mandrillapp.com or smtp.gmail.com
        //'username' => 'procesosonline@uteg.edu.ec',
        'username' => 'notificaciones@uteg.edu.ec',//'facultadonline@uteg.edu.ec',
        //'password' => 'Ut3g2017P4oc3sos',
        'password' => 'F@cult@d0nline2o2o',
        'port' => '587', // Port 25 is a very common port too
        'encryption' => 'tls', // ssl, tls, It is often used, check your provider or mail server specs
        'streamOptions' => ['ssl' =>
            ['allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ],
    ],
    /*'transport' => [
        'class' => 'Swift_SmtpTransport',
        'constructArgs' => ['localhost', 25],
            'plugins' => [
            [
                'class' => 'Swift_Plugins_ThrottlerPlugin',
                'constructArgs' => [20],
            ],
        ],
    ],*/
];
