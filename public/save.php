<?php
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "vistorpass";
/*
$servername = "184.168.108.77";
$username = "qudra_visitor";
$password = '*$y.5HW4I@kV';
$dbname = "visitor_pass";
*/
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$r = $conn->query("set names 'utf8'");


$name = explode(" ", $_POST['name']);
$lastname = substr(strstr($_POST['name'], " "), 1);
$gender = $_POST['gender'];
$address = $_POST['address'];
$nat_id = $_POST['nat_id'];
$perpic = $_POST['perpic'];
$exdate = $_POST['exdate'];
$plate_no = $_POST['plate_no'];
$add = $_POST['add'];
$images = explode("||", $_POST['images']);
$max = 0;
$reg_no = 0;
$counter = 1;

if ($gender == 'M') $gender = '5'; else $gender = '10';

if ($add == 'N') {
    unlink('plate.txt');
    $fh = fopen('plate.txt', 'w');
    fclose($fh);
} else {
    $myfile = fopen("plate.txt", "w");
    fwrite($myfile, $plate_no);
}


foreach ($images as $img):

    $img = str_replace("data:image/jpeg;base64,", "", $img);
    if ($img != '' or $img != ' ') {
        file_put_contents('images/' . $nat_id . '-' . $counter . '.jpg', base64_decode($img));
        $counter++;
    }
endforeach;

$r = $conn->query("set names 'utf8'");

$qry22 = "SELECT MAX( reg_no ) mx FROM `visiting_details`";
$result22 = $conn->query($qry22);
if ($result22->num_rows > 0) {
// output data of each row
    while ($row = $result22->fetch_assoc()) {
        $reg_no = $row["mx"];
    }
}
$reg_no = ($reg_no + 1);

//Create Picture
$data = $perpic;
list($type, $data) = explode(';', $data);
list(, $data) = explode(',', $data);
$data = base64_decode($data);

file_put_contents('per_images/' . $reg_no . '.png', $data);


$qry = "INSERT INTO `visitors`( `first_name`, `last_name`, `email`, `phone`, `gender`, `address`, `national_identification_no`, `is_pre_register`, `status`, 
		`creator_type`, `creator_id`, `editor_type`, `editor_id`, `created_at`, `updated_at`, `type`, `photo`) 
		VALUES 
		('" . $name[0] . "','" . $lastname . "','','','" . $gender . "','" . $address . "','" . $nat_id . "','1','5','App\Scan','1',
		'App\Scan','1','" . date('Y-m-d h:i:s') . "','" . date('Y-m-d h:i:s') . "','','" . $perpic . "')";
$result = $conn->query($qry);

$qry2 = "SELECT MAX( id ) mx FROM `visitors`";
$result2 = $conn->query($qry2);
if ($result2->num_rows > 0) {
// output data of each row
    while ($row = $result2->fetch_assoc()) {
        $max = $row["mx"];
    }
}


$qrcode = file_get_contents('https://www.qudratech-eg.net/qrcode/index.php?data=' . $name[0] . $reg_no);

$qry3 = "INSERT INTO `visiting_details`(`reg_no`, `purpose`, `company_name`, `company_employee_id`, `checkin_at`, `checkout_at`, 
		`status`, `user_id`, `employee_id`, `visitor_id`, `creator_type`, `creator_id`, `editor_type`, `editor_id`, `created_at`, `updated_at`, 
		`qrcode`, `expiry_date`, `plate_no`) 
		VALUES 
		('" . $reg_no . "','زياره',NULL,NULL,'" . date('Y-m-d h:i:s') . "',NULL,'5','3','3','" . $max . "',
		'App\Scan','1','App\Scan','1','" . date('Y-m-d h:i:s') . "','" . date('Y-m-d h:i:s') . "','" . $qrcode . "','" . $exdate . "','" . $plate_no . "')";
$result3 = $conn->query($qry3);

$create = file_get_contents('https://www.qudratech-eg.net/addimg.php?id=' . $max);


echo $max;
?>