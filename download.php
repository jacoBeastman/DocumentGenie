<?php

//config

include("config.php");

//open connection to get file
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//update record for testing
 /*
$myfile = file_get_contents('./cpu.png');


$myPDO = new PDO("mysql:host=localhost;dbname=$dbname;", $username, $password);

$sth = $myPDO->prepare("UPDATE researchdata SET filedata = ?");


if ($sth->execute(array($myfile)) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
*/


//Get the requested recordID. If someone browses directly to the page, just give them the first file for now
if($_POST["RecordID"] > 0)
	$myid = $_POST["RecordID"];
else
	$myid = 1;

//Get the file data from the table. Only one row for testing....
$sql = "SELECT filedata, filename FROM researchdata WHERE mykey = $myid";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

$row = $result->fetch_assoc();

$myfile = $row["filedata"];
$myname = $row["filename"];
    }
else {
    echo "no files found";
}



$conn->close();

$myname = urlencode($myname);

//dump it in the uploads dir for sanity check on filesize

//$handle = fopen("uploads/tempdl", "w");
//fwrite($handle, $myfile);

//spit it out
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
//header('Content-Type: application/pdf');
header("Content-Disposition: attachment; filename=$myname");
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: '.strlen($myfile));
ob_clean();
flush();
echo $myfile;

?>
