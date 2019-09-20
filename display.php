<?php
require_once 'HTML/Table.php';


//config
include 'config.php';

	//open connection to get file
	$conn = new mysqli($servername, $username, $password, $dbname);
		
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT mykey, filename, fileauthor, filetitle, filedesc FROM researchdata";

$result = $conn->query($sql);

if ($result->num_rows > 0) {


$data = array();

//Load query results into $data array
while($row = $result->fetch_assoc()){
array_push($data, array($row["mykey"], $row["filetitle"], $row["fileauthor"], $row["filedesc"], $row["filename"]));
}

//Hardcode a single row for testing
/*
$data = array(
'0'  =>  array($myid, $myname, $myauth, $mydesc)
);
*/
}
else{

echo "No files found.";
}

$attrs = array('width' => '600');
$table = new HTML_Table($attrs);
$table->setAutoGrow(true);
$table->setAutoFill('n/a');


for ($nr = 0; $nr < count($data); $nr++) {
  $table->setHeaderContents($nr+1, 0, (string)$nr);
  for ($i = 0; $i < 5; $i++) {
    if ('' != $data[$nr][$i]) {
      $table->setCellContents($nr+1, $i+1, $data[$nr][$i]);
    }
  }
}
//$altRow = array('bgcolor' => 'blue');
//$table->altRowAttributes(1, null, $altRow);


$table->setHeaderContents(0, 1, 'RecordID');
$table->setHeaderContents(0, 2, 'Title');
$table->setHeaderContents(0, 3, 'Author');
$table->setHeaderContents(0, 4, 'Description');
$table->setHeaderContents(0, 5, 'Filename');
$hrAttrs = array('bgcolor' => 'silver');
$table->setRowAttributes(0, $hrAttrs, true);
$table->setColAttributes(0, $hrAttrs);

echo $table->toHtml();

echo '<br><br><form action="download.php" method="post">
Input the recordID of the file you wish to download: <br> <input type="text" name="RecordID"><br><br>
<input type="submit" value="Download Selected File">
</form>';


echo '<br><br><form action="upload.html" method="get"><input type="submit" value="Upload New File"></form>';


?>


