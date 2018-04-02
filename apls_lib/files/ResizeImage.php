<?php
/**
 * Изменение размера изображения
 *
 * Можно изменять размер точно
 * Максимальная ширина при сохранинии соотношения сторон
 * Максимальная высота при сохранинии соотношения сторон
 * Авто при сохранинии соотношения сторон
 * Обрезка
 *
 * @author olga
 */
class ResizeImage {
    
    private $ext;          // расширение
    private $image;        // дескриптор загруженного изображения 
    private $newImage;     //для измененного изображения
    private $origWidth;    // ширина загруженного изображения
    private $origHeight;   // высота загруженного изображения
    private $resizeWidth;  // новая ширина 
    private $resizeHeight; // новая высота
    
    /**
     * 
     * @param type $filename - изображение
     */
    public function __construct( $filename ) {
        if (file_exists($filename)) {
            $this->setImage( $filename );
        } else {
            echo 'Изображение не найденo, выберите другое';
        }
    }

    /**
     *  Получить расширение файла  
     * @param string $filename
     */
    private function setImage( $filename ) {
        $size = getimagesize($filename);
        $this->ext = $size['mime'];
        switch ($this->ext) {
            // JPG
            case 'image/jpg':
            case 'image/jpeg':
                $this->image = imagecreatefromjpeg($filename);
                break;
            // GIF
            case 'image/gif':
                $this->image = @imagecreatefromgif($filename);
                break;
            // PNG
            case 'image/png':
                $this->image = @imagecreatefrompng($filename);
                break;
            default:
                throw new Exception("Файл не является изображением, выберите другое", 1);
        }
        $this->origWidth = imagesx($this->image);
        $this->origHeight = imagesy($this->image);
    }

    /**
     * Сохранить изображение
     * @param  String[type] $savePath - путь для сохранения нового изображения
     * @param  string $imageQuality -   качество сохраняемого нового изображения
     * @return сохраненное изображение
     */
    public function saveImage($savePath, $imageQuality="100", $download = false) {
        switch ($this->ext) {
            case 'image/jpg':
            case 'image/jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->newImage, $savePath, $imageQuality);
                }
                break;
            case 'image/gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->newImage, $savePath);
                }
                break;
            case 'image/png':
                $invertScaleQuality = 9 - round(($imageQuality/100) * 9);
                if (imagetypes() & IMG_PNG) {
                    imagepng($this->newImage, $savePath, $invertScaleQuality);
                }
                break;
        }
        if ($download) {
            header('Content-Description: File Transfer');
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename= ".$savePath."");
            readfile($savePath);
        }
        imagedestroy($this->newImage);
    }

    /**
     * Ресайз
     * @param  int $width -  максимальная ширина изображения
     * @param  int $height - максимальная высота изображения
     * @param  string $resizeOption - соотношение длины-ширины
     * @return 
     */
    public function resizeTo( $width, $height, $resizeOption = 'placedin' ) {
        if(($this->origWidth > $this->origHeight && $width < $height) || ($this->origWidth < $this->origHeight && $width > $height)) {
            $tempWidth = $width;
            $width = $height;
            $height = $tempWidth;
        }
        switch (strtolower($resizeOption)) {
            case 'exact': 
                $this->resizeWidth = $width;
                $this->resizeHeight = $height;
                break;
            case 'maxwidth':
                $this->resizeWidth  = $width;
                $this->resizeHeight = $this->resizeHeightByWidth($width);
                break;
            case 'maxheight':
                $this->resizeWidth  = $this->resizeWidthByHeight($height);
                $this->resizeHeight = $height;
                break;
            case 'placedin':
                if($this->resizeHeightByWidth($width) <= $height) {
                    $this->resizeWidth  = $width;
                    $this->resizeHeight = $this->resizeHeightByWidth($width);
                } else {
                    $this->resizeWidth  = $this->resizeWidthByHeight($height);
                    $this->resizeHeight = $height;
                }
                break;
            case 'crop': 
                $heightRatio = $this->origHeight / $height;  
                $widthRatio  = $this->origWidth /  $height;  
                if ($heightRatio < $widthRatio) {  
                    $optimalRatio = $heightRatio;  
                } else {  
                    $optimalRatio = $widthRatio;  
                }  
                $this->resizeWidth = $height / $optimalRatio;  
                $this->resizeHeight  = $width  / $optimalRatio;  
            default:
                if ($this->origWidth > $width || $this->origHeight > $height) {
                    if ( $this->origWidth > $this->origHeight ) {
                        $this->resizeHeight = $this->resizeHeightByWidth($width);
                        $this->resizeWidth  = $width;
                    } else if ( $this->origWidth < $this->origHeight ) {
                        $this->resizeWidth  = $this->resizeWidthByHeight($height);
                        $this->resizeHeight = $height;
                    }  else {
                        $this->resizeWidth = $width;
                        $this->resizeHeight = $height;	
                    }
                } else {
                    $this->resizeWidth = $width;
                    $this->resizeHeight = $height;
                }
                break;
        }

        $this->newImage = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
        // для png (сораняем прозрачность)
        if ($this->ext == 'image/png') {
            // отключаем режим сопряжения цветов (убираем черный фон)
            imagealphablending($this->newImage, false);
            // сохраняем альфа канал в выходной файл
            imagesavealpha($this->newImage, true);
        }
        //для gif (если надо)
//        if ($this->ext == 'image/gif') {
//            // получаем прозрачный цвет
//            $transparent_source_index = imagecolortransparent($this->image);
//            // проверяем наличие прозрачности
//            if ($transparent_source_index !== -1) {
//                // получаем цвет, соответствующий заданному индексу.
//                $transparent_color = imagecolorsforindex($this->image, $transparent_source_index);
//                //Добавляем цвет в палитру нового изображения 
//                $transparent_destination_index = imagecolorallocate($this->newImage, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
//                 // устанавливаем его как прозрачный
//                imagecolortransparent($this->newImage, $transparent_destination_index);
//                //На всякий случай заливаем фон этим цветом
//                imagefill($this->newImage, 0, 0, $transparent_destination_index);
//            }
//        } 
        imagecopyresampled($this->newImage, $this->image, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->origWidth, $this->origHeight);
        // Если параметр $option = 'crop'(обрезка), то создаем соответствующий холст
        if ($resizeOption == 'crop') {  
            $this->crop($this->resizeWidth, $this->resizeHeight, $width, $height);  
        } 
    }
    
    /**
     * Oбрезкa
     * @param type $optimalWidth
     * @param type $optimalHeight
     * @param type $newWidth
     * @param type $newHeight
     */
    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight) {  
        // Находим центр - это необходимо для обрезки
        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );  
        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 ); 
        $crop = $this->newImage;  
        // Теперь обрезаем от центра до указанного размера 
        $this->newImage = imagecreatetruecolor($newWidth , $newHeight);  
        imagecopyresampled($this->newImage, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
    }

    /**
     * Максимальная ширина при сохранинии соотношения сторон
     * @param  int $width - Максимальная ширина
     * @return 
     */
    private function resizeHeightByWidth($width) {
        return floor(($width/$this->origWidth)*$this->origHeight);
    }

    /**
     * Максимальная высота при сохранинии соотношения сторон
     * @param  int $height - MМаксимальная высота
     * @return 
     */
    private function resizeWidthByHeight($height) {
        return floor(($height/$this->origHeight)*$this->origWidth);
    }
}
