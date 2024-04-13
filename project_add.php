<?php
session_start();
if (!isset($_SESSION['designer_logged'])) {
    header("location:index.php");
}
require_once('includes/connection.php');
include("includes/header.php");
include('includes/functions.php');

$designer_id = $_SESSION['designer_id'];
$allCats=getCategories($conn);

?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6 offset-2">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add Project</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="designerID" value="<?php echo $designer_id; ?>">
                            <label for="projectName">project Name</label>
                            <input id="projectName" name="projectName" class="form-control">
                        </div>
                        <div class="form-group">

                            <label for="designCategoryID">Category</label>
                            <select id="designCategoryID" name="designCategoryID" class="form-control">
                                <?php foreach ($allCats as $cat){ ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category']; ?></option>
                                <?php }?>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Request Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="projectImgFileName">Project Image</label>
                            <input type="file" id="projectImgFileName" name="projectImgFileName" class="form-control">
                        </div>


                    </div>
                    <!-- /.card-body -->
                </div>

                <!-- /.card -->
        </div>

    </div>
    <div class="row">
        <div class="col-12 offset-2">
            <a href="#" class="btn btn-secondary">Cancel</a>
            <input type="submit" name="submit" value="Create new Project" class="btn btn-success ">
        </div>
    </div>
    </form>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

include('includes/footer.php'); ?>

<?php

if (!empty($_POST) && isset($_POST['submit'])) {

    $projectName = isset($_POST['projectName']) ? $_POST['projectName'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $designerID = isset($_POST['designerID']) ? $_POST['designerID'] : 0;
    $designCategoryID = isset($_POST['designCategoryID']) ? $_POST['designCategoryID'] : 0;

    $projectName = mysqli_real_escape_string($conn, $projectName);
    $description = mysqli_real_escape_string($conn, $description);


    if (isset($_FILES['projectImgFileName'])) {
        $errors = array();
        $file_name = $_FILES['projectImgFileName']['name'];
        $file_size = $_FILES['projectImgFileName']['size'];
        $file_tmp = $_FILES['projectImgFileName']['tmp_name'];
        $file_type = $_FILES['projectImgFileName']['type'];
        $array = explode('.', $_FILES['projectImgFileName']['name']);
        $file_ext = strtolower(end($array));
        $extensions = array("jpeg", "jpg", "png");
        if (in_array($file_ext, $extensions) === false) {
            $errors[] =
                "extension is not allowed,please choose a JPEG or PNG.";
        }
        if ($file_size > 2097152) {

            $errors[] =
                'File size must not larger than 2MB';
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "uploads/" . $file_name);
        } else {
            print_r($errors);
        }
    }
    $logoImgFileName = $file_name ? $file_name : "";
    $insert_project = insertProject($projectName, $description,$designerID,$designCategoryID, $logoImgFileName, $conn);
    /*UPLOad Files*/

    /******************/
    if ($insert_project > 0) {

        echo '<script>
    window.location="designer_home.php";</script>';


    }

}
?>
