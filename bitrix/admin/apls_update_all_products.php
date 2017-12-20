<?
if (isset($_REQUEST['work_start']))
{
    define("NO_AGENT_STATISTIC", true);
    define("NO_KEEP_STATISTIC", true);
}
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/prolog.php");
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/hlblock/APLS_GetHighloadEntityDataClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogHelper.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/catalog/APLS_CatalogProperties.php";

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("main");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm("Доступ запрещен");

if($_REQUEST['work_start'] && check_bitrix_sessid())
{
    $ignoreSectionId = "";
    $rs = CIBlockSection::GetList(array(), array("EXTERNAL_ID" => "98557c36-f99a-11e6-80ec-00155dfef48a"), false);
    if ($obRes = $rs->GetNextElement()) {
        $arRes = $obRes->GetFields();
        $ignoreSectionId = $arRes["ID"];
    }
    $start = microtime(true);
    CModule::IncludeModule("iblock");
    $lastID = intval($_REQUEST["lastid"]);
    $startID = $lastID;
    $table = "b_iblock_element";
    $iBlockId = APLS_CatalogHelper::getShopIblockId();
    $propertiesUpdate = ["26e05687-c602-4c36-8b63-debb1b4e0250", "26e05687-c602-4c36-8b63-desc5g0XN322"];
    $rs = $DB->Query("select `ID`,`ACTIVE` from `$table` where `ID`>$lastID AND `IBLOCK_ID`='$iBlockId' AND `IBLOCK_SECTION_ID`!='$ignoreSectionId' order by `ID` asc limit 1000;");
    $updateElements = ["hide"=>[],"show"=>[]];
    $sqlIdArray = ["hide"=>'',"show"=>''];
    $elementCounter = 0;
    while ($ar = $rs->Fetch())
    {
        $elementCounter++;
        $lastID = intval($ar["ID"]);
        $isActive = $ar["ACTIVE"] == 'Y';
        if($startID === 0) {
            $startID = $lastID;
        }
        $PROP = array();
        $hideElement = false;
        foreach ($propertiesUpdate as $property) {
            $propertyCode = APLS_CatalogProperties::convertPropertyXMLIDtoCODE($property);
            $propertyId = APLS_CatalogProperties::convertPropertyXMLIDtoID($property);
            $db_props = CIBlockElement::GetProperty($iBlockId, $lastID, array("sort" => "asc"), Array("CODE"=>$propertyCode));
            if($ar_props = $db_props->Fetch()) {
                if($ar_props['VALUE_XML_ID'] === 'false') {
                    $hideElement = true;
                }

            }
        }
        if($isActive === $hideElement) {
            if($hideElement) {
                $updateElements["hide"][] = $lastID;
                $sqlIdArray["hide"] .= $lastID.",";
            } else {
                $updateElements["show"][] = $lastID;
                $sqlIdArray["show"] .= $lastID.",";
            }
        }
    }
    if($startID !== $lastID) {
        $rsLeftBorder = $DB->Query("select `ID` from `$table` where `ID`>$lastID AND `IBLOCK_ID`='$iBlockId' order by `ID` asc", false, "FILE: ".__FILE__."<br /> LINE: ".__LINE__);
        $leftBorderCnt = $rsLeftBorder->SelectedRowsCount();
        $rsAll = $DB->Query("select ID from $table where `IBLOCK_ID`='$iBlockId';", false, "FILE: ".__FILE__."<br /> LINE: ".__LINE__);
        $allCnt = $rsAll->SelectedRowsCount();
        $p = 100-round(100*$leftBorderCnt/$allCnt, 2);
        $counterHide = count($updateElements["hide"]);
        $counterShow = count($updateElements["show"]);
        $counterNone = $elementCounter-$counterHide-$counterShow;
        $sqlIdArray["hide"] = "(".mb_substr($sqlIdArray["hide"],0,-1).")";
        $sqlIdArray["show"] = "(".mb_substr($sqlIdArray["show"],0,-1).")";
        if($counterHide > 0) {
            $rs = $DB->Query("UPDATE `$table` SET `ACTIVE` = 'N' WHERE `ID` in ".$sqlIdArray["hide"].";");
        }
        if($counterShow > 0) {
            $rs = $DB->Query("UPDATE `$table` SET `ACTIVE` = 'Y' WHERE `ID` in ".$sqlIdArray["show"].";");
        }
        $updateString = 'Скрыто: '.$counterHide."<br>Отображено: ".$counterShow."<br>Без изменения: ".$counterNone;
        $time = 'Время выполнения: '.round(microtime(true) - $start, 4).' сек.';
        echo 'CurrentStatus = Array('.$p.',"'.($p < 100 ? '&lastid='.$lastID : '').'","Обработаны товары с '.$startID." по ".$lastID.'<br><br>'.$updateString.'<br><br>'.$time.'");';
    } else {
        echo 'CurrentStatus = Array("0",false,"Завершено");';
    }
    die();
}
$clean_test_table = '<table id="result_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущее действие</td>'.
    '<td width="1%">&nbsp;</td>'.
    '</tr>'.
    '</table>';
