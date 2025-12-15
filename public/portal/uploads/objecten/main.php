<?php
ob_start();
session_start();
include_once 'db.php';
$con = new Database;
$connection = $con->connect();
$output = array();
error_reporting(E_ALL);ini_set('display_errors', 1);

function saltGenerator() {
	$chars = "!@#$%^&*()_+zaqsxcdfvbghnujmik,ol.p[]mAZWSXECDRFVTBGYHNUJMIKO1234567890";
	$salt = '';
	for ($i = 0; $i < 20; $i++) {
		$salt.= $chars[rand(0, strlen($chars) - 1) ];
	}
	return $salt;
}

function createPassword($wachtwoord, $salt) {
		return sha1($salt . $wachtwoord . 'humusreal');
}
function createName($name, $salt) {
		return sha1($salt . $name . 'humusreal');
}

if($_POST['status'] == "gebruikersAccountMaken"){
	$firstname 			= mysqli_real_escape_string($connection, $_POST['firstname']);
	$lastname 			= mysqli_real_escape_string($connection,$_POST['lastname']);
	$password 			= mysqli_real_escape_string($connection,$_POST['password']);
	$email 				= mysqli_real_escape_string($connection,$_POST['email']);
	$registreerCode 	= mysqli_real_escape_string($connection,$_POST['registerCode']);
	$salt 				= saltGenerator();
	$incryptwachtwoord	= createPassword($password, $salt);
	if($registreerCode == 1234){
		$query = "INSERT INTO users (firstname,lastname,email,password,salt,createdAt,updatedAt)
					VALUES ('$firstname', '$lastname','$email', '$incryptwachtwoord', '$salt', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'))";
		if($connection->query($query)){
			$output["message"] = "Uw account is succesvol aangemaakt.";
		}else{
			$output["message"] = "Iets is misgegaan tijdens het opslaan";
			echo("Error description: " . mysqli_error($connection ));
		}
	}else{
		echo "U bent niet bevoegd om iemand toe te voegen";
	}
}

if($_POST['status'] == 'objectFotosToevoegen'){
	$id 			= mysqli_real_escape_string($connection,$_POST['id']);
	$aantalFotos = count($_FILES);
	$status = 0;
	//var_dump($_FILES);
	for ($i=0; $i < $aantalFotos; $i++) { 
		$image = $_FILES['image'.$i]['name'];
		$newImageName = preg_replace('/\s+/', '-', $image);
		$newPathName = $id."-".$newImageName;
		$createName = "";
		if($_FILES['image'.$i]['type'] == "image/jpeg"){
			$createName = preg_replace('.jpg ', '', $newPathName);
		}else{
			$createName = preg_replace('.png ', '', $newPathName);
		}
		

		$file_type 	= pathinfo($image, PATHINFO_EXTENSION);
		$targetPath = '/home1/peprewatra/elevaterealestate.sr/portal/uploads/objecten/'.$createName.'.'.$file_type;
		$urlOpslaan 	= '/uploads/objecten/'.$createName.'.'.$file_type;
	    // now $name holds the original file name
	    $tmp_file = $_FILES['image'.$i]['tmp_name'];
		$error = $_FILES['image'.$i]['error'][$i];
	  //  $size = $_FILES['image'.$i]['size'][$i];
	  //  $type = $_FILES['image'.$i]['type'][$i];
        $newFileName = 'foo'; // You'll probably want something unique for each file.
        if(in_array(strtolower($file_type), [ 'jpeg', 'jpg', 'png', 'gif' ])) {
            if(move_uploaded_file($tmp_file, $targetPath))
            {
               $output["message"] = "Upload Succesvol";
	            $query = "INSERT INTO objectFotos (url,object_id) VALUES ('$urlOpslaan','$id')";
				if($connection->query($query)){
					$status = 1;
				}else{
					$status = 0;
				}
            }
        }
	}
	if($status == 1){
		$output["message"] = $_FILES;
		$output["status"] = 1; 
	}else{
		$output["message"] = $_FILES;
		$output["status"] = 0;
	}
}

if($_POST['status'] == "objectFotosOphalen"){
	$object 						= mysqli_real_escape_string($connection, $_POST['object']);
	$query 							= mysqli_query($connection,"SELECT * FROM objectFotos WHERE object_id = '$object' ");
	$aantalResultaten 				= mysqli_num_rows($query);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
	    $data = array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $data[] = $row;

        }
	$output = $data;
	$output['rows'] = $aantalResultaten;
	}
}

