<?php
function verify_client_login($email, $password, $conn)
{
    $existed_user = "select * from client where emailAdress='" .
        $email . "'";
    $query_client = mysqli_query($conn, $existed_user);
    if ($query_client) {
        $client = mysqli_fetch_assoc($query_client);
        $client_password = $client['password']; //password hashed from DB
        $verify_password = password_verify($password,
            $client_password);
        if ($verify_password) {
            $_SESSION['client_id'] = $client['id'];
            return true;
        }
        return false;
    }
}

function verify_designer_login($email, $password, $conn)
{
    $existed_user = "select * from designer where emailAdress='" .
        $email . "'";
    $query_designer = mysqli_query($conn, $existed_user);
    if ($query_designer) {
        $designer = mysqli_fetch_assoc($query_designer);
        if($designer){
            $designer_password = $designer['password']; //password hashed from DB
            $verify_password = password_verify($password,
                $designer_password);
            if ($verify_password) {
                $_SESSION['designer_id'] = $designer['id'];
                return true;
            }
        }

        return false;
    }
}


function getCategories($conn)
{
    $cats_data = "select * from designcategory";
    $cats_sql = mysqli_query($conn, $cats_data);

    return $cats_sql;
}


function getRoomTypes($conn)
{
    $rooms = "select * from roomtype";
    $rooms_sql = mysqli_query($conn, $rooms);

    return $rooms_sql;
}


function getMyDesigner($id, $conn)
{
    $design_data = "select * from designer where id=" . $id;
    $design_sql = mysqli_query($conn, $design_data);
    $design_arr = mysqli_fetch_array($design_sql);
    return $design_arr;
}

function getMyClient($id, $conn)
{
    $client_data = "select * from client where id=" . $id;
    $client_sql = mysqli_query($conn, $client_data);
    $client_arr = mysqli_fetch_array($client_sql);
    return $client_arr;
}

function getAllprojects($id, $conn)
{
    $all_projects = "select d.*,c.category from designportfolioproject d  left join designcategory c  on d.designCategoryID=c.id where designerID=" . $id;

    $projects_sql = mysqli_query($conn, $all_projects);
    return $projects_sql;
}

function getAllDesigners($conn)
{
    $all_designers = "select d.*,c.category from designerspeciality dr    left join  designer d on dr.designerID=d.id left join designcategory c on c.id=dr.designCategoryID " ;

    $designers_sql = mysqli_query($conn, $all_designers);
    return $designers_sql;
}

function getDesignersByCategory($cat,$conn)
{
    $all_designers = "select d.*,c.category from designerspeciality dr    left join  designer d on dr.designerID=d.id left join designcategory c on c.id=dr.designCategoryID where dr.designCategoryID=".$cat ;

    $designers_sql = mysqli_query($conn, $all_designers);
    return $designers_sql;
}

function getProject($id, $conn)
{
    $project = "select d.*,c.category from designportfolioproject d  left join designcategory c  on d.designCategoryID=c.id where d.id=" . $id;

    $project_sql = mysqli_query($conn, $project);

    $project_arr = mysqli_fetch_array($project_sql);

    return $project_arr;
}


function getDesignerProjects($id, $conn)
{
    $project = "select d.*,c.category from designportfolioproject d  left join designcategory c  on d.designCategoryID=c.id where d.designerID=" . $id;

    $project_sql = mysqli_query($conn, $project);

    return $project_sql;
}

function getRequestInfo($id, $conn)
{
    $request = "SELECT dr.id, c.firstName,c.lastName,dr.statusID,dr.designerID,dr.clientID,dr.roomLength ,
       rt.type,dr.colorPreferences,dr.date,rs.status,dc.category 
FROM designconsultationrequest dr left join designcategory dc on dr.designCategoryID=dc.id 
    join requeststatus rs on rs.id=dr.statusID join roomtype rt on rt.id=dr.roomTypeID 
    join client c on c.id=dr.clientID where dr.id=" . $id;
    $request_sql = mysqli_query($conn, $request);
    $request_arr = mysqli_fetch_assoc($request_sql);
    return $request_arr;
}

function insertRequest($clientID, $designerID,$roomTypeID,$designCategoryID,$roomLength
    ,$colorPreferences,$date,$statusID, $conn)

{
    $insert_request = "INSERT into designconsultationrequest(clientID, designerID, roomTypeID, designCategoryID, roomLength, colorPreferences, date, statusID) 
VALUES
($clientID,$designerID,$roomTypeID,$designCategoryID,$roomLength,'$colorPreferences','$date',$statusID)";
    $result = mysqli_query($conn, $insert_request);
    echo mysqli_insert_id($conn);
    if ($result) {
        return mysqli_insert_id($conn);
    }
    return false;
}

function getCourseStudents($id, $conn)
{
    $all_courses = "SELECT * FROM enrolment en ,student st 
where en.course_id=" . $id . " and en.student_id=st.id";
    $courses_sql = mysqli_query($conn, $all_courses);
    return $courses_sql;
}

