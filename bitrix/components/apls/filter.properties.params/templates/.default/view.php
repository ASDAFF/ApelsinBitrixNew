<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
require_once($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/CheckBoxGenerator.php");
$VIEW_HTML = "";
foreach ($arResult as $commonArray) {
    $VIEW_HTML .= '<div class="APLSFilterPropertiesContainerContentBlock"';
    $VIEW_HTML .= 'XML_ID="'.$commonArray["XML_ID"].'"';
    $VIEW_HTML .= 'HLID="'.$commonArray["HLID"].'">';
    $VIEW_HTML .= '<div class="APLSFilterPropertiesContainerContentBlockName">';
    $VIEW_HTML .= '<span id="ID">['. $commonArray["ID"] .']</span>';
    $VIEW_HTML .= '<span id="name">'.$commonArray['NAME'].'</span></div>';
    $VIEW_HTML .= '<div class="APLSFilterPropertiesContainerContentBlockSmartFilter"><div class="CheckBoxConteiner">'.CheckBoxGenerator::get($commonArray['SMART_FILTER'], $commonArray['XML_ID'], 'column1 checkboxFilter').'</div></div>';
    $VIEW_HTML .= '<div class="APLSFilterPropertiesContainerContentBlockMapFilter"><div class="CheckBoxConteiner">'.CheckBoxGenerator::get($commonArray['DETAIL_PROPERTY'], $commonArray['XML_ID'], 'column2 checkboxFilter').'</div></div>';
    $VIEW_HTML .= '<div class="APLSFilterPropertiesContainerContentBlockCopmarisonFilter"><div class="CheckBoxConteiner">'.CheckBoxGenerator::get($commonArray['COMPARE_PROPERTY'], $commonArray['XML_ID'], 'column3 checkboxFilter').'</div></div>';
    $VIEW_HTML .= '<div class="APLSFilterPropertiesContainerContentBlockApproved"><div class="CheckBoxConteiner">'.CheckBoxGenerator::get($commonArray['APPROVED'], $commonArray['XML_ID'], 'column4 checkboxFilter').'</div></div>';
    $VIEW_HTML .= '</div>';
}
$VIEW_HTML .= '
<script type = "text/javascript">
    $(".checkboxFilter").change(function () {
        var data = [];
        data["xml_id"] = $(this).parent().parent().parent().attr("xml_id");
        data["HLID"] = $(this).parent().parent().parent().attr("HLID");
        if($(this).hasClass("column1")) {
            data["field"] = "SMART_FILTER";
        } else if($(this).hasClass("column2")) {
            data["field"] = "DETAIL_PROPERTY";
        } else if($(this).hasClass("column3")) {
            data["field"] = "COMPARE_PROPERTY";
        } else if($(this).hasClass("column4")) {
            data["field"] = "APPROVED";
        }
        if(this.checked){
            data["val"] = 1;
        }else{
            data["val"] = 0;
        }
        $(".statusBarButton").addClass("neadApdate");
        $(".statusBarButton").html("отфильтровать повторно");
        data["templateFolder"] = $(".APLSFilterProperties").attr("templateFolder");
        BX.ajax({
            url: data["templateFolder"] + "/ajaxSetValue.php",
            data: data,
            method: "POST",
            dataType: "html",
            onsuccess: function(rezult){
//                APLSFilterPropertiesParams_FilterAjax();
                return true;
            },
            onfailure: function(){
                alert("Произошла ошибка");
            },
        });
    });
</script>
';
