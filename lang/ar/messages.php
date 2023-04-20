<?php

$nameValue=':name';

return [

    'success' => [
        'create' =>  'تم إضافة '.$nameValue.' بنجاح',
        'update' => 'تم تعديل '.$nameValue.' بنجاح',
        'delete' => 'تم حذف '.$nameValue.' بنجاح',
        'index' => ''
    ],

    'failed' => [
        'create' => 'لم يتم إضافة '.$nameValue.' | حاول مرة أخرى',
        'update' => 'لم يتم تعديل '.$nameValue.'  | حاول مرة أخرى',
        'delete' => 'لم يتم حذف '.$nameValue.'  | حاول مرة أخرى',
        'index' => ''
    ],
   
];