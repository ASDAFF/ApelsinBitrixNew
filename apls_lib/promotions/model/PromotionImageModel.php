<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModelAbstract.php";

class PromotionImageModel extends PromotionModelAbstract
{
    protected static $tableName = "apls_promotions_image";
    protected static $requiredFields = array('b_file_name','s_file_name','name','type');

    public static function imageSearchByType($typeId, $searchString = "", $atLeastOne = true):array {
        $searchWords = array();
        if($searchString !== "") {
            $searchString = mb_strtolower($searchString);
            $searchWords = array_diff(explode(" ", $searchString),array(" "));
        }
        $whereType = MySQLWhereElementString::getBinaryOperationString(
            'type',
            MySQLWhereElementString::OPERATOR_B_EQUAL,
            $typeId
        );
        $orderByObj = new MySQLOrderByString();
        $orderByObj->add('name',MySQLOrderByString::ASC);
        $images = PromotionImageModel::getElementList($whereType,null,null,$orderByObj);
        if(!empty($searchWords)) {
            foreach ($images as $imageId => $image) {
                if($image instanceof PromotionImageModel) {
                    $searchCount = 0;
                    foreach ($searchWords as $searchWord) {
                        $searchWord = str_replace("_"," ", $searchWord);
                        if(substr_count(mb_strtolower($image->getFieldValue('name')),$searchWord) > 0) {
                            $searchCount ++;
                        }
                    }
                    if(($atLeastOne && $searchCount < 1) || (!$atLeastOne && $searchCount < count($searchWords))) {
                        unset($images[$imageId]);
                    }
                }
            }
        }
        return $images;
    }
}