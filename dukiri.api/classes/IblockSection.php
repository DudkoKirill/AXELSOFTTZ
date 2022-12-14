<?php
namespace Dukiri\Api;

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Iblock;

class IblockSection
{
    public function GetUserTypeDescription()
    {
        return array(
            'USER_TYPE_ID' => 'dukiri_iblocksection', //Уникальный идентификатор типа свойств
            'USER_TYPE' => 'IBLOCKSECTION',
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Привязка к инфоблоку с разделом',
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
            'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
        );
    }

    public static function ConvertToDB($arProperty, $value)
    {
        if ($value['VALUE']['IBLOCK'] != '' && $value['VALUE']['SECTION']!='')
        {
            try {
                $value['VALUE'] = base64_encode(serialize($value['VALUE']));
            } catch(Bitrix\Main\ObjectException $exception) {
                echo $exception->getMessage();
            }
        } else {
            $value['VALUE'] = '';
        }

        return $value;
    }

    public static function ConvertFromDB($arProperty, $value, $format = '')
    {
        if ($value['VALUE'] != '')
        {
            try {
                $value['VALUE'] = base64_decode($value['VALUE']);
            } catch(Bitrix\Main\ObjectException $exception) {
                echo $exception->getMessage();
            }
        }

        return $value;
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $arHtmlControl)
    {
        $iblocks=\Bitrix\Iblock\IblockTable::getList(array('select' => array('ID', 'NAME')))->fetchAll();

        $selectSectId = 'sects_' . substr(md5($arHtmlControl['VALUE']), 0, 10); //ID для js
        $rowId = 'row_' . substr(md5($arHtmlControl['VALUE']), 0, 10); //ID для js
        $fieldName =  htmlspecialcharsbx($arHtmlControl['VALUE']);
        $arValue = unserialize(htmlspecialcharsback($value['VALUE']), [stdClass::class]);
        $select = '<select data-id="'. $selectSectId .'" class="iblock" name="'. $fieldName .'[IBLOCK]">';
        foreach ($iblocks as $block){
            if($arValue['IBLOCK'] == $block['ID']){
                $select .= '<option value="'. $block['ID'] .'" selected="selected">'. $block['NAME']."[".$block['ID'].']' .'</option>';
            } else {
                $select .= '<option value="'. $block['ID'] .'">'. $block['NAME']."[".$block['ID'].']' .'</option>';
            }

        }
        $select .= '</select>';
        $select2 = '<select class="section" name="'. $fieldName .'[SECTION]" id="'. $selectSectId .'">';
        if(isset($arValue['IBLOCK'])){
            $sects=\Bitrix\Iblock\SectionTable::getList(array('filter' => array('IBLOCK_ID' => $arValue['IBLOCK']),'select' => array('ID', 'NAME')))->fetchAll();
            foreach ($sects as $sect){
                if($arValue['SECTION'] == $sect['ID']){
                    $select2 .= '<option value="'. $sect['ID'] .'" selected="selected">'. $sect['NAME']."[".$sect['ID'].']' .'</option>';
                } else {
                    $select2 .= '<option value="'. $sect['ID'] .'">'. $sect['NAME']."[".$sect['ID'].']' .'</option>';
                }

            }
        }
        $select2 .= '</select>';
        $html = '<div class="property_row">';

        $html .= '<div class="city_row" id="'. $rowId .'">';
        $html .= $select;
        $html .= $select2;

        if($arValue['IBLOCK']!='' && $arValue['SECTION']!=''){
            $html .= '&nbsp;&nbsp;<input type="button" style="height: auto;" value="x" title="Удалить" onclick="document.getElementById(\''. $rowId .'\').parentNode.parentNode.remove()" />';
        }
        $html .= '</div>';
        $html .= '</div><br/>';
        $html .= '<script src="/local/modules/dukiri.api/js/script.js"></script>';
        return $html;
    }
}
