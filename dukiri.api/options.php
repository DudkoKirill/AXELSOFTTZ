<?php
use Bitrix\Main\Localization\Loc;
use    Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

$arIblocks=\Bitrix\Iblock\IblockTable::getList(array('select' => array('ID', 'NAME')))->fetchAll();
$iblocks=array();
foreach ($arIblocks as $arIblock){
    $iblocks[$arIblock['ID']]=$arIblock['NAME'].'['.$arIblock['ID'].']';
}
Loader::includeModule($module_id);
$aTabs = array(
    array(
        "DIV"       => "edit",
        "TAB"       => 'Настройки',
        "TITLE"   => 'Настройки',
        "OPTIONS" => array(
            "Настройки",
            array(
                "IBLOCK_ID",
                'ID инфоблока',
                "",
                array("selectbox", $iblocks)
            ),
        )
    )
);
if($request->isPost() && check_bitrix_sessid()){

    foreach($aTabs as $aTab){

        foreach($aTab["OPTIONS"] as $arOption){

            if(!is_array($arOption)){

                continue;
            }

            if($arOption["note"]){

                continue;
            }

            if($request["apply"]){

                $optionValue = $request->getPost($arOption[0]);

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage()."?mid=".$module_id."&lang=".LANG);
}
$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();
?>
<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">

  <?
   foreach($aTabs as $aTab){

       if($aTab["OPTIONS"]){

         $tabControl->BeginNextTab();

         __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
      }
   }

   $tabControl->Buttons();
  ?>

   <input type="submit" name="apply" value="Применить" class="adm-btn-save" />

   <?
   echo(bitrix_sessid_post());
 ?>

</form>

<?$tabControl->End();?>