<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">
<form action="#" id="APLSSortPropertiesListAllqform" method="post">
    <div class="statusBar">
        <div class="statusBarText"></div>
        <div class="statusBarButton">
            Сохранить
        </div>
    </div>
    <table
            id="APLSSortProperties"
            class="APLSSortPropertiesWrapper"
            sortScriptFile="<?=$templateFolder?>/ajax.php"
    >
        <tr>
            <td class="resultHTML"></td>
        </tr>
        <tr class="APLSSortPropertiesName">
            <th>
                Первоочередные</br>
            </th>
            <th>
                Неотсортированные</br>
            </th>
            <th>
                Второстепенные</br>
            </th>
        </tr>
        <tr>
            <td id="APLSSortPropertiesListBefore" class='APLSSortPropertiesList'>
                <? foreach ($arResult['HLBeforeProperties'] as $propertys): ?>
                    <div class="APLSSortableElement"
                         sortId = "<?= $propertys['SORT']?>"
                         sortXML_ID = "<?= $propertys['XML_ID']?>"
                         nameProperty = "<?= $propertys['NAME'] ?>"
                         IDProperty = "<?= $propertys['ID'] ?>"
                    >
                        <div class="sort-handle">
                            <div class="icon-bar"></div>
                            <div class="icon-bar"></div>
                            <div class="icon-bar"></div>
                        </div>
                        <div class="text">
                            <span>[<?= $propertys['ID'] ?>] <?= $propertys['NAME'] ?></span>
                        </div>
                    </div>
                <? endforeach; ?>
            </td>
            <td id="APLSSortPropertiesListAll" class='APLSSortPropertiesList'>
                <? foreach ($arResult['AllProperties'] as $propertys): ?>
                    <div class="APLSSortableElement"
                         sortId = "3000"
                         sortXML_ID = "<?= $propertys['XML_ID']?>"
                         nameProperty = "<?= $propertys['NAME'] ?>"
                         IDProperty = "<?= $propertys['ID'] ?>"
                    >
                        <div class="sort-handle">
                            <div class="icon-bar"></div>
                            <div class="icon-bar"></div>
                            <div class="icon-bar"></div>
                        </div>
                        <div class="text">
                            <span>[<?= $propertys['ID'] ?>] <?= $propertys['NAME'] ?></span>
                        </div>
                    </div>
                <? endforeach; ?>
            </td>
            <td id="APLSSortPropertiesListAfter" class='APLSSortPropertiesList'>
                <? foreach ($arResult['HLAfterProperties'] as $propertys): ?>
                    <div class="APLSSortableElement"
                         sortId = "<?= $propertys['SORT']?>"
                         sortXML_ID = "<?= $propertys['XML_ID']?>"
                         nameProperty = "<?= $propertys['NAME'] ?>"
                         IDProperty = "<?= $propertys['ID'] ?>"
                    >
                        <div class="sort-handle">
                            <div class="icon-bar"></div>
                            <div class="icon-bar"></div>
                            <div class="icon-bar"></div>
                        </div>
                        <div class="text">
                            <span>[<?= $propertys['ID'] ?>] <?= $propertys['NAME'] ?></span>
                        </div>
                    </div>
                <? endforeach; ?>
            </td>
        </tr>
    </table>
</form>
<div class="scrollUp"><img src="/bitrix/components/apls/sort.properties/templates/.default/logos/up-arrow-5_icon-icons.com_69823.png" alt="Кнопка"></div>
<script type="text/javascript">
    BX.bind(BX("qBefore"), "keyup", BX.delegate(BX.BocFormSubmit, BX));
    BX.bind(BX("qAll"), "keyup", BX.delegate(BX.BocFormSubmit, BX));
    BX.bind(BX("qAfter"), "keyup", BX.delegate(BX.BocFormSubmit, BX));
    BX.bind(BX("APLSSortPropertiesListAllqform"), "keydown", BX.delegate(BX.NoEnter, BX));
</script>