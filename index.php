<?php 
session_start();

	include("connection.php");
	include("functions.php");
  date_default_timezone_set("America/Santiago");
	check_login($con);
  $user_id = $_SESSION['user_id'];


  function ultimoID(&$last_id,$valor){
    $last_id= $valor;
 }
 
 if(isset($_POST['action']) && isset($_POST['contenidoID'])){// sin esto marca error si o si 
  $id=$_POST['contenidoID'];
  if( $_POST['action']=="ELIMINAR"){
    echo <<<_END
       <div="ELIMINAR">
        <form method="POST">              
          <fieldset style="width:10%">
            <legend style="text-align:center;">¿SEGURO?</legend>
            <input type="text" value="borraras el contenido: $id" disabled>
            <input type="hidden" name="contenido_id_delete" value="$id">
            <input type="submit" name="OPTION" value="SI">
            <input type="submit" name="OPTION" value="NO">
          </fieldset>
        </form>
       </div>    

     _END;

  }
  else if(( $_POST['action']=="ACTUALIZAR")){
    // podría por script habilitarse un form igual al de creación y obtener valores de ahí
       echo <<<_END
       <div id="UPDATE">
          <form method="POST">
           <fieldset style="width: 15%">
             <legend style="text-align:center;">ACTUALIZAR</legend>
             <input type="hidden" name="contenido_id_update" value="$id">
             <input type="text" name="nombreActualizar" placeholder="nombre" autocomplete="off"><br>
             <input type="text" name="descripcionActualizar" placeholder="descripcion..." autocomplete="off"><br><br>  
             <input type="submit" name="boton_actualizar" value="ACTUALIZAR" ><br>
             <input type="submit" name="boton_actualizar" value="CANCELAR">
          </fieldset>  
         </form>
       </div>
       _END;

    

        
  }
 }
 if(isset($_POST['boton_actualizar']))
    {
        if($_POST['boton_actualizar']=="ACTUALIZAR")
        {  
          //die("lleeegaaaaa aquí");// aquí si llega
            if(ISSET($_POST['contenido_id_update']) && isset($_POST['nombreActualizar'])  && isset($_POST['descripcionActualizar'])      )
            {
              //die("llega aquí"); //no llega
              $date=date("Y-m-d H:i:s");
              $newName = $_POST['nombreActualizar'];
              $newDesc = $_POST['descripcionActualizar'];
              $id= $_POST['contenido_id_update'];
              $query = "UPDATE contenido set nombre='$newName',descripcion='$newDesc',fecha_actualizacion='$date'  WHERE contenido.id='$id'";// entre comillas falta ver si es que cambian o no el tipo de apunte al actulizar
              $result = $con->query($query) or die(mysqli_error($con));
              header("Refresh:0");
            }
        }
        elseif($_POST['boton_actualizar']=="CANCELAR"){
             header("Refresh:0");
        }
    } 



    if(isset($_POST['OPTION'])){


      if($_POST['OPTION']=="SI" && isset($_POST['contenido_id_delete'])){
        $id= $_POST['contenido_id_delete'];
        $query ="DELETE FROM apunte WHERE contenido_id='$id'"; //$apunte_id
        $con->query($query) or die(mysqli_error($con));
        $query ="DELETE FROM contenido WHERE id='$id'"; //$apunte_id
        $con->query($query) or die(mysqli_error($con));
        //header("Refresh:0");
      }
      else if($_POST['OPTION']== "NO"){
        header("Refresh:0");
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
          echo '<div class="BOX" style="border: solid black 1px; width:25%">'; // cada contenido se crea en un div con id igual al id de la tabla contenido
          echo '<a class=apuntes href='.'PAGES/apuntes.php?contenido_id='.htmlspecialchars($contenido_data['id']).'&nombre='.htmlspecialchars($contenido_data['nombre']).'>';
          //echo 'ID: ' . htmlspecialchars($contenido_data['id']) . '<br>';
          echo 'Nombre: ' . htmlspecialchars($contenido_data['nombre']) . '<br>';
          echo 'Descripcion: ' . htmlspecialchars($contenido_data['descripcion']) . '<br>';
          echo '</a>';
          echo '<form method="post">';
          echo '<input type="hidden"name="contenidoID" value='.$contenido_data['id'].'>';
          echo '<hr>';
          echo '<input type="submit" name="action" value="ELIMINAR">';
          echo '<input type="submit" name="action" value="ACTUALIZAR">';
          echo '</form>';
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
      
      if(isset($_POST['nombre'])  && isset($_POST['descripcion'])   ){
        //Algo fue "POSTEADO"
       $nombre = strval($_POST['nombre']);
       $descripcion = strval($_POST['descripcion']);
   
       if(!empty($nombre) && !empty($descripcion) && !is_numeric($nombre))
       {
         //leer desde la base de datos
         $date=date("Y-m-d H:i:s");
         //$query = "INSERT into contenido (id,nombre,descripcion,fecha_creacion,fecha_actualizacion,user_id)"." values('$last_id','$nombre','$descripcion','$date','$date','$user_id')";// error: contenido.id =/= AI, cambiar el  1 en el primer campo de values()
         $query = "INSERT INTO contenido (id,nombre,descripcion,fecha_creacion,fecha_actualizacion,user_id)"." values('$last_id','$nombre','$descripcion','$date','$date','$user_id')";

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