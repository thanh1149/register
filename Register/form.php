<?php
    require_once 'function.php';
    init_connection();
    
    // Lưu email vào db khi đki thành công  
    function processPostForm() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($email && $password) {
            addEmail($email, $password);
            header('Location:login.php');
            exit();
        }
    }
    
    function getEmail($email) {
	return $email ? "form.php?email=$email" : 'form.php';
    }  
    //kiem tra email trong db
    function check_email($email) {
	global $conn;
	$sql = "SELECT email FROM user WHERE email = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();  
	
	if($result->num_rows > 0){
            return true;
        }else{
            $insertSql = "INSERT INTO user (email, password) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param('ss', $email, $password);
            $insertStmt->execute();

            return false;
        }     
    }
    
    // ktra password trung nhau
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lấy dữ liệu từ form
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if ($password !== $confirm_password) {
            echo "Mật khẩu không trùng khớp.";
        } else {
            if(check_email($email)){
                echo "Email da ton tai! ";
            }else{
                header("Location:login.php");
                exit();
            }
        }
    }
    
    
    //lay email khi edit
    $email = intval($_GET['email'] ?? '');
    
    // xu li khi an submit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	processPostForm();
    }
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đăng kí</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
            }

            .form-container {
                max-width: 400px;
                margin: 50px auto;
                padding: 20px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        </style>
        <script>
            function validatePassword() {
                var password = document.getElementById("password").value;
                var confirm_password = document.getElementById("confirm_password").value;
                var passwordMatchMessage = document.getElementById("passwordMatch");

                if (password != confirm_password) {
                    passwordMatchMessage.innerText = "Mật khẩu không trùng khớp.";
                    return false;
                } else {
                    passwordMatchMessage.innerText = "";
                    return true;
                }
        }
        </script>
    </head>
    <body>

        <div class="container">
            <div class="form-container">
                <h2 class="text-center mb-4">Đăng kí</h2>
                <form onsubmit="return validatePassword()" action="form.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <small id="passwordMatch" class="form-text text-danger"></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng kí</button>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
