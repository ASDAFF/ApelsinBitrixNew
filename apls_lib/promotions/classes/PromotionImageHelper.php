<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/promotions/model/PromotionImageTypeModel.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/main/textgenerator/ID_GENERATOR.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/files/ResizeImage.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/apls_lib/files/FTPConnector.php";

class PromotionImageHelper
{
    const FTP_SERVER = "apls.compuproject.com";
    const FTP_USER = "compuprojectapls";
    const FTP_PASS = "NS6odoPvrr!ME";
    const B_IMG_PATH = "b_img";
    const S_IMG_PATH = "s_img";
    const S_IMG_SIZE = "100";
    const SERVER_URL = "http://" . self::FTP_SERVER;

    const RESIZE_TYPE_EXACT = "exact";
    const RESIZE_TYPE_MAX_WIDTH = "maxwidth";
    const RESIZE_TYPE_MAX_HEIGHT = "maxheight";
    const RESIZE_TYPE_PLACEDIN = "placedin";
    const RESIZE_TYPE_CROP = "crop";

    private $ftpConn = null;
    private $uploadDir = "";

    public function __construct()
    {
        $this->ftpConn = new FTPConnector(self::FTP_SERVER, self::FTP_USER, self::FTP_PASS);
        $this->uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/apls_temp/';
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir);
        }
    }

    public function uploadImage(string $name, string $tmpName, string $typeId, string $resizeType = self::RESIZE_TYPE_MAX_HEIGHT)
    {
        $imageType = new PromotionImageTypeModel($typeId);
        if ($imageType->getId() != "") {
            $path_info = pathinfo($name);
            $extension = "." . $path_info["extension"];
            $bName = ID_GENERATOR::generateID() . $extension;
            $sName = ID_GENERATOR::generateID() . $extension;
            $bImg = $this->uploadDir . $bName;
            $sImg = $this->uploadDir . $sName;
            $title = str_replace($extension, "", $name);
            if (move_uploaded_file($tmpName, $bImg)) {
                $resizeImage = new ResizeImage($bImg);
                $resizeImage->resizeTo(self::S_IMG_SIZE, self::S_IMG_SIZE, $resizeType);
                $resizeImage->saveImage($sImg);
                $connId = $this->ftpConn->getConn();
                $this->createFtpDirs($typeId);
                ftp_put($connId, $typeId . "/" . self::B_IMG_PATH . "/" . $bName, $bImg, FTP_BINARY);
                ftp_put($connId, $typeId . "/" . self::S_IMG_PATH . "/" . $sName, $sImg, FTP_BINARY);
                unlink($bImg);
                unlink($sImg);
                return PromotionImageModel::createElement(
                    array(
                        'b_file_name' => $bName,
                        's_file_name' => $sName,
                        'name' => $title,
                        'type' => $typeId
                    )
                );
            }
        }
        return false;
    }

    public function deleteImage(string $imageId): bool
    {
        $image = new PromotionImageModel($imageId);
        if ($image->getFieldValue('type') !== NULL) {
            $type = $image->getFieldValue('type');
            $imgB = $image->getFieldValue('b_file_name');
            $imgS = $image->getFieldValue('s_file_name');
            ftp_delete($this->ftpConn->getConn(), $type . "/" . self::B_IMG_PATH . "/" . $imgB);
            ftp_delete($this->ftpConn->getConn(), $type . "/" . self::S_IMG_PATH . "/" . $imgS);
            return PromotionImageModel::deleteElement($image->getId());
        }
        return false;
    }

    public function createImageType($alias, $typeName)
    {
        $typeId = PromotionImageTypeModel::createElement(array('alias' => $alias, 'type' => $typeName));
        $this->createFtpDirs($typeId);
        return $typeId;
    }

    public function deleteImageType(string $typeId)
    {
        $images = PromotionImageModel::getElementList(
            MySQLWhereElementString::getBinaryOperationString(
                'type',
                MySQLWhereElementString::OPERATOR_B_EQUAL,
                $typeId
            )
        );
        foreach ($images as $image) {
            if ($image instanceof PromotionImageModel) {
                $this->deleteImage($image->getId());
            }
        }
        PromotionImageTypeModel::deleteElement($typeId);
        ftp_rmdir($this->ftpConn->getConn(), $typeId . "/" . self::B_IMG_PATH . "/");
        ftp_rmdir($this->ftpConn->getConn(), $typeId . "/" . self::S_IMG_PATH . "/");
        ftp_rmdir($this->ftpConn->getConn(), $typeId);
    }

    public static function getBigImagePath($imageId)
    {
        $image = new PromotionImageModel($imageId);
        return self::SERVER_URL . "/" .
            $image->getFieldValue('type') . "/" .
            self::B_IMG_PATH . "/" .
            $image->getFieldValue('b_file_name');
    }

    public static function getSmallImagePath($imageId)
    {
        $image = new PromotionImageModel($imageId);
        return self::SERVER_URL . "/" .
            $image->getFieldValue('type') . "/" .
            self::S_IMG_PATH . "/" .
            $image->getFieldValue('s_file_name');
    }

    private function createFtpDirs($typeId)
    {
        $connId = $this->ftpConn->getConn();
        $rootDir = ftp_nlist($connId, ".");
        if (!in_array($typeId, $rootDir)) {
            ftp_mkdir($connId, $typeId);
        }
        $typeDir = ftp_nlist($connId, $typeId);
        if (!in_array(self::S_IMG_PATH, $typeDir)) {
            ftp_mkdir($connId, $typeId . "/" . self::S_IMG_PATH);
        }
        if (!in_array(self::B_IMG_PATH, $typeDir)) {
            ftp_mkdir($connId, $typeId . "/" . self::B_IMG_PATH);
        }
    }
}