function updateProject($id, $projectName, $designerID, $designCategoryID,
                       $description, $projectImgFileName, $conn)
{

    if ($projectImgFileName == "") {
        $update_project = "update designportfolioproject set projectName='$projectName',designerID=$designerID,
        designCategoryID='$designCategoryID', description='$description' where id=$id";
        //echo $update_project; die;
    } else
        $update_project = "update designportfolioproject set projectName='$projectName',designerID=$designerID,
        designCategoryID='$designCategoryID', description='$description',projectImgFileName='$projectImgFileName' where 
id=$id";
    //echo $update_project;
    $result = mysqli_query($conn, $update_project);
    if ($result) {
        return true;
    }
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

function deleteProject($id, $conn)
{
    $del_qry = "DELETE from designportfolioproject where id=$id";
    $result = mysqli_query($conn, $del_qry);
    if ($result)
        return true;
    else
        return false;
}

function checkEmail($email, $conn)
{
    $is_exist = "select * from designer where emailAdress='" . $email .
        "'";
    //echo $is_exist;
    $exis_qry = mysqli_query($conn, $is_exist);
    $num = mysqli_num_rows($exis_qry);
    if ($num > 0) {
        return false;
    }
    return true;
}

function checkClientEmail($email, $conn)
{
    $is_exist = "select * from client where emailAdress='" . $email .
        "'";
    //echo $is_exist;
    $exis_qry = mysqli_query($conn, $is_exist);
    $num = mysqli_num_rows($exis_qry);
    if ($num > 0) {
        return false;
    }
    return true;
}

function is_Enrolled($id, $course_id, $conn)
{
    $enrolled = "SELECT * FROM enrolment WHERE student_id=" .
        $id . " and course_id=" . $course_id;
//echo $enrolled;
    $sorted_qry = mysqli_query($conn, $enrolled);
    $num = mysqli_num_rows($sorted_qry);
    if ($num > 0) {
        return true;
    }
    return false;
}

function getEnrolled($id, $conn)
{
    $sorted_courses = "SELECT c.name,id , 'rolled' AS Roll FROM 
course c where id IN(SELECT course_id FROM enrolment WHERE 
student_id=" . $id . ")
UNION
(SELECT name,id , 'unrolled' AS Roll FROM course where id NOT 
IN(SELECT course_id FROM enrolment WHERE student_id=" . $id .
        "))";
    $sorted_qry = mysqli_query($conn, $sorted_courses);
    return $sorted_qry;
}

function getAllRequests($id, $conn)
{
    $designRequests = "SELECT dr.id, c.firstName,c.lastName,dr.statusID,dr.designerID,dr.clientID,dr.roomLength ,
       rt.type,dr.colorPreferences,dr.date,rs.status,dc.category 
FROM designconsultationrequest dr left join designcategory dc on dr.designCategoryID=dc.id 
    join requeststatus rs on rs.id=dr.statusID join roomtype rt on rt.id=dr.roomTypeID 
    join client c on c.id=dr.clientID where dr.statusID=1 and dr.designerID=" . $id;
    $requests_qry = mysqli_query($conn, $designRequests);
    return $requests_qry;
}

function dropEnrolled($std_id, $course_id, $conn)
{
    $drop = "DELETE from enrolment where course_id=" . $course_id . " 
and student_id=" . $std_id;
    $drop_qry = mysqli_query($conn, $drop);
    return $drop_qry;
}

function isertEnrolled($std_id, $course_id, $conn)
{
    $insert = "insert into enrolment(course_id,student_id) 
values($course_id,$std_id)";
    $ins_qry = mysqli_query($conn, $insert);
    return $ins_qry;
}

function checkUserName($username, $conn)
{
    $is_exist = "select * from student where username='" .
        $username . "'";
    $exis_qry = mysqli_query($conn, $is_exist);
    $num = mysqli_num_rows($exis_qry);
    if ($num > 0) {
        return false;
    }
    return true;
}

function insertDesigner($firstName, $lastname, $emailAdress, $brandName, $password, $logoImgFileName, $conn)
{
    $options = [
        'cost' => 12,
    ];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);
    $insert_designer = "INSERT INTO designer(firstName, lastName, emailAdress, password, brandName, logoImgFileName) values 
('$firstName','$lastname','$emailAdress','$hashed_password','$brandName','$logoImgFileName')";
    $result = mysqli_query($conn, $insert_designer);
    echo mysqli_insert_id($conn);
    if ($result) {
        return mysqli_insert_id($conn);
    }
    return false;
}

function insertProject($projectName, $description, $designerID, $designCategoryID, $logoImgFileName, $conn)
{

    $insert_project = "INSERT INTO  designportfolioproject(designerID, projectName, projectImgFileName, description, designCategoryID)
    Values ($designerID,'$projectName','$logoImgFileName','$description',$designCategoryID)";

    $result = mysqli_query($conn, $insert_project);

    if ($result) {
        return mysqli_insert_id($conn);
    }
    return false;
}


function insertConsultation($consultation, $requestID, $logoImgFileName, $conn)
{

    $insert_consultation = "INSERT INTO designconsultation( requestID, consultation, consultationImgFileName) values 
($requestID,'$consultation','$logoImgFileName')";
    $result = mysqli_query($conn, $insert_consultation);

    if ($result) {
        $update_request = "update designconsultationrequest set statusID=3 where id=$requestID";
        $result_consultation = mysqli_query($conn, $update_request);

        if ($result_consultation) {
            return true;
        }
        return false;
    }
    return false;
}

function insertClient($firstName, $lastname, $emailAdress, $password, $conn)
{
    $options = [
        'cost' => 12,
    ];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);
    $insert_designer = "INSERT INTO client(firstName, lastName, emailAdress, password) values 
('$firstName','$lastname','$emailAdress','$hashed_password')";
    $result = mysqli_query($conn, $insert_designer);
    echo mysqli_insert_id($conn);
    if ($result) {
        return mysqli_insert_id($conn);
    }
    return false;
}

?>