if($_POST['status'] == "featuredPhotoUpload"){
	$id 			= mysqli_real_escape_string($connection,$_POST['id']);
	// File name
	$file_name 		= $_FILES['file']['name'];
	$createName 	= createName("humus".$id,$file_name);
	$file_type 		= pathinfo($file_name, PATHINFO_EXTENSION);
	$targetPath 	= '/home1/peprewatra/elevaterealestate.sr/portal/uploads/featured/'.$createName.'.'.$file_type;
	$urlOpslaan 	= '/uploads/featured/'.$createName.'.'.$file_type;

	// File extension
	$file_type = pathinfo($file_name, PATHINFO_EXTENSION);
	// Validate type of file
	if(in_array(strtolower($file_type), [ 'jpeg', 'jpg', 'png', 'gif' ])) {
		if(move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
			if($targetPath == "" ){
				$output["message"] = "Niet alle velden zijn ingevuld";
			}else{

				$query = "UPDATE objecten SET featuredFoto = '$urlOpslaan' WHERE id ='$id' ";
				if($connection->query($query)){
					$output["status"] = 1;		
					$output["message"] = "Succesvol toegevoegd";
				}else{
					$output["status"] = 0;
					$output["message"] = "Iets is misgegaan tijdens het opslaan";
					$output = ("Error description: " . mysqli_error($connection ));
				}
			}
		}else{
			$output["message"] = "Niet upgeload";
		}
	}
	else {
		$output["message"] = 'Error : Only JPEG, PNG & GIF allowed';
	}
}

if($_POST['status'] == "userLogin"){
	$password 				= mysqli_real_escape_string($connection,$_POST['password']);
	$email 					= mysqli_real_escape_string($connection,$_POST['email']);
	$query 					= mysqli_query($connection,"SELECT * FROM users WHERE email='$email'");
	$data 					= $query->fetch_assoc();
	$incryptPass 			= createPassword($password, $data['salt']);
	$loginStatus 			= 0;
		if ($incryptPass == $data['password']){
			$output["login"] = 1;
			$output["currentUser"] = $data['id'];
			$_SESSION['currentUser'] = $data['id'];
			$_SESSION['password'] = $incryptPass;
		}else{
			$output["login"] = 0;
			$output["message"] = "Uw login gegevens zijn verkeerd";
		}
}

if($_POST['status'] == "ojectenOphalen"){
	$sql = "SELECT obj.naam as object,obj.corporate,curr.name as currency, distr.naam as district,objSub.naam as objectSub,obj.status,objT.naam as objectType,obj.vraagPrijs as prijs, obj.id as id FROM `objecten`obj ";
	$sql .= "INNER JOIN objectSubTypes objSub ON obj.objectSubType_id = objSub.id ";
	$sql .= "INNER JOIN objectTypes objT ON objT.id = objSub.objectType_id ";
	$sql .= "INNER JOIN districten distr ON distr.id = obj.district_id ";
	$sql .= "INNER JOIN currencies curr ON curr.id = obj.currency";

	$query 	= mysqli_query($connection,$sql);
	$aantalResultaten = mysqli_num_rows($query);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
	    $data = array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $data[] = $row;
        }
	$output = $data;
	$output['rows'] = $aantalResultaten;
	}
}

if($_POST['status'] == "gebruikersOphalen"){
	$query 				= mysqli_query($connection,"SELECT * FROM users");
	$aantalResultaten = mysqli_num_rows($query);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
	    $data = array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $data[] = $row;
        }
	$output = $data;
	$output['rows'] = $aantalResultaten;
	}
}

if($_POST['status'] == "gebruikerVerwijderen"){
	$id 			= mysqli_real_escape_string($connection, $_POST['id']);
	if($id == ""){
		$output["message"] = "Dit kan niet leeg zijn";
	}else{
		$query = "DELETE FROM users WHERE id = '$id'";
		if($connection->query($query)){
			$output["status"] = 1;			
			$output["message"] = "Succesvol gebruiker verwijderd";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het verwijderen";
			echo("Error description: " . mysqli_error($connection ));
		}
	}
}

