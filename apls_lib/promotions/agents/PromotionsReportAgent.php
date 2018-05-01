<?php
/**
 * Created by PhpStorm.
 * User: melkov_a
 * Date: 19.04.2018
 * Time: 9:19
 */

include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionRevisionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/classes/PromotionImageHelper.php";

class PromotionsReportAgent
{
    const STOPSHOWARRAY = ["stop_from", "show_from"];
    private $currentDateTime = null;
    private $preDateTime = null;
    private $whereObj = null;

    public function __construct() {
        if (!$this->currentDateTime || !$this->preDateTime || !$this->whereObj) {
            $objDateTime = new \Bitrix\Main\Type\DateTime();
            $this->currentDateTime = $objDateTime->format("Y-m-d H:i:s");
            $objDateTime->add("-1 hour");
            $this->preDateTime = $objDateTime->format("Y-m-d H:i:s");
            $this->whereObj = new MySQLWhereString();
            $this->whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "disable",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    "0")
            );
            $this->whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    "vk_activity",
                    MySQLWhereElementString::OPERATOR_B_EQUAL,
                    "1")
            );
        }
    }

    /** Функция возвращает двумерный массив с акциями которые необходимо остановить (stop_from) и отображать (show_from)
     * @return array
     */
    private function getStopShowFromReport () {
        foreach (self::STOPSHOWARRAY as $date) {
            $id[0] = $this->whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    $date,
                    MySQLWhereElementString::OPERATOR_B_LESS_OR_EQUAL,
                    $this->currentDateTime)
            );
            $id[1] = $this->whereObj->addElement(
                MySQLWhereElementString::getBinaryOperationString(
                    $date,
                    MySQLWhereElementString::OPERATOR_B_MORE,
                    $this->preDateTime)
            );
            $result = PromotionRevisionModel::getElementList($this->whereObj);
            foreach ($result as $element) {
                if($element instanceof PromotionRevisionModel) {
                    $resultArray[$date][$element->getId()]["PROMO_ID"] = $element->getFieldValue("promotion");
                    $resultArray[$date][$element->getId()]["TEXT"] = $element->getFieldValue("vk_text");
                }
            }
            //Удаляем блоки whereObj, чтобы они не появились в последующих запросах
            foreach ($id as $i) {
                $this->whereObj->removeBlock($i);
            }
        }
            return $resultArray;
    }

    /** Функция возвращает двумерный массив с акциями которые необходимо начинать (start_from) при незаполненном поле "Отображать с"
     * @return array
     */
    private function getStartFromReport () {
        $id[0] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "show_from",
                MySQLWhereElementString::OPERATOR_U_NULL)
        );
        $id[1] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "start_from",
                MySQLWhereElementString::OPERATOR_B_LESS_OR_EQUAL,
                $this->currentDateTime)
        );
        $id[2] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "start_from",
                MySQLWhereElementString::OPERATOR_B_MORE,
                $this->preDateTime)
        );
        $result = PromotionRevisionModel::getElementList($this->whereObj);
        foreach ($result as $element) {
            if($element instanceof PromotionRevisionModel) {
                $resultArray["start_from"][$element->getId()]["PROMO_ID"] = $element->getFieldValue("promotion");
                $resultArray["start_from"][$element->getId()]["TEXT"] = $element->getFieldValue("vk_text");
            }
        }
        foreach ($id as $i) {
            $this->whereObj->removeBlock($i);
        }
            return $resultArray;
    }

    /** Функция возвращает двумерный массив с акциями которые необходимо отображать (apply_from), когда зполнено только поле "Вступает в силу"
     * @return array
     */
    private function getApplyFromReport () {
        $id = array();
        $id[0] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "show_from",
                MySQLWhereElementString::OPERATOR_U_NULL)
        );
        $id[1] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "start_from",
                MySQLWhereElementString::OPERATOR_U_NULL)
        );
        $id[2] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "apply_from",
                MySQLWhereElementString::OPERATOR_B_LESS_OR_EQUAL,
                $this->currentDateTime)
        );
        $id[3] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "apply_from",
                MySQLWhereElementString::OPERATOR_B_MORE,
                $this->preDateTime)
        );
        $result = PromotionRevisionModel::getElementList($this->whereObj);
        foreach ($result as $element) {
            if($element instanceof PromotionRevisionModel) {
                $resultArray["apply_from"][$element->getId()]["PROMO_ID"] = $element->getFieldValue("promotion");
                $resultArray["apply_from"][$element->getId()]["TEXT"] = $element->getFieldValue("vk_text");
            }
        }
        foreach ($id as $i) {
            $this->whereObj->removeBlock($i);
        }
        return $resultArray;

    }

    /** Функция возвращает двумерный массив с акциями по всем остальным условиям
     * @return array
     */
    private function getApplyShowFromReport () {
        $id = array();
        $id[0] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "apply_from",
                MySQLWhereElementString::OPERATOR_B_LESS_OR_EQUAL,
                $this->currentDateTime)
        );
        $id[1] = $this->whereObj->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "apply_from",
                MySQLWhereElementString::OPERATOR_B_MORE,
                $this->preDateTime)
        );
        $whereORApplyShowStart = new MySQLWhereString(MySQLWhereString::OR_BLOCK);
        $id[2] = $whereORApplyShowStart->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "show_from",
                MySQLWhereElementString::OPERATOR_B_MORE,
                $this->currentDateTime)
        );
        $whereANDApplyShowStart = new MySQLWhereString();
        $id[3] = $whereANDApplyShowStart->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "show_from",
                MySQLWhereElementString::OPERATOR_U_NULL)
        );
        $id[4] = $whereANDApplyShowStart->addElement(
            MySQLWhereElementString::getBinaryOperationString(
                "start_from",
                MySQLWhereElementString::OPERATOR_B_MORE,
                $this->currentDateTime
            )
        );
        $whereORApplyShowStart->addBlock($whereANDApplyShowStart);
        $this->whereObj->addBlock($whereORApplyShowStart);
        $result = PromotionRevisionModel::getElementList($this->whereObj);
        foreach ($result as $element) {
            if($element instanceof PromotionRevisionModel) {
                $resultArray["apply_show_from"][$element->getId()]["PROMO_ID"] = $element->getFieldValue("promotion");
                $resultArray["apply_show_from"][$element->getId()]["TEXT"] = $element->getFieldValue("vk_text");
            }
        }
        foreach ($id as $i) {
            $this->whereObj->removeBlock($i);
        }
            return $resultArray;
    }

    /** Проверяет изменился ли текст акции с последней ревизии
     * @param $id - ID акции
     * @return string Возвращает текст для размещения
     */
    private function checkRevisionText ($id) {
        $promotion = new PromotionModel($id);
        $curentRevision =  $promotion->getCurrentRevision();
        $previosRevision = $promotion->getPreviousRevision();
        if ($previosRevision->getId() == "") {
            $text = "<b>Текст для размещения: </b>".$curentRevision->getFieldValue("vk_text")."<br>";
        } else {
            if ($curentRevision->getFieldValue("vk_text") == $previosRevision->getFieldValue("vk_text")) {
                $text = "<b>Текст не изменился: </b>".$curentRevision->getFieldValue("vk_text")."<br>";
            } else {
                $text = "<b>Новый текст для размещения: </b>".$curentRevision->getFieldValue("vk_text")."<br>";
            }
        }
        return $text;
    }

    /** Проверяет изменилась ли картинка для акции
     * @param $id - ID акции
     * @return string Возвращает ссылу на картинку
     */
    private  function checkRevisionImages ($id) {
        $promotion = new PromotionModel($id);
        $curentRevisionId =  $promotion->getCurrentRevisionId();
        $previosRevisionId = $promotion->getPreviousRevisionId();
        $typeId = "C3AHB6IHA1EKQ-BJEDBBGLF4A1P-BOHKB7EQH";
        $image = new PromotionImageHelper();
        $revision = new  PromotionRevisionModel($curentRevisionId);
        $images = $revision->getImages();
        if($images != NULL) {
            $imgId = $images[$typeId]->getId();
            if ($image->checkRevisionsId($curentRevisionId, $previosRevisionId, $typeId) == TRUE) {
                $text = "<b>Необходимо изменить картинку: </b><a href='".$image->getBigImagePath($imgId)."'>Ссылка на фото</a>";
            } else {
                $text = "<b>Картинка не изменилась: </b><a href='".$image->getBigImagePath($imgId)."'>Ссылка на фото</a>";
            }
        } else {
            $text = "<b>Картинка не прикреплена к акции!</b>";
        }
        return $text;
    }

    private function getPromotionName ($id) {
        $promotion = new PromotionModel($id);
        return $promotion->getFieldValue("title");
    }

    /** Возвращает html для e-meil
     * @return string
     */
    public function getMessage () {
        $html = "";
        $html .= "<html>";
        $html .= "<head>";
        $html .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
        $html .= "<style type='text/css'>
                    #alert_message .greenText {
                    color: #0a962f;
                    font-size: 14px;
                    font-weight: 600;
                    vertical-align: bottom;
                    }
                    #alert_message .redText {
                    color: #991d00;
                    font-size: 14px;
                    font-weight: 600;
                    vertical-align: bottom;
                    }
                    #alert_message td {
                    font-size: 14px;
                    font-weight: normal;
                    vertical-align: bottom;
                    }
                    #alert_message th:nth-child(1), 
                    tr:nth-child(1) {
                    width: 100px;
                    }
                    #alert_message th:nth-child(2), 
                    tr:nth-child(2) {
                    width: 100px;
                    }
                    #alert_message .tableInfo {
                    width: 400px;
                    word-wrap: break-word;
                    }
                </style>";
        $html .= "<title>";
        $html .= "Отчет об акциях для размещения в ВК в период с ".$this->preDateTime." по ".$this->currentDateTime.".";
        $html .= "</title>";
        $html .= "</head>";
        $html .= "<body>";
        $html .= "<table width='100%' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF'><tr><td>";
        $html .= "<table id='alert_message' cellpadding='20' cellspacing='0' width='600px' align='center'>";
        $html .= "<tr>
                      <th width='100px'>Акция</th>
                      <th width='100px'>Действие</th>
                      <th width='400px' class='tableInfo'>Комментарий</th>
                  </tr>";
        if ($this->getStopShowFromReport ()) {
            $resArray = $this->getStopShowFromReport ();
            if ($resArray["stop_from"]) {
                foreach ($resArray["stop_from"] as $key=>$stop) {
                    $html .= "<tr>
                                  <td>".$this->getPromotionName($stop['PROMO_ID'])."</td>
                                  <td class='redText'>Убрать с размещения</td>
                                  <td class='tableInfo'>Акция завершила действие</td>
                              </tr>";
                }
            } elseif ($resArray["show_from"]) {
                foreach ($resArray["show_from"] as $key=>$show) {
                    $html .= "<tr>
                                  <td>".$this->getPromotionName($show['PROMO_ID'])."</td>
                                  <td class='greenText'>Начать размещение</td>
                                  <td class='tableInfo'>".$this->checkRevisionText($show['PROMO_ID']). $this->checkRevisionImages($show['PROMO_ID'])."</td>
                              </tr>";
                }
            }
        }
        if ($this->getStartFromReport ()) {
            $resArray = $this->getStartFromReport ();
            foreach ($resArray["start_from"] as $key=>$start) {
                $html .= "<tr>
                              <td>".$this->getPromotionName($start['PROMO_ID'])."</td>
                              <td class='redText'>Разместить/обновить пост ВК</td>
                              <td class='tableInfo'>".$this->checkRevisionText($start['PROMO_ID']). $this->checkRevisionImages($start['PROMO_ID'])."</td>
                          </tr>";
            }
        }
        if ($this->getApplyFromReport ()) {
            $resArray = $this->getApplyFromReport ();
            foreach ($resArray["apply_from"] as $key=>$apply) {
                $html .= "<tr>
                              <td>".$this->getPromotionName($apply['PROMO_ID'])."</td>
                              <td class='greenText'>Разместить/обновить пост ВК</td>
                              <td class='tableInfo'>".$this->checkRevisionText($apply['PROMO_ID']). $this->checkRevisionImages($apply['PROMO_ID'])."</td>
                          </tr>";
            }
        }
        if ($this->getApplyShowFromReport ()) {
            $resArray = $this->getApplyShowFromReport ();
            foreach ($resArray["apply_show_from"] as $key=>$apply) {
                $html .= "<tr>
                              <td>".$this->getPromotionName($apply['PROMO_ID'])."</td>
                              <td class='greenText'>Разместить/обновить пост ВК</td>
                              <td class='tableInfo'>".$this->checkRevisionText($apply['PROMO_ID']). $this->checkRevisionImages($apply['PROMO_ID'])."</td>
                          </tr>";
            }
        }
        $html .= "</table>";
        $html .= "</tr></td></table>";
        $html .= "</body>";
        $html .= "</html>";
        return  $html;
    }
}