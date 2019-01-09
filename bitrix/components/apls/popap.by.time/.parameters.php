<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


$arComponentParameters = array(
    'PARAMETERS' => array(
        "ALIAS" => Array(
            "NAME" => GetMessage("PARAMETER_ALIAS"),
            "TYPE" => "STRING",
            "DEFAULT" => "popapByTime",
            "PARENT" => "BASE",
        ),
        "TITLE_TEXT" => Array(
            "NAME" => GetMessage("PARAMETER_TITLE_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
        "START_TIME" => Array(
            "NAME" => GetMessage("PARAMETER_START_TIME"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
        "FILE_PATH" => Array(
            "NAME" => GetMessage("PARAMETER_FILE_PATH"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
        'OVERLAY' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_OVERLAY'),
            "DEFAULT" => "Y",
            'TYPE' => 'CHECKBOX',
        ),
        'AUTO_HIDE' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_AUTO_HIDE'),
            "DEFAULT" => "Y",
            'TYPE' => 'CHECKBOX',
        ),
        'OVERFLOW_HIDDEN' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_OVERFLOW_HIDDEN'),
            "DEFAULT" => "Y",
            'TYPE' => 'CHECKBOX',
        ),
        'OLD_CORE' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_OLD_CORE'),
            "DEFAULT" => "N",
            'TYPE' => 'CHECKBOX',
        ),
        'CLOSE_BUTTON' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_CLOSE_BUTTON'),
            "DEFAULT" => "N",
            'TYPE' => 'CHECKBOX',
        ),
        'ACCEPT_BUTTON' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_ACCEPT_BUTTON'),
            "DEFAULT" => "N",
            'TYPE' => 'SAVE_BUTTON',
        ),
        "ACCEPT_BUTTON_TEXT" => Array(
            "NAME" => GetMessage("PARAMETER_ACCEPT_BUTTON_TEXT"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
        "ACCEPT_BUTTON_JS" => Array(
            "NAME" => GetMessage("PARAMETER_ACCEPT_BUTTON_JS"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "PARENT" => "BASE",
        ),
        'CLOSE_AFTER_ACCEPT' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_CLOSE_AFTER_ACCEPT'),
            "DEFAULT" => "Y",
            'TYPE' => 'CHECKBOX',
        ),
    ),
);