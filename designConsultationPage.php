<?php
session_start();
if (!isset($_SESSION['designer_logged'])) {
    header("location:index.php");
}
require_once('includes/connection.php');
include("includes/header.php");
include('includes/functions.php');

$designer_id = $_SESSION['designer_id'];
$reqId = isset($_GET['reqId']) ? $_GET['reqId'] : 0;
$consultantRequest = getRequestInfo($reqId, $conn);


?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-10 offset-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Design Detail</h3>

                    <div class="card-tools">

                    </div>
                </div>
                <div class="card-body">

                </div>
                <div>

                    <div class="col-10 col-md-10 col-lg-8 order-2 order-md-1">
                        <div class="row">

                            <div class=" col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Client Name</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $consultantRequest['firstName'] . ' ' . $consultantRequest['lastName']; ?><span>
                    </span></span></div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Category</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $consultantRequest['category']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Room Dimensions</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $consultantRequest['roomLength']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">colorPreferences</span>
                                       </span>
                                        <div class="mx-5">

                                            <label style="background-color:<?php echo $consultantRequest['colorPreferences']; ?> "><?php echo $consultantRequest['colorPreferences']; ?></label>
                                        </div></div>
                                </div>
                            </div>


                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Type</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $consultantRequest['type']; ?><span>
                    </span></span></div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Date</span>
                                        <span class="info-box-number text-center text-muted mb-0"><?php echo $consultantRequest['date']; ?><span>
                    </span></span></div>
                                </div>
                            </div>


                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="row">
        <div class="col-md-10 offset-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Consultation</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="consultation">Design Consultation</label>
                            <textarea id="consultation" name="consultation" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="consultationImgFileName">Design Image</label>
                            <input type="file" id="consultationImgFileName" name="consultationImgFileName"
                                   class="form-control">
                        </div>

                        <input type="hidden" name="requestID" value="<?php echo $reqId; ?>">
                        <button type="submit" name="submit" class="btn btn-success btn-sm"><i class="fas fa-check">
                            </i>
                            Add Consultation
                        </button>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
    </div>


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

include('includes/footer.php'); ?>

<?php

if (!empty($_POST) && isset($_POST['submit'])) {

    $consultation = isset($_POST['consultation']) ? $_POST['consultation'] : '';
    $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : '';

    $consultation = mysqli_real_escape_string($conn, $consultation);


        if (isset($_FILES['consultationImgFileName'])) {
            $errors = array();
            $file_name = $_FILES['consultationImgFileName']['name'];
            $file_size = $_FILES['consultationImgFileName']['size'];
            $file_tmp = $_FILES['consultationImgFileName']['tmp_name'];
            $file_type = $_FILES['consultationImgFileName']['type'];
            $array = explode('.', $_FILES['consultationImgFileName']['name']);
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
        $insert_consult = insertConsultation($consultation, $requestID, $logoImgFileName, $conn);
        /*UPLOad Files*/

        /******************/
        if ($insert_consult > 0) {

            echo '<script>
    window.location="designer_home.php";</script>';


        }

}
?>
