<?php

return [

// config/snappy.php
'pdf' => [
    'enabled' => true,
    'binary'  => env('WKHTMLTOPDF_BINARY', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe'),
    'timeout' => false,
    'options' => [],
    'env'     => [],
],

    'image' => [
        'enabled' => true,
       // 'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe"',
       'binary' => env('WKHTML_PDF_BINARY','/usr/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => [],
        'env' => [],
    ],
];


