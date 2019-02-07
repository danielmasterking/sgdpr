<?php 

namespace app\components;

use yii\base\Component;



class Verificar_imagenComponent extends Component{
    
	
    public function init(){
        parent::init();
        
    }
	
    public function esImagen($path)
    {
        $imageSizeArray = getimagesize($path);
        $imageTypeArray = $imageSizeArray[2];
        return (bool)(in_array($imageTypeArray , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)));
    }

    public function Redimenzionar($path,$type){
        switch ($type) {
            case 'image/jpeg':
               $original=imagecreatefromjpeg($path);
                $ancho_original=imagesx($original);
                $alto_original=imagesy($original);
                $copia=imagecreatetruecolor(700, 400);
                imagecopyresampled($copia, $original, 0,0, 0,0, 700, 400,$ancho_original,$alto_original);
                imagejpeg($copia,$path);
            break;

            case 'image/png':
               $original=imagecreatefrompng($path);
                $ancho_original=imagesx($original);
                $alto_original=imagesy($original);
                $copia=imagecreatetruecolor(700, 400);
                imagecopyresampled($copia, $original, 0,0, 0,0, 700, 400,$ancho_original,$alto_original);
                imagepng($copia,$path);
            break;

            case 'image/gif':
               $original=imagecreatefromgif($path);
                $ancho_original=imagesx($original);
                $alto_original=imagesy($original);
                $copia=imagecreatetruecolor(700, 400);
                imagecopyresampled($copia, $original, 0,0, 0,0, 700, 400,$ancho_original,$alto_original);
                imagegif($copia,$path);
            break;
        }
    	
    }

}



?>