if($_POST['status'] == "gebruikerBewerken"){
	$id 			= mysqli_real_escape_string($connection, $_POST['id']);
	$firstname 		= mysqli_real_escape_string($connection,$_POST['firstname']);
	$lastname 		= mysqli_real_escape_string($connection,$_POST['lastname']);
	$email 			= mysqli_real_escape_string($connection,$_POST['email']);

	if($email == ""){
		$output["message"] = "Dit kan niet leeg zijn";
	}else{
		$query = "UPDATE users SET firstname = '$firstname',lastname = '$lastname',email = '$email' WHERE id='$id'";
		if($connection->query($query)){
			$output["status"] = 1;			
			$output["message"] = "Succesvol bijgewerkt";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het bijwerken";
			echo ("Error description: " . mysqli_error($connection ));
		}
	}
}
if($_POST['status'] == "memberToevoegen"){

	$naam 			= mysqli_real_escape_string($connection,$_POST['naam']);
	$title 			= mysqli_real_escape_string($connection,$_POST['title']);
	$phone 			= mysqli_real_escape_string($connection,$_POST['phone']);
	$email 			= mysqli_real_escape_string($connection,$_POST['email']);
	$description 	= mysqli_real_escape_string($connection,$_POST['description']);
	$file_name 		= $_FILES['file']['name'];
	$createName 	= createName("elevate".$id,$file_name);
	$file_type 		= pathinfo($file_name, PATHINFO_EXTENSION);
	$targetPath 	= '/home1/peprewatra/elevaterealestate.sr/portal/uploads/members/'.$createName.'.'.$file_type;
	$urlOpslaan 	= '/uploads/featured/'.$createName.'.'.$file_type;

	// File extension
	$file_type = pathinfo($file_name, PATHINFO_EXTENSION);
	// Validate type of file
	if(in_array(strtolower($file_type), [ 'jpeg', 'jpg', 'png', 'gif' ])) {
		if(move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
			if( $naam == "" || $title == "" ){
				$output["message"] = "Niet alle velden zijn ingevuld";
			}else{
				$query = "INSERT INTO teamMembers (naam,title,phone,email,description,image) VALUES ('$naam','$title','$phone','$email', '$description', '$urlOpslaan')";
				if($connection->query($query)){
					$output["status"] = 1;		
					$output["laatste"] = $connection->insert_id;	
					$output["message"] = "Succesvol toegevoegd";
				}else{
					$output["status"] = 0;
					$output["message"] = "Iets is misgegaan tijdens het opslaan";
					$output = ("Error description: " . mysqli_error($connection ));
				}
			} 
		}else{
			$output["message"] = "Niet upgeload";
		}
	}
	else {
		$output["message"] = 'Error : Only JPEG, PNG & GIF allowed';
	}
}

