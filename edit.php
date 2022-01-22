<?php

require './classes/userClass.php';
#############################################################################
$id = $_GET['id'];

$sql = "select * from blog where id = $id";
$op  = $db->doQuery($sql);



# Code .....

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = Clean($_POST['title']);
    $content = Clean($_POST['content']);
 
    # Validate name ....
    $errors = [];

    # Validate Title
    if (!Validate($title, 1)) {
        $errors['Title'] = 'Required Field';
    } elseif (!Validate($title, 6)) {
        $errors['Title'] = 'Invalid String';
    }

    # Validate Desc ...
    if (!Validate($content, 1)) {
        $errors['Content'] = 'Required Field';
    } elseif (!Validate($content, 3, 30)) {
        $errors['Content'] = 'Length Must be  >= 30  CHARS';
    }


    # Validate Image
    if (Validate($_FILES['image']['name'], 1)) {
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

    if (count($errors) > 0) {
        $Message = $errors;
    } else {
        // DB CODE .....

        if (Validate($_FILES['image']['name'], 1)) {
            $disPath = './uploads/' . $FinalName;

            if (!move_uploaded_file($ImgTempPath, $disPath)) {
                $Message = ['Message' => 'Error  in uploading Image  Try Again '];
            } else {
                unlink('./uploads/' . $BlogData['image']);
            }
        } else {
            $FinalName = $BlogData['image'];
        }

        if (count($Message) == 0) {
            $date = strtotime($date);
            $sql = "update blog set title='$title' , content='$content' ,  image ='$FinalName' where id = $id";

            $op = mysqli_query($con, $sql);

            if ($op) {
                $Message = ['Message' => 'Raw Updated'];
            } else {
                $Message = ['Message' => 'Error Try Again ' . mysqli_error($con)];
            }
        }
        # Set Session ......
        $_SESSION['Message'] = $Message;
        header('Location: index.php');
        exit();
    }
    $_SESSION['Message'] = $Message;
}

             $user = new User();
             $user->Register($title ,  $content, $FinalName );
            echo '<br>';
            if (isset($_SESSION['Message'])) {
                Messages($_SESSION['Message']);
            
                # Unset Session ...
                unset($_SESSION['Message']);
            }
            
            ?>

    


        <div class="card mb-4">

            <div class="card-body">

                <form action="edit.php?id=<?php echo $BlogData['id']; ?>" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="exampleInputName">Title</label>
                        <input type="text" class="form-control" id="exampleInputName" name="title" aria-describedby=""
                            placeholder="Enter Title" value="<?php echo $BlogData['title']; ?>">
                    </div>


                    <div class="form-group">
                        <label for="exampleInputName"> Content</label>
                        <textarea class="form-control" id="exampleInputName"
                            name="content"> <?php echo $BlogData['content']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputName">Image</label>
                        <input type="file" name="image">
                    </div>

                    <img src="./uploads/<?php echo $BlogData['image']; ?>" alt="" height="50px" width="50px"> <br>


                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
            </div>
        </div>
 


