<?php
function init_connection(){
        global $conn;
        if(!$conn){
            $servername = "localhost";
            $username = "root";         
            $password = "";             
            $dbname = "register";        
            $conn = new mysqli($servername, $username, $password, $dbname);
       }
   }

function check_login() {
	session_start();
	$logged = $_SESSION['logged'] ?? false;

	if (!$logged) {
		header("Location:form.php");
		exit();
	}	
}

function addEmail($email, $password) {
    global $conn; 
    $sql = "INSERT INTO user (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
}
