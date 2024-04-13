<?php
session_start();
if (!isset($_SESSION['client_logged'])) {
    header("location:index.php");
}
require_once('includes/connection.php');
include("includes/header.php");
include('includes/functions.php');

$client_id = $_SESSION['client_id'];
$designer = isset($_GET['designer']) ? $_GET['designer'] : "";
$allCats = getCategories($conn);
$roomTypes = getRoomTypes($conn);

?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6 offset-2">
            <form action="" method="post">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add Request</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="designCategoryID">Category</label>
                            <input type="hidden" name="clientID" value="<?php echo $client_id; ?>">
                            <input type="hidden" name="designerID" value="<?php echo $designer; ?>">
                            <input type="hidden" name="statusID" value="1">
                            <select id="designCategoryID" name="designCategoryID" class="form-control">
                                <?php foreach ($allCats as $cat) { ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category']; ?></option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roomLength">Room Type</label>
                            <select id="roomTypeID" name="roomTypeID" class="form-control">
                                <?php foreach ($roomTypes as $room) { ?>
                                    <option value="<?php echo $room['id']; ?>"><?php echo $room['type']; ?></option>
                                <?php } ?>

                            </select>

                        </div>


                        <div class="form-group">
                            <label for="roomLength">Room Length</label>
                            <input type="number" id="roomLength" name="roomLength" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="roomLength">colorPreferences</label>
                                <input type="color" id="colorPreferences" name="colorPreferences" class="form-control">
                        </div>


                        <div class="form-group">
                            <label for="date">Date/label>
                                <input type="date" data-inputmask="dd-mm-yyyy" id="date" name="date" class="form-control">
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
            <input type="submit" name="submit" value="Create new Request" class="btn btn-success ">
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
  //`id`, `clientID`, `designerID`, `roomTypeID`, `designCategoryID`, `roomLength`, `colorPreferences`,
   //`date`, `statusID` FROM `designconsultationrequest`
if (!empty($_POST) && isset($_POST['submit'])) {


    $designerID = isset($_POST['designerID']) ? $_POST['designerID'] : 0;
    $clientID = isset($_POST['clientID']) ? $_POST['clientID'] : 0;
    $roomTypeID = isset($_POST['roomTypeID']) ? $_POST['roomTypeID'] : 0;
    $designCategoryID = isset($_POST['designCategoryID']) ? $_POST['designCategoryID'] : 0;
    $roomLength = isset($_POST['roomLength']) ? $_POST['roomLength'] : 0;
    $colorPreferences = isset($_POST['colorPreferences']) ? $_POST['colorPreferences'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '12-12-2024';
    $statusID = isset($_POST['statusID']) ? $_POST['statusID'] : 1;


    $insert_request = insertRequest($clientID, $designerID,$roomTypeID,$designCategoryID,$roomLength,$colorPreferences,$date,$statusID, $conn);

    /******************/
    if ($insert_request > 0) {

        echo '<script>
    window.location="client_home.php";</script>';


    }

}
?>
