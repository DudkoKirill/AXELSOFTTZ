<?php
defined('B_PROLOG_INCLUDED') || die;
global $APPLICATION;
$module_id = 'dukiri.api';
CModule::AddAutoloadClasses(
    $module_id,
    array(
        "Dukiri\Api\IblockSection" => "classes/IblockSection.php",
        "Dukiri\Api\Controller\Reviews" => "lib/controller/Reviews.php",
    )
);