<?php
require_once('includes/connection.php');

//$action = isset($_POST['action']) ? $_POST['action'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

if ($category != '') {
    $all_designers= getDesignersByCategory($category, $conn);

}
else{
    $all_designers = getAllDesigners($conn);
}
if (($all_designers != null)) {
    $designerArray=[];
    foreach ($all_designers as $key => $designer) {
        $designerArray[$key]=$designer;

    }
    echo  json_encode(['data'=>$designerArray]);
}

function getDesignersByCategory($cat,$conn)
{
    $all_designers = "select d.*,c.category from designerspeciality dr    left join  designer d on dr.designerID=d.id left join designcategory c on c.id=dr.designCategoryID where dr.designCategoryID=".$cat ;

    $designers_sql = mysqli_query($conn, $all_designers);
    return $designers_sql;
}

function getAllDesigners($conn)
{
    $all_designers = "select d.*,c.category from designerspeciality dr    left join  designer d on dr.designerID=d.id left join designcategory c on c.id=dr.designCategoryID " ;

    $designers_sql = mysqli_query($conn, $all_designers);
    return $designers_sql;
}
?>