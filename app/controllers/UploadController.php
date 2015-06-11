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
        sleep(5);
        //loading session   
        //save path
        $upload_folder  = $_SERVER['DOCUMENT_ROOT'];
        $upload_folder .= "/uploads/";


        ############ Edit settings ##############
        $ThumbSquareSize        = 100; //Thumbnail will be 100x100
        $BigImageMaxSize        = 500; //Image Maximum height or width
        $ThumbPrefix            = "thumb_"; //Normal thumb Prefix
        $DestinationDirectory   = $upload_folder;
        $Quality                = 100; //jpeg quality
        ##########################################
        
        
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
            echo $NewImageName;
           


        }else{
            die('Resize Error'); //output error
        }
    }


    // This function will proportionally resize image 
    public function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
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
    public function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
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