if($_POST['status'] == "objectToevoegen"){
	$naam 					= mysqli_real_escape_string($connection,$_POST['naam']);
	$objectType 			= mysqli_real_escape_string($connection,$_POST['objectType']);
	$objectSubType 			= mysqli_real_escape_string($connection,$_POST['objectSubType']);
	$vraagprijs 			= mysqli_real_escape_string($connection,$_POST['vraagprijs']);
	$discountPrice 			= mysqli_real_escape_string($connection,$_POST['discountPrice']);
	$currency 				= mysqli_real_escape_string($connection,$_POST['currency']);
	$byowner 				= mysqli_real_escape_string($connection,$_POST['byowner']);
	$featured 				= mysqli_real_escape_string($connection,$_POST['featured']);
	$youtube 				= (empty($_POST['youtube']) ?  "" : mysqli_real_escape_string($connection,$_POST['youtube']));
	$directions 			= (empty($_POST['directions']) ?  "" : mysqli_real_escape_string($connection,$_POST['directions']));
	$district_id 			= (empty($_POST['district']) ? 0 : mysqli_real_escape_string($connection,$_POST['district']));
	$omgeving_id 			= (empty($_POST['omgeving']) ? 1 : mysqli_real_escape_string($connection,$_POST['omgeving']));
	$objectSoort 			= (empty($_POST['objectSoort']) ? 0 : mysqli_real_escape_string($connection,$_POST['objectSoort']));
	$status 				= mysqli_real_escape_string($connection,$_POST['objectStatus']);
	$perceelOppervlakte 	= (empty($_POST['perceelOppervlakte']) ?  "" : mysqli_real_escape_string($connection,$_POST['perceelOppervlakte']));
	$oppervlakteEenheid 	= (empty($_POST['oppervlakteEenheid']) ?  "" : mysqli_real_escape_string($connection,$_POST['oppervlakteEenheid']));
	$woonOppervlakte 		= (empty($_POST['woonOppervlakte']) ? "" :  mysqli_real_escape_string($connection,$_POST['woonOppervlakte']));
	$omschrijving 			= mysqli_real_escape_string($connection,$_POST['omschrijving']);
	$titel_id 				= (empty($_POST['titel_id']) ?  1 : mysqli_real_escape_string($connection,$_POST['titel_id']));
	$aantalSlaapkamers 		= (empty($_POST['aantalSlaapkamers']) ?  "" : mysqli_real_escape_string($connection,$_POST['aantalSlaapkamers']));
	$aantalBadkamers 		= (empty($_POST['aantalBadkamers']) ?  "" : mysqli_real_escape_string($connection,$_POST['aantalBadkamers']));
	$gemeubileerd 			= (empty($_POST['gemeubileerd']) ?  "" : mysqli_real_escape_string($connection,$_POST['gemeubileerd']));
	$huurwaarborg 			= (empty($_POST['huurwaarborg']) ? "" :  mysqli_real_escape_string($connection,$_POST['huurwaarborg']));
	$beschikbaarheid 		= (empty($_POST['beschikbaarheid']) ? "" :  mysqli_real_escape_string($connection,$_POST['beschikbaarheid']));

	if( $naam == "" || $status == 0 ){
		$output["message"] = "Niet alle velden zijn ingevuld";
	}else{
		$query = "INSERT INTO objecten (youtube,directions,byowner,featured,corporate,naam,objectType_id,objectSubType_id,vraagPrijs,currency,district_id,omgeving_id,status,perceelOppervlakte,oppervlakteEenheid,woonOppervlakte,omschrijving,titel_id,aantalSlaapkamers,aantalBadkamers,gemeubileerd,huurwaarborg,beschikbaarheid,discount) VALUES ('$youtube','$directions','$byowner','$featured','$objectSoort','$naam','$objectType','$objectSubType', '$vraagprijs','$currency','$district_id','$omgeving_id','$status','$perceelOppervlakte','$oppervlakteEenheid','$woonOppervlakte','$omschrijving','$titel_id','$aantalSlaapkamers','$aantalBadkamers','$gemeubileerd','$huurwaarborg','$beschikbaarheid','$discountPrice')";
		if($connection->query($query)){
			$output["status"] = 1;		
			$output["laatste"] = $connection->insert_id;	
			$output["message"] = "Succesvol toegevoegd";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het opslaan";
			$output = ("Error description: " . mysqli_error($connection ));
		}
	} 
}

if($_POST['status'] == "objectOphalenById"){
	$id 							= mysqli_real_escape_string($connection,$_POST['id']);
	$query 							= "SELECT * FROM objecten where id = '$id' ";
	$mysqli 						= mysqli_query($connection,$query);
	$resultaat 						= $mysqli->fetch_assoc();
	$aantalResultaten 				= count($resultaat);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
		$output = $resultaat;
	}
}

if($_POST['status'] == "laadFeaturedImage"){
	$object 						= mysqli_real_escape_string($connection,$_POST['object']);
	$mysqli 						= mysqli_query($connection,"SELECT featuredFoto FROM objecten where id = '$object' ");
	$resultaat 						= mysqli_fetch_assoc($mysqli);
	$aantalResultaten 				= mysqli_num_rows($mysqli);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
		$output = $resultaat;
		$output['status'] = 1;
		
	}
}

if($_POST['status'] == "objectVerwijderen"){
	$id 			= mysqli_real_escape_string($connection, $_POST['id']);
	if($id == ""){
		$output["message"] = "Dit kan niet leeg zijn";
	}else{
		$query = "DELETE FROM objecten WHERE id = '$id'";
		if($connection->query($query)){
			$output["status"] = 1;			
			$output["message"] = "Succesvol item verwijderd";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het verwijderen";
			echo("Error description: " . mysqli_error($connection ));
		}
	}
}

if($_POST['status'] == "objectFotoVerwijderen"){
	$id 			= mysqli_real_escape_string($connection, $_POST['id']);
	if($id == ""){
		$output["message"] = "Dit kan niet leeg zijn";
	}else{
		$query = "DELETE FROM objectFotos WHERE id = '$id'";
		if($connection->query($query)){
			$output["status"] = 1;			
			$output["message"] = "Foto succesvol verwijderd";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het verwijderen";
			echo("Error description: " . mysqli_error($connection ));
		}
	}
}

