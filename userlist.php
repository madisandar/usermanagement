<?php
ini_set('display_errors',1);
// session_start();
require_once('./dbconnect.php');
require_once "./sessionconfig.php";
// if(!isset($_SESSION['email']) && !isset($_SESSION['password'])){
//     header("Location:./signup.php");
// }

if(authfailed()){
    redirectto('./signin.php');
}

try{
   $stmt = $conn->prepare("SELECT id,profile,firstname,lastname,email,dob,phone,address FROM users");
   $stmt->execute();
   
}catch(PDOException $e){
    echo "Error Found ".$e->getMessage();
}

$conn = null;

?>
<!DOCTYPE html>
<html>
	<head>
		<title>User List</title>
		<link href="./style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>


       <div class="container">

		   <div id="dotbox" class="dotbox">
               <span class="dot"></span>
			   <span class="dot"></span>
			   <span class="dot"></span>
			   <span class="dot"></span>
		   </div>

		   <div id="userlist-container">
               <table>
                   <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile Picture</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Birthday</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                   </thead>
                   <tbody>
                      <?php while($row = $stmt->fetch()): ?>
                        <tr>
                            <td><?= "{$row['id']}" ?></td>
                            <td><img src="<?= "{$row['profile']}" ?>" style="width:25px;height:25px;border-radius:50%" /></td>
                            <td><?php echo "{$row['firstname']} {$row['lastname']}" ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['dob']; ?></td>
                            <td><?= $row['phone']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                        </tr>
                      <?php endwhile; ?>
                   </tbody>
               </table>

               <button type="submit" class="submit-btn" onclick="goregister()">Create User</button>
           </div>
	   </div>

		<script type="text/javascript">
            function goregister(){
                window.location.href = "./signup.php";
            }
        </script>
	</body>
</html>