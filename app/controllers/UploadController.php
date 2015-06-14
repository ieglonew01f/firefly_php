<?php

class UploadController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Upload Controller
    | Author: lonew01f
    | First commit: 6/11/2015 11:31 PM
    | Describtion: upload controller is responsible for all the aspects of
    | managing and uploading files on to the server eg banner upload/change
    |--------------------------------------------------------------------------
    |
    */
    public function change_banner(){
         //save path
        $upload_folder  = $_SERVER['DOCUMENT_ROOT'];
        $upload_folder .= "/uploads/";

        $file_name    = $_FILES['file']['name'];

        $image = $this -> lossless_save($upload_folder, $_FILES['file']['name'], 0);

        //save to db
        $profile = new Profiles;
        $profile -> update_or_bake_profile_data(array('column_name' => 'banner', 'value' => $image, 'profile_setup' => false));

        echo $image;
    }

    private function lossless_save($save_path, $file_name, $type){
        //save path
        $upload_folder = $save_path;
        $RandomNumber  = rand(0, 9999999999); 
        $id            = Session::get('id');

        //Get file extension from Image name, this will be added after random name
        $ImageExt = substr($file_name, strrpos($file_name, '.'));
        $ImageExt = str_replace('.','',$ImageExt);
        
        //remove extension from filename
        $ImageName      = preg_replace("/\\.[^.\\s]{3,4}$/", "", $file_name); 
        
        //Construct a new name with random number and extension.
        $NewFileName = $ImageName.'-'.$RandomNumber.$id.'.'.$ImageExt;

        $file_size = $_FILES['file']['size'];
        $file_tmp  = $_FILES['file']['tmp_name'];

        move_uploaded_file($file_tmp, $upload_folder.$NewFileName);
        return $NewFileName;
    }


    // This function will proportionally resize image 
    private function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
    {
        //Check Image size is not 0
        if($CurWidth <= 0 || $CurHeight <= 0) 
        {
            return false;
        }
        
        //Construct a proportional size of new image
        $ImageScale         = min($MaxSize/$CurWidth, $MaxSize/$CurHeight); 
        $NewWidth           = ceil($ImageScale*$CurWidth);
        $NewHeight          = ceil($ImageScale*$CurHeight);
        $NewCanves          = imagecreatetruecolor($NewWidth, $NewHeight);
        
        // Resize Image
        if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
        {
            switch(strtolower($ImageType))
            {
                case 'image/png':
                    imagepng($NewCanves,$DestFolder);
                    break;
                case 'image/gif':
                    imagegif($NewCanves,$DestFolder);
                    break;          
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewCanves,$DestFolder,$Quality);
                    break;
                default:
                    return false;
            }
        //Destroy image, frees memory   
        if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
        return true;
        }

    }

    //This function corps image to create exact square images, no matter what its original size!
    private function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
    {    
        //Check Image size is not 0
        if($CurWidth <= 0 || $CurHeight <= 0) 
        {
            return false;
        }
        
        //abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
        if($CurWidth>$CurHeight)
        {
            $y_offset = 0;
            $x_offset = ($CurWidth - $CurHeight) / 2;
            $square_size    = $CurWidth - ($x_offset * 2);
        }else{
            $x_offset = 0;
            $y_offset = ($CurHeight - $CurWidth) / 2;
            $square_size = $CurHeight - ($y_offset * 2);
        }
        
        $NewCanves  = imagecreatetruecolor($iSize, $iSize); 
        if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
        {
            switch(strtolower($ImageType))
            {
                case 'image/png':
                    imagepng($NewCanves,$DestFolder);
                    break;
                case 'image/gif':
                    imagegif($NewCanves,$DestFolder);
                    break;          
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewCanves,$DestFolder,$Quality);
                    break;
                default:
                    return false;
            }
        //Destroy image, frees memory   
        if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
        return true;

        }  
    }

}