<?php
if (empty($_SERVER["HTTP_REFERER"])) die();
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


if (CModule::IncludeModule("catalog")) {
    $db_res = CCatalogProduct::GetList(
        array("QUANTITY" => "DESC"),
        array("VAT_ID" => $_POST["ndsId"])
    );
    $product_names = [];
    while ($ar_res = $db_res->Fetch()) {
        $product_names[$ar_res["ELEMENT_XML_ID"]]["ID"] = $ar_res["ID"];
        $product_names[$ar_res["ELEMENT_XML_ID"]]["ELEMENT_NAME"] = $ar_res["ELEMENT_NAME"];
    }
    $products_chunks = array_chunk($product_names, '50');
    ?>
    <?
    if (!empty($products_chunks)) {
        ?>
        <div class="productTable">
            <div class="row counter">
                <?if($_POST["counter"]<=5){?>
                    <div class="cout_element">1</div>
                    <div class="cout_element">2</div>
                    <div class="cout_element">3</div>
                    <div class="cout_element">4</div>
                    <div class="cout_element">5</div>
                    <div class="cout_element point">...</div>
                    <div class="cout_element"><?= count($products_chunks) + 1 ?></div>
                <?}else{?>
                    <div class="cout_element">1</div>
                    <div class="cout_element point">...</div>
                    <?for ($i = $_POST["counter"] - 3; $i <= $_POST["counter"] + 3; $i++):?>
                        <div class="cout_element"><?= $i ?></div>
                    <?endfor; ?>
                    <div class="cout_element point">...</div>
                    <div class="cout_element"><?= count($products_chunks) + 1 ?></div>
                <?}?>
            </div>
            <div class="row header">
                <div class="col counter header">№П/П</div>
                <div class="col activ header">Активность</div>
                <div class="col header">Внешний код</div>
                <div class="col header">Название</div>
            </div>
            <?
            $counter = 1 ?>
            <?
            foreach ($products_chunks[$_POST["counter"]] as $key => $value):?>
                <div class="row header">
                    <div class="col counter"><?= $counter ?></div>
                    <div class="col activ"><?= getElementActivity($value["ID"]) ?></div>
                    <div class="col"><?= $key ?></div>
                    <div class="col"><?= $value["ELEMENT_NAME"] ?></div>
                </div>
                <?
                $counter++ ?>
            <?endforeach; ?>
        </div>
    <?
    } else {
        ?>
        <p>Товаров с данной ставкой не найдено</p>
    <?
    } ?>
    <?
}

function getElementActivity($elementId)
{
    if (CModule::IncludeModule("iblock")) {
        $res = CIBlockElement::GetByID($elementId);
        if ($ar_res = $res->GetNext()) {
            return $ar_res["ACTIVE"];
        }
    }
}