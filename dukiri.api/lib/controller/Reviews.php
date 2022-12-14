<?php
namespace Dukiri\Api\Controller;

use \Bitrix\Main\Error;

class Reviews extends \Bitrix\Main\Engine\Controller
{

    public function getDefaultPreFilters(){
        return [];
    }

    public function getReviewsAction(int $page, int $limit=10):? array
    {
        $reviewsIblock=\COption::GetOptionString("dukiri.api", "IBLOCK_ID", "0");
        $params=array('select'=>array('ID'),'filter' => array('IBLOCK_ID' => $reviewsIblock),'limit' => $limit,'count_total' => 1);
        if($page>1){
            $params['offset']=$page*$limit;
        }
        $dbItems=\Bitrix\Iblock\ElementTable::getList($params);
        $allCount=$dbItems->getCount();
        $reviews = $dbItems->fetchAll();
        $list=array();
        foreach ($reviews as $review){
            $city="nothing";
            $rate="nothing";
            $db_props = \CIBlockElement::GetProperty(77, 555594, array("sort" => "asc"), Array("CODE"=>"CITY"));
            if($ar_props = $db_props->Fetch()){
                $cityId= unserialize(htmlspecialcharsback($ar_props['VALUE']), [stdClass::class])['SECTION'];
                \Bitrix\Iblock\ElementTable::getById($cityId);
                $city=\Bitrix\Iblock\SectionTable::getById(6921)->fetch()['NAME'];

            }
            $db_props = \CIBlockElement::GetProperty(77, 555594, array("sort" => "asc"), Array("CODE"=>"RATING"));
            if($ar_props = $db_props->Fetch()){
                $rate=$ar_props['VALUE'];
            }
            $list[]=array('fields'=>array('ID'=>$review['ID']),'properties'=>array('city'=>$city,'rating'=>$rate));
        }
        $res=array('list'=>$list,'all_count'=>$allCount);
        return $res;
    }

    public function getSectionsAction(int $iblockID):? array
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $sects=\Bitrix\Iblock\SectionTable::getList(array('filter' => array('IBLOCK_ID' => $iblockID),'select' => array('ID', 'NAME')))->fetchAll();
        return array('result'=>json_encode($sects,JSON_UNESCAPED_UNICODE));
    }
}