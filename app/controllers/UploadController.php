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

    //photo update
    public function photo_update(){
         //save path
        $upload_folder  = $_SERVER['DOCUMENT_ROOT'];
        $upload_folder .= "/uploads/";

        $image = $this -> crop_and_save($upload_folder, $_FILES['file'], 0, 250, 4096);

        echo $image;
    }

    //change banner
    public function change_banner(){
         //save path
        $upload_folder  = $_SERVER['DOCUMENT_ROOT'];
        $upload_folder .= "/uploads/";

        $image = $this -> lossless_save($upload_folder, $_FILES['file'], 0);

        //save to db
        $profile = new Profiles;
        $profile -> update_or_bake_profile_data(array('column_name' => 'banner', 'value' => $image, 'profile_setup' => false));

        echo $image;
    }

    //set avatar 
    public function set_avatar(){
        $profile = new profiles;
        $profile -> update_or_bake_profile_data(array('column_name' => 'profile_picture', 'value' => Input::get('avatarname'), 'profile_setup' => false));
    }

    //change profile picture
    public function change_profile_picture(){
         //save path
        $upload_folder  = $_SERVER['DOCUMENT_ROOT'];
        $upload_folder .= "/uploads/";

        $image = $this -> crop_and_save($upload_folder, $_FILES['file'], 0, 500, 4096);

        //save to db
        $profile = new Profiles;
        $profile -> update_or_bake_profile_data(array('column_name' => 'profile_picture', 'value' => $image, 'profile_setup' => false));

        echo $image;
    }

    private function lossless_save($save_path, $file, $type){
        //save path
        $upload_folder = $save_path;
        $RandomNumber  = rand(0, 9999999999); 
        $id            = Session::get('id');

        //Get file extension from Image name, this will be added after random name
        $ImageExt = substr($file['name'], strrpos($file['name'], '.'));
        $ImageExt = str_replace('.','',$ImageExt);
        
        //remove extension from filename
        $ImageName      = preg_replace("/\\.[^.\\s]{3,4}$/", "", $file['name']); 
        
        //Construct a new name with random number and extension.
        $NewFileName = $ImageName.'-'.$RandomNumber.$id.'.'.$ImageExt;
        $file_tmp    = $file['tmp_name'];

        move_uploaded_file($file_tmp, $upload_folder.$NewFileName);
        return $NewFileName;
    }

    //crop and save
    private function crop_and_save($save_path, $file, $type, $ThumbSquareSize, $BigImageMaxSize){
        //save path
        $upload_folder  = $save_path;
        $_FILES['file'] = $file;

        ##########################SETTINGS###################################
        $ThumbPrefix            = "thumb_"; //Normal thumb Prefix
        $DestinationDirectory   = $upload_folder;
        $Quality                = 100;      //jpeg quality
        #####################################################################
        
        
        // check $_FILES['file'] not empty
        if(!isset($_FILES['file']) || !is_uploaded_file($_FILES['file']['tmp_name']))
        {
                die('Something wrong with uploaded file, something missing!'); // output error when above checks fail.
        }
        
        // Random number will be added after image name
        $RandomNumber   = rand(0, 9999999999); 

        $ImageName      = str_replace(' ','-',strtolower($_FILES['file']['name'])); //get image name
        $ImageSize      = $_FILES['file']['size']; // get original image size
        $TempSrc        = $_FILES['file']['tmp_name']; // Temp name of image file stored in PHP tmp folder
        $ImageType      = $_FILES['file']['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.

        //Let's check allowed $ImageType, we use PHP SWITCH statement here
        switch(strtolower($ImageType))
        {
            case 'image/png':
                //Create a new image from file 
                $CreatedImage =  imagecreatefrompng($_FILES['file']['tmp_name']);
                break;
            case 'image/gif':
                $CreatedImage =  imagecreatefromgif($_FILES['file']['tmp_name']);
                break;          
            case 'image/jpeg':
            case 'image/pjpeg':
                $CreatedImage = imagecreatefromjpeg($_FILES['file']['tmp_name']);
                break;
            default:
                die('Unsupported File!'); //output error and exit
        }
        
        //PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
        //Get first two values from image, width and height. 
        //list assign svalues to $CurWidth,$CurHeight
        list($CurWidth,$CurHeight)=getimagesize($TempSrc);
        
        //Get file extension from Image name, this will be added after random name
        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);
        
        //remove extension from filename
        $ImageName      = preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName); 
        
        //Construct a new name with random number and extension.
        $NewImageName = $ImageName.'-'.$RandomNumber.'.'.$ImageExt;
        
        //set the Destination Image
        $thumb_DestRandImageName    = $DestinationDirectory.$ThumbPrefix.$NewImageName; //Thumbnail name with destination directory
        $DestRandImageName          = $DestinationDirectory.$NewImageName; // Image with destination directory

        //Resize image to Specified Size by calling resizeImage function.
        if($this -> resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$ImageType))
        {
            //Create a square Thumbnail right after, this time we are using cropImage() function
            if(!$this -> cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$ImageType))
                {
                    echo 'Error Creating thumbnail';
                }
                
            //updating 

            return $NewImageName;
           


        }else{
            die('Resize Error'); //output error
        } 
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