$aTabs = array(array("DIV" => "edit1", "TAB" => "Перенос данных"));
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$APPLICATION->SetTitle("Обновление статуса активности у товаров");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
    <script type="text/javascript">
        var bWorkFinished = false;
        var bSubmit;
        function set_start(val)
        {
            document.getElementById('work_start').disabled = val ? 'disabled' : '';
            document.getElementById('work_stop').disabled = val ? '' : 'disabled';
            document.getElementById('progress').style.display = val ? 'block' : 'none';
            if (val)
            {
                ShowWaitWindow();
                document.getElementById('result').innerHTML = '<?=$clean_test_table?>';
                document.getElementById('status').innerHTML = 'Работаю...';
                document.getElementById('percent').innerHTML = '0%';
                document.getElementById('indicator').style.width = '0%';
                CHttpRequest.Action = work_onload;
                CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?work_start=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>');
            }
            else
                CloseWaitWindow();
        }
        function work_onload(result)
        {
            try
            {
                eval(result);
                iPercent = CurrentStatus[0];
                strNextRequest = CurrentStatus[1];
                strCurrentAction = CurrentStatus[2];
                document.getElementById('percent').innerHTML = iPercent + '%';
                document.getElementById('indicator').style.width = iPercent + '%';
                document.getElementById('status').innerHTML = 'Работаю...';
                if (strCurrentAction != 'null')
                {
                    oTable = document.getElementById('result_table');
                    oRow = oTable.insertRow(-1);
                    oCell = oRow.insertCell(-1);
                    oCell.innerHTML = strCurrentAction;
                    oCell = oRow.insertCell(-1);
                    oCell.innerHTML = '';
                }
                if (strNextRequest && document.getElementById('work_start').disabled)
                    CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?work_start=Y&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>' + strNextRequest);
                else
                {
                    set_start(0);
                    bWorkFinished = true;
                }
            }
            catch(e)
            {
                CloseWaitWindow();
                document.getElementById('work_start').disabled = '';
                alert('Сбой в получении данных');
            }
        }
    </script>
    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="post_form" id="post_form">
        <?
        echo bitrix_sessid_post();
        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>
        <tr>
            <td colspan="2">
                <input type=button value="Старт" id="work_start" onclick="set_start(1)" />
                <input type=button value="Стоп" disabled id="work_stop" onclick="bSubmit=false;set_start(0)" />
                <div id="progress" style="display:none;" width="100%">
                    <br />
                    <div id="status"></div>
                    <table border="0" cellspacing="0" cellpadding="2" width="100%">
                        <tr>
                            <td height="10">
                                <div style="border:1px solid #B9CBDF">
                                    <div id="indicator" style="height:10px; width:0%; background-color:#B9CBDF"></div>
                                </div>
                            </td>
                            <td width=30>&nbsp;<span id="percent">0%</span></td>
                        </tr>
                    </table>
                </div>
                <div id="result" style="padding-top:10px"></div>
            </td>
        </tr>
        <?
        $tabControl->End();
        ?>
    </form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>