<?php
require_once('includes/connection.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($action == "delete") {
    $projectId = isset($_POST['project_id']) ? $_POST['project_id'] : '';
    $delete_project = deleteProject($projectId, $conn);
    echo $delete_project;
} else if ($action == "decline") {
    $requestId = isset($_POST['request_id']) ? $_POST['request_id'] : '';
    $declineRequest = declineRequest($requestId, $conn);
    echo $declineRequest;
}


function deleteProject($id, $conn)
{
    $del_qry = "DELETE from designportfolioproject where id=$id";
    $result = mysqli_query($conn, $del_qry);
    if ($result)
        return true;
    else
        return false;
}

function declineRequest($id, $conn)
{
    $update_request = "update designconsultationrequest set 
statusID=2 where id=$id";

    $result = mysqli_query($conn, $update_request);
    if ($result) {
        return true;
    }
    return false;
}

?>