if($_POST['status'] == "alleFotosVerwijderen"){
	$object 			= mysqli_real_escape_string($connection, $_POST['object']);
	if($object == ""){
		$output["message"] = "Er is geen object om fotos van de te verwijderen";
	}else{
		$query 		= "DELETE FROM objectFotos WHERE object_id = '$object'";
		$query2 	= "UPDATE objecten SET featuredFoto = '' WHERE id = '$object'";

		if($connection->query($query) && $connection->query($query2)){
			$output["status"] = 1;			
			$output["message"] = "Fotos succesvol verwijderd";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het verwijderen";
			echo("Error description: " . mysqli_error($connection ));
		}
	}
}

if($_POST['status'] == "objectBewerken"){
	$id 					= mysqli_real_escape_string($connection, $_POST['id']);
	$naam 					= mysqli_real_escape_string($connection,$_POST['naam']);
	$objectType 			= mysqli_real_escape_string($connection,$_POST['objectType']);
	$objectSubType 			= (empty($_POST['objectSubType']) ?  "" : mysqli_real_escape_string($connection,$_POST['objectSubType']));
	$vraagprijs 			= mysqli_real_escape_string($connection,$_POST['vraagprijs']);
	$discountPrice 			= mysqli_real_escape_string($connection,$_POST['discountPrice']);
	$youtube 				= (empty($_POST['youtube']) ?  "" : mysqli_real_escape_string($connection,$_POST['youtube']));
	$directions 			= (empty($_POST['directions']) ?  "" : mysqli_real_escape_string($connection,$_POST['directions']));
	$objectSoort 			= mysqli_real_escape_string($connection,$_POST['objectSoort']);
	$currency 				= mysqli_real_escape_string($connection,$_POST['currency']);
	$district_id 			= mysqli_real_escape_string($connection,$_POST['district']);
	$byowner 				= mysqli_real_escape_string($connection,$_POST['byowner']);
	$featured 				= mysqli_real_escape_string($connection,$_POST['featured']);
	$omgeving_id 			= (empty($_POST['omgeving']) ? "" : mysqli_real_escape_string($connection,$_POST['omgeving']));
	$status 				= mysqli_real_escape_string($connection,$_POST['objectStatus']);
	$perceelOppervlakte 	= (empty($_POST['perceelOppervlakte']) ?  "" : mysqli_real_escape_string($connection,$_POST['perceelOppervlakte']));
	$oppervlakteEenheid 	= (empty($_POST['oppervlakteEenheid']) ?  "" : mysqli_real_escape_string($connection,$_POST['oppervlakteEenheid']));
	$woonOppervlakte 		= (empty($_POST['woonOppervlakte']) ? "" :  mysqli_real_escape_string($connection,$_POST['woonOppervlakte']));
	$omschrijving 			= mysqli_real_escape_string($connection,$_POST['omschrijving']);
	$typePand_id 			= (empty($_POST['typePand_id']) ?  "" : mysqli_real_escape_string($connection,$_POST['typePand_id']));
	$titel_id 				= (empty($_POST['titel_id']) ?  "" : mysqli_real_escape_string($connection,$_POST['titel_id']));
	$aantalSlaapkamers 		= (empty($_POST['aantalSlaapkamers']) ?  "" : mysqli_real_escape_string($connection,$_POST['aantalSlaapkamers']));
	$aantalBadkamers 		= (empty($_POST['aantalBadkamers']) ?  "" : mysqli_real_escape_string($connection,$_POST['aantalBadkamers']));
	$gemeubileerd 			= (empty($_POST['gemeubileerd']) ?  "" : mysqli_real_escape_string($connection,$_POST['gemeubileerd']));
	$huurwaarborg 			= (empty($_POST['huurwaarborg']) ? "" :  mysqli_real_escape_string($connection,$_POST['huurwaarborg']));
	$beschikbaarheid 		= (empty($_POST['beschikbaarheid']) ? "" :  mysqli_real_escape_string($connection,$_POST['beschikbaarheid']));
	if($objectSubType == 0 && $naam == ""){
		$output["message"] = "Dit kan niet leeg zijn";
	}else{
		$query = "UPDATE objecten SET byowner = '$byowner',featured = '$featured',corporate = '$objectSoort',naam = '$naam',objectType_id = '$objectType',objectSubType_id = '$objectSubType',vraagPrijs = '$vraagprijs',discount = '$discountPrice',currency = '$currency',youtube = '$youtube',directions = '$directions',district_id = '$district_id',omgeving_id = '$omgeving_id',status = '$status',perceelOppervlakte = '$perceelOppervlakte',oppervlakteEenheid = '$oppervlakteEenheid',woonOppervlakte = '$woonOppervlakte',	omschrijving = '$omschrijving',titel_id = '$titel_id',aantalSlaapkamers = '$aantalSlaapkamers',aantalBadkamers = '$aantalBadkamers',gemeubileerd = '$gemeubileerd',huurwaarborg = '$huurwaarborg',beschikbaarheid = '$beschikbaarheid' WHERE id='$id'";
		if($connection->query($query)){
			$output["status"] = 1;			
			$output["message"] = "Succesvol bijgewerkt";
		}else{
			$output["status"] = 0;
			$output["message"] = "Iets is misgegaan tijdens het bijwerken";
			echo ("Error description: " . mysqli_error($connection ));
		}
	}
}

