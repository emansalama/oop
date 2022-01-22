<?php
session_start();
require 'dbClass.php';
require 'ValidatorClass.php';

class User
{
    private $title;
    private $content;
    private $image;

    // function __construct( )
    // {
  
    // }

    public function Register($val1, $val2 , $val3 )
    {

        ## Create Obj From Validator  ......
        $validator = new Validator();
       
        # Clean ....
        $this->title   = $validator->Clean($val1);
        $this->content    = $validator->Clean($val2);
        $this->image    = $validator->Clean($val3);

    

        # Validation .....
        $errors = [];

        if (!Validate($title, 1)) {
            $errors['Title'] = 'Required Field';
        } elseif (!Validate($title, 6)) {
            $errors['Title'] = 'Invalid String';
        }
        
           # Validate content ...
    if (!Validate($content, 1)) {
        $errors['Content'] = 'Required Field';
    } elseif (!Validate($content, 3, 50)) {
        $errors['Content'] = 'Length Must be  >= 50  CHARS';
    }

     # Validate Image
     if (!Validate($_FILES['image']['name'], 1)) {
        $errors['Image'] = 'Field Required';
    } else {
        $ImgTempPath = $_FILES['image']['tmp_name'];
        $ImgName = $_FILES['image']['name'];

        $extArray = explode('.', $ImgName);
        $ImageExtension = strtolower(end($extArray));

        if (!Validate($ImageExtension, 7)) {
            $errors['Image'] = 'Invalid Extension';
        } else {
            $FinalName = time() . rand() . '.' . $ImageExtension;
        }
    }


     
   
    
       # CHECK ERRORS ...   
        if (count($errors) > 0) {
            $Message = $errors;
        }else{
         # Create DB Obj ...
         $db = new DB();

         $disPath = './uploads/' . $FinalName;

         if (move_uploaded_file($ImgTempPath, $disPath)) {
        
        
             $sql = "insert into blog (title,content,image) values ('$title','$content','$FinalName')";
             $op  = $db->doQuery($sql);
 
             if ($op) {
                 $Message = ['Message' => 'Raw Inserted'];
             } else {
                 $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
             }
         }else{
             $Message = ['Error Try Again !!!!!'];
         }
 
        }
      
        $_SESSION['Message'] = $Message;
    
    }



    public function info(){
        echo 'Name .....';
    }
}

?>
