<?php

require_once('Zend/Pdf.php');

//config
include("config.php");



//copy pasta with some hacks....
$target_dir = "/tmp/uploads/";
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$target_file = "/tmp/uploads/temp";
$uploadOk = 1;
/*$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
*/

//if everything checks out, upload the file to the temp location

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded. <br><br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. \n \n";
    } else {
        echo "Sorry, there was an error uploading your file. <br><br>";
    }
}

//we'll assume it is a pdf for now...


try{
	$pdf = Zend_Pdf::load('./uploads/temp');
	
	$myauth = $pdf->properties['Author'];
	$mytitle = $pdf->properties['Title'];
}
catch (Exception $e){
	newrelic_notice_error($e->getMessage(), $e);
	echo 'Error getting Author/Title. <br><br><br>', $e->getMessage(), '<br><br>';
	$myauth = '';
	$mytitle = '';
}

//get the temp file data and upload it to the database
$myfile = file_get_contents('./uploads/temp');
$myname = basename($_FILES["fileToUpload"]["name"]);
$mydesc = $_POST["filedesc"];


$myPDO = new PDO("mysql:host=localhost;dbname=$dbname;", $username, $password);


$sth = $myPDO->prepare("INSERT INTO researchdata (filename, filetitle, fileauthor, filedesc, filedata) VALUES (?, ?, ?, ?, ?)");

if ($sth->execute(array($myname, $mytitle, $myauth, $mydesc, $myfile)) === TRUE)
{
    echo "File stored in database successfully";
    echo '<br><br><br>
         <form action="display.php" method="get"><input type="submit" value="Return to Display Page"></form>';



} else {
    echo "Error inserting file to database: " . $myPDO->error;
    echo "data follows: <br> $myname <br> $mytitle <br> $myauth <br> $mydesc";
}


//OLD CODE FOR SINGLE RECORD TESTING
/*$sth = $myPDO->prepare("UPDATE researchdata SET filedata = ?");


if ($sth->execute(array($myfile)) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$sth = $myPDO->prepare("UPDATE researchdata SET filename = ?");

$sth->execute(array(basename($_FILES["fileToUpload"]["name"])));

$sth = $myPDO->prepare("UPDATE researchdata SET filedesc = ?");

$sth->execute(array($_POST["filedesc"]));

$sth = $myPDO->prepare("UPDATE researchdata SET fileauthor = ?");

$sth->execute(array($myauth));

$sth = $myPDO->prepare("UPDATE researchdata SET filetitle = ?");

$sth->execute(array($mytitle));
*/

?> 
