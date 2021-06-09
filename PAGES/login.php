<?php
   session_start();
   include ("../connection.php");
   

   if($_SERVER['REQUEST_METHOD'] == "POST"){
     //Algo fue "POSTEADO"
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
		{

			//lleer desde la base de datos
			$query = "select * from users where user_name = '$user_name' limit 1"; // cambiar nombre base de datos
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['user_id'] = $user_data['user_id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo "wrong username or password!";
		}else
		{
			echo "wrong username or password!";
		}
   }
?>
<!DOCTYPE html>
<html>
<head>
   <tittle>LOGIN</tittle>
   <link rel="stylesheet" href="CSS/styles.css" type="text/css" />
</head>

<body>
   <div id="formBox">
   
     <form method="POST">
     <input type="text" name="user_name"><br><br>
     <input type="password" name="password"><br><br>
     <input type="submit" value="LOGIN">
     </form>
   
   </div>
</body>

</html>