if($_POST['status'] == "zoekenOpNaam"){
	$trefwoord 		= mysqli_real_escape_string($connection, $_POST['trefwoord']);

	$sql = "SELECT obj.naam as object,obj.corporate,curr.name as currency, distr.naam as district,objSub.naam as objectSub,obj.status,objT.naam as objectType,obj.vraagPrijs as prijs, obj.id as id FROM `objecten`obj ";
	$sql .= "INNER JOIN objectSubTypes objSub ON obj.objectSubType_id = objSub.id ";
	$sql .= "INNER JOIN objectTypes objT ON objT.id = objSub.objectType_id ";
	$sql .= "INNER JOIN districten distr ON distr.id = obj.district_id ";
	$sql .= "INNER JOIN currencies curr ON curr.id = obj.currency ";
	$sql .= "WHERE obj.naam LIKE '%".$trefwoord."%'";

	$query 	= mysqli_query($connection,$sql);
	$aantalResultaten = mysqli_num_rows($query);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
	    $data = array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $data[] = $row;

        }
	$output = $data;
	$output['rows'] = $aantalResultaten;
	}
}

if($_POST['status'] == "zoekenTypeObject"){
	$trefwoord 						= mysqli_real_escape_string($connection, $_POST['trefwoord']);
	$sql = "SELECT obj.naam as object,obj.corporate,curr.name as currency, distr.naam as district,objSub.naam as objectSub,obj.status,objT.naam as objectType,obj.vraagPrijs as prijs, obj.id as id FROM `objecten`obj ";
	$sql .= "INNER JOIN objectSubTypes objSub ON obj.objectSubType_id = objSub.id ";
	$sql .= "INNER JOIN objectTypes objT ON objT.id = objSub.objectType_id ";
	$sql .= "INNER JOIN districten distr ON distr.id = obj.district_id ";
	$sql .= "INNER JOIN currencies curr ON curr.id = obj.currency ";
	$sql .= "WHERE obj.objectType_id = '".$trefwoord."'";

	$query 	= mysqli_query($connection,$sql);
	$aantalResultaten = mysqli_num_rows($query);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
	    $data = array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $data[] = $row;

        }
	$output = $data;
	$output['rows'] = $aantalResultaten;
	}
}

if($_POST['status'] == "zoekenOpStatus"){
	$trefwoord 						= mysqli_real_escape_string($connection, $_POST['trefwoord']);
	$sql = "SELECT obj.naam as object,obj.corporate,curr.name as currency, distr.naam as district,objSub.naam as objectSub,obj.status,objT.naam as objectType,obj.vraagPrijs as prijs, obj.id as id FROM `objecten`obj ";
	$sql .= "INNER JOIN objectSubTypes objSub ON obj.objectSubType_id = objSub.id ";
	$sql .= "INNER JOIN objectTypes objT ON objT.id = objSub.objectType_id ";
	$sql .= "INNER JOIN districten distr ON distr.id = obj.district_id ";
	$sql .= "INNER JOIN currencies curr ON curr.id = obj.currency ";
	$sql .= "WHERE obj.status = '".$trefwoord."'";

	$query 	= mysqli_query($connection,$sql);
	$aantalResultaten = mysqli_num_rows($query);
	if($aantalResultaten == 0){
		$output['status'] = 0;
		$output['message'] = "Geen resultaten gevonden";
	}else{
	    $data = array();
        while ($row = mysqli_fetch_assoc($query))
        {
            $data[] = $row;

        }
	$output = $data;
	$output['rows'] = $aantalResultaten;
	}
}


echo json_encode($output);
?>
