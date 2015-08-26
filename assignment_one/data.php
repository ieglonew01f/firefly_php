<?php //data.php
require_once 'login.php'; 
	
// Get values from form
$QuestionTitle       = $_POST['question_title'];
$Question       = $_POST['question'];

// Insert data into mysql
$sql="INSERT INTO jenkins_stack (question_title , question)
VALUES ('$QuestionTitle', '$Question')";
$result = mysqli_query($db_server,$sql); 

// if successfully insert data into database, displays message "Successful".
if($result){
//echo "Success";
header('Location: http://localhost/assignment_one/thankyou.php');
exit();
}
else {
echo "ERROR";
}

// close mysql
mysqli_close($db_server);
?> 