<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/ui/APLS_SortLists/APLS_SortLists.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRegionModel.php";

class AdminPromotionsRegion
{
    /**
     * Интерфейс списка всех доступных регионов
     * @return string - HTML код
     */
    public static function getRegionsListHtml():string {
        $orderByObj = new MySQLOrderByString();
        $orderByObj->add('sort',MySQLOrderByString::ASC);
        $regions = PromotionRegionModel::getElementList(null, null, null, $orderByObj);
        $regionListElements = new APLS_SortListElements();
        foreach ($regions as $region) {
            if($region instanceof PromotionRegionModel) {
                $editButton = "<a class='editButton' href='javascript:void(0)' regionId='".$region->getId()."'>изменить</a>";
                $setDefaultButton = "<a class='setDefaultButton' href='javascript:void(0)' regionId='".$region->getId()."'>по умолчанию</a>";
                $delButton = "<a class='delButton' href='javascript:void(0)' regionId='".$region->getId()."'>удалить</a>";
                $content = "<span class='region'>".$region->getFieldValue('region')."</span>";
                $cityString = "";
                foreach ($region->getCities() as $city) {
                    if($city instanceof  PromotionCityModel) {
                        $cityName = $city->getFieldValue('city');
                        $pos = strpos($cityName, ',');
                        if($pos > 0) {
                            $cityString .= substr($cityName, 0, $pos).", ";
                        } else {
                            $cityString .= $cityName.", ";
                        }
                    }
                }
                if($cityString != "") {
                    $content .= ": <span class='cities'>".substr($cityString, 0, -2)."</span>";
                }
                $content .= "<div class='buttonPanel'>$editButton $setDefaultButton $delButton</div>";
                $regionElement = new APLS_SortListElement($content);

                $regionElement->addAttribute('regionId',$region->getId());
                if($region->getFieldValue('default') > 0) {
                    $regionElement->addClassName("default-promotion-region");
                    $regionElement->addClassName("selected-promotion-region");
                }
                $regionListElements->addSortListElement($regionElement);
            }
        }
        $regionList = new APLS_SortList();
        $regionList->setSortListElements($regionListElements);
        $html = "";
        $html .= "<div class='PromotionRegionsList'>".$regionList->getSortListHtml()."</div>";
        $html .= "<div class='PromotionRegionsListButtonPanel'>";
        $html .= "<div class='NewRegionName'><input type='text' required placeholder='Название региона'></div>";
        $html .= "<div class='NewRegionAlias'><input type='text' required placeholder='Алиас'></div>";
        $html .= "<div class='NewRegionAdd'>Создать регион</div>";
        $html .= "</div>";
        $html .= "
        <script>
        $(document).ready(function () {
            aplsSortListAddSelectableAndSortable('AdminPromotionsRegionStopSort');
            $('.PromotionRegionsListButtonPanel .NewRegionAdd').click(AdminPromotionsRegionAdd);
            $('.PromotionRegionsList .buttonPanel .editButton').click(AdminPromotionsRegionUiShowRegionsEditEvent);
            $('.PromotionRegionsList .buttonPanel .setDefaultButton').click(AdminPromotionsRegionSetDefault);
            $('.PromotionRegionsList .buttonPanel .delButton').click(AdminPromotionsRegionDelete);
        });
        </script>
        ";
        return $html;
    }

    /**
     * Редактирование региона
     * @param string $regionId
     * @return string
     */
    public static function getRegionEditHtml(string $regionId):string {
        $region = new PromotionRegionModel($regionId);
        if($region->getRegion() == "") {
            return static::getRegionAddHtml();
        }
        $html = "<div class='RegionEditWrapper'>";
        $html .= static::getTitle("Изменение региона: ".$region->getRegion());
        $html .= "<div class='RegionFields'>";
        $html .= static::getRegionFieldHtml(
            $region->getId(),
            "Идентификатор",
            "RegionId",
            true
        );
        $html .= static::getRegionFieldHtml(
            $region->getFieldValue('region'),
            "Название региона",
            "RegionName",
            false

        );
        $html .= static::getRegionFieldHtml(
            $region->getFieldValue('alias'),
            "Псевдоним",
            "RegionAlias",
            false

        );
        $html .= "</div>";
        $html .= "<div class='RegionTextFields'>";
        $html .= static::getRegionTextFieldHtml(
            $region->getFieldValue('head_html'),
            "HTML для шапки",
            "HeadHtml",
            false

        );
        $html .= "</div>";
        $html .= "<div class='RegionCitiesTitle'>Территории</div>";
        $html .= "<div class='RegionCities'></div>";
        $html .= "<div class='RegionCitiesTitle'>Добавить территорию</div>";
        $html .= "<div class='RegionCitiesAddPanel'></div>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= static::showRegionsListButton();
        $html .= "
        <script>
        $(document).ready(function () {
            AdminPromotionsRegionUiCitiesAdd();
        });
        </script>";
        return $html;
    }

    private static function getRegionFieldHtml($value, $name, $fieldClass = "", $disabled = false, $afterInputHtml = "") {
        if($disabled) {
            $disabled = "disabled";
        } else {
            $disabled = "";
        }
        $html = "";
        $html .= "<div class='RegionField $fieldClass'>";
        $html .= "<div class='RegionFieldName'>$name</div>";
        $html .= "<div class='RegionFieldValue'><input id='RegionFieldValue$fieldClass' type='text' value='$value' $disabled required>$afterInputHtml</div>";
        $html .= "</div>";
        return $html;
    }

    private static function getRegionTextFieldHtml($value, $name, $fieldClass = "", $disabled = false, $afterInputHtml = "") {
        if($disabled) {
            $disabled = "disabled";
        } else {
            $disabled = "";
        }
        $html = "";
        $html .= "<div class='RegionField RegionTextField $fieldClass'>";
        $html .= "<div class='RegionFieldName'>$name</div>";
        $html .= "<div class='RegionFieldValue'><textarea id='RegionFieldValue$fieldClass' type='text' $disabled required>$value</textarea>$afterInputHtml</div>";
        $html .= "</div>";
        return $html;
    }

    private static function getTitle($title) {
        return "<div class='RegionWrapperTitle'>$title</div>";
    }

    private static function showRegionsListButton():string {
        $html = "<div class='PromotionRegionButtonPanel'>";
        $html .= "<div class='showRegionsListButton'>к списку регионов</div>";
//        $html .= "<div class='refreshButton'>Отменить</div>";
//        $html .= "<div class='saveButton'>Сохранить</div>";
        $html .= "</div>";
        $html .= "
        <script>
        $(document).ready(function () {
            $('.showRegionsListButton').click(AdminPromotionsRegionUiShowRegionsList);
        });
        </script>";
        return $html;
    }
}