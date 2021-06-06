<?php 
session_start();

	include("connection.php");
	include("functions.php");
  
	check_login($con);
  $user_id = $_SESSION['user_id'];


  function ultimoID(&$last_id,$valor){
    $last_id= $valor;
 }
 function change($apunte_id,$con){
  if($_SERVER['REQUEST_METHOD'] == "POST"){// sin esto marca error si o si 
  if( $_POST['action']=="ELIMINAR"){
       $query ='DELETE FROM apunte where id='.$apunte_id;
       $con->query($query) or die(mysqli_error($con));
  }
  else if(( $_POST['action']=="ACTUALIZAR")){
  
  }
}
}

    $query = "SELECT c.id, c.nombre, c.descripcion FROM contenido  as c
              INNER JOIN user as u ON c.user_id= u.user_id where u.user_id =$user_id "; // editar bases de datos
    $result = $con->query($query);

  
    // imprimir contenido 

    if($result && mysqli_num_rows($result) > 0){

       // en caso de que ya haya contenido creado

        
        
        for($i=0;$i< mysqli_num_rows($result);$i++){
          $contenido_data = mysqli_fetch_assoc($result);
          echo '<div id=' . htmlspecialchars($contenido_data['id']) .'>'; // cada contenido se crea en un div con id igual al id de la tabla contenido
          echo '<a class=apuntes href='.'PAGES/apuntes.php?contenido_id='.htmlspecialchars($contenido_data['id']).'&nombre='.htmlspecialchars($contenido_data['nombre']).'>';
          echo 'ID: ' . htmlspecialchars($contenido_data['id']) . '<br>';
          echo 'Nombre: ' . htmlspecialchars($contenido_data['nombre']) . '<br>';
          echo 'Descripcion: ' . htmlspecialchars($contenido_data['descripcion']) . '<br>';
          echo '</a>';
          echo '<form action='.change($contenido_data['id'],$con).' method="post">';
          echo '<input type="submit" name="action" value="ELIMINAR">';
          echo '<input type="submit" name="action" value="ACTUALIZAR">';
          echo '</div>';
          echo '<br><br>';
          if($i ==  (mysqli_num_rows($result)-1)   ){  // en caso de haber contenido toma el último id y le suma 1 para ser usado como el siguiente
            ultimoID($last_id,$contenido_data['id']+1);
          }

        }
    }
      
      elseif ($result && mysqli_num_rows($result) == 0){
          //x($last_id);
          $last_id = 1; // si no hay contenido deja el próximo id a utilizar como 1 
          echo "no hay contenido";
      }
      elseif (!$result){
        echo " error al conectar con la base de datos";
      }
      



      // creacion de contenido
      
      if($_SERVER['REQUEST_METHOD'] == "POST"){
        //Algo fue "POSTEADO"
       $nombre = strval($_POST['nombre']);
       $descripcion = strval($_POST['descripcion']);
   
       if(!empty($nombre) && !empty($descripcion) && !is_numeric($nombre))
       {
         //leer desde la base de datos
        
         $query = "INSERT into contenido (id,nombre,descripcion,user_id)"." values('$last_id','$nombre','$descripcion','$user_id')";// error: contenido.id =/= AI, cambiar el  1 en el primer campo de values()
         

         $result = $con->query($query) or die(mysqli_error($con));
   
         if($result) // problema: $result = false por algún motivo.
         { 
           header("Refresh:0");

           
         }
         
         
       }else
       {
         echo "form vacio";
       }
      }
     
      


?>

<!DOCTYPE html>
<html>
<head>
<title>HOME</title>
 <link rel="stylesheet" href="CSS/styles.css" type="text/css" />
</head>
<body>
  <div id="formBOX">
     <form method="POST" >
      <fieldset style="width: 10%">
        <legend style="text-align:center;">CREAR CONTENIDO</legend>
        <input type="text" name="nombre" placeholder="nombre" autocomplete="off"><br><br>
        <input type="text" name="descripcion"placeholder="descripcion..." autocomplete="off"><br><br>
        <input type="submit" value="CREAR" >
      </fieldset>  
     </form>
  </div>
</body>

</html>