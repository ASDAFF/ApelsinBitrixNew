<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();


$arComponentParameters = array(
    'PARAMETERS' => array(
        "ALIAS" => Array(
            "NAME" => GetMessage("PARAMETER_ALIAS"),
            "TYPE" => "STRING",
            "DEFAULT" => "popapByWindow",
            "PARENT" => "BASE",
        ),
        "BUTTON_ID" => Array(
            "NAME" => GetMessage("PARAMETER_BUTTON_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "popapByButton",
            "PARENT" => "BASE",
        ),
        "BUTTON_TEXT" => Array(
            "NAME" => GetMessage("PARAMETER_BUTTON_TEXT"),
            "TYPE" => "HTML",
            "DEFAULT" => "popapByButton",
            "PARENT" => "BASE",
        ),
        "BUTTON_CREATE" => Array(
            "NAME" => GetMessage("PARAMETER_BUTTON_CREATE"),
            "TYPE" => "STRING",
            "DEFAULT" => "N",
            "PARENT" => "CHECKBOX",
        ),
        "TITLE_TEXT" => Array(
            "NAME" => GetMessage("PARAMETER_TITLE_TEXT"),
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
            "DEFAULT" => "N",
            'TYPE' => 'CHECKBOX',
        ),
        'AUTO_HIDE' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_AUTO_HIDE'),
            "DEFAULT" => "N",
            'TYPE' => 'CHECKBOX',
        ),
        'OVERFLOW_HIDDEN' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PARAMETER_OVERFLOW_HIDDEN'),
            "DEFAULT" => "N",
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