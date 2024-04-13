<?php
session_start();
if (!isset($_SESSION['designer_logged'])) {
    header("location:index.php");
}
require_once('includes/connection.php');
include("includes/header.php");
include('includes/functions.php');
 ?>


<!-- Main content -->
<section class="content">
    <div class="col-12 col-sm-12">
        <div>
            <div class="info-box-content">
                <?php
                $designer = isset($_GET['designer']) ? $_GET['designer'] : "";

                ?>



            </div>
        </div>
    </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Designer Projects</h3>


            <div class="card-header">


            </div>
        </div>
        <div class="card-body p-5">
            <table class="table table-striped reqs mb-15">

                <thead>
                <tr class="bg-primary">
                    <th style="width: 1%">
                        #
                    </th>
                    <th style="width: 30%">
                        Project Name
                    </th>

                    <th>
                        image
                    </th>
                    <th>
                        Design category
                    </th>

                    <th>
                        Description
                    </th>

                </tr>
                </thead>
                <tbody>
                <?php

                $all_projects = getDesignerProjects($designer, $conn);
                if (($all_projects != null)) {
                    foreach ($all_projects as $key => $project) {

                        ?>
                        <tr>
                            <td>
                                <?php echo $key ?>
                            </td>
                            <td>
                                <?php echo $project['projectName']; ?>
                                <br/>

                            </td>
                            <td>
                                <div class="col-sm-6">
                                    <img class="img-fluid mb-3"
                                         src="   <?php echo 'uploads/' . $project['projectImgFileName']; ?>"
                                         alt="   <?php echo $project['projectName']; ?>">

                                </div>


                            </td>
                            <td>
                                <?php echo $project['category']; ?>
                                <br/>

                            </td>
                            <td>
                                <?php echo $project['description']; ?>

                            </td>

                        </tr>

                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->


    </div>
    <!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php

include('includes/footer.php'); ?>

