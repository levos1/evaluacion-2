<?php
 session_start();
 include("../connection.php");
 include("../functions.php");

 $user_data = check_login($con);
 $contenido_id= $_GET['contenido_id'];
 // talvez corregir la base de datos para que tenga el campo " tipo " y me ahorro el problema

 function ultimoID(&$last_id,$valor){
    $last_id= $valor;
 }





 // borrar o actualizar 
 
 
  if(isset($_POST['action']) && isset($_POST['apunte_id'])){// sin esto marca error si o si 
     $id=$_POST['apunte_id'];
     if( $_POST['action']=="ELIMINAR"){
          $query ="DELETE FROM apunte WHERE id='$id'"; //$apunte_id
          $con->query($query) or die(mysqli_error($con));
          header("Refresh:0");
     }
     else if(( $_POST['action']=="ACTUALIZAR")){
       // podría por script habilitarse un form igual al de creación y obtener valores de ahí
          echo <<<_END
          <div id="UPDATE">
             <form method="POST">
              <fieldset style="width: 10%">
                <legend style="text-align:center;">ACTUALIZAR</legend>
                <input type="hidden" name="apunte_id_update" value='$id'">
                <input type="text" name="nombreActualizar" placeholder="nombre" autocomplete="off"><br><br>
                <select name="tipoApunteActualizar">
                <option value="DEFAULT">ELIGE UN TIPO DE APUNTE</option>
                <option value="TEXTO">TEXTO</option>
                <option value="IMAGEN">IMAGEN</option>
                <option value="VIDEO">VIDEO</option>
                </select><br><br>
                <input type="url" name="urlActualizar" placeholder="url.com..." autocomplete="off"><br><br>  
                <input type="submit" name="boton_actualizar" value="ACTUALIZAR" >
                <input type="submit" name="boton_actualizar" value="CANCELAR">
             </fieldset>  
            </form>
          </div>
          _END;

          // corregir posición usando css

           
     }
    }
    if(isset($_POST['boton_actualizar']))
    {
        if($_POST['boton_actualizar']=="ACTUALIZAR")
        {  
            if(ISSET($_POST['nombreActualizar']) && ISSET($_POST['tipoApunteActualizar']) && ISSET($_POST['urlActualizar']) )
            {
              $newName = $_POST['nombreActualizar'];
              $newType = $_POST['tipoApunteActualizar'];
              $newUrl = $_POST['urlActualizar'];
              $id= $_POST['apunte_id_update'];
              $query = "UPDATE apunte SET nombre ='$newName', tipoApunte='$newType'".",url='$newUrl' WHERE id='$id'";// entre comillas falta ver si es que cambian o no el tipo de apunte al actulizar
              $result = $con->query($query) or die(mysqli_error($con));
              header("Refresh:0");
            }
        }
        elseif($_POST['boton_actualizar']=="CANCELAR"){
             header("Refresh:0");
        }
    } 



 $query = "SELECT a.id, a.nombre, a.url, a.tipoApunte FROM apunte  as a
          INNER JOIN contenido as c ON a.contenido_id= c.id where c.id =$contenido_id "; // editar bases de datos
 $result = $con->query($query) or die(mysqli_error($con));
 

 // imprimir apuntes
 if($result && mysqli_num_rows($result) > 0){
     
    for($i=0;$i< mysqli_num_rows($result);$i++)
    {
        $apuntes_data = mysqli_fetch_assoc($result);
        // $id = $apuntes_data['id'];
        

          echo '<div id=' . htmlspecialchars($apuntes_data['id']) .'>'; // cada contenido se crea en un div con id igual al id de la tabla contenido
          echo 'ID: ' . htmlspecialchars($apuntes_data['id']) . '<br>';
          echo 'Nombre: ' . htmlspecialchars($apuntes_data['nombre']) . '<br>';
          
            switch($apuntes_data['tipoApunte']){
              case "TEXTO":
                 $gestor = fopen($apuntes_data['url'], "r");
                 $line = fgets($gestor);
                 //echo '<iframe src='.htmlspecialchars($apuntes_data['url']).' title=></iframe><br>'; // leer todo del documento asociado al url????
                 echo $line;
                 fclose($gestor);

                 break;
             case "IMAGEN":
                 echo '<img src="'.htmlspecialchars($apuntes_data['url']).'"width="100" height="100"><br>';
                 break;
             case "VIDEO":
                 echo '<video width="320" height="240" controls>'.
                        '<source src='.htmlspecialchars($apuntes_data['url']).'type="video/mp4">'.
                         '<source src='.htmlspecialchars($apuntes_data['url']).' type="video/ogg">
                          Your browser does not support the video tag.
                       </video>';
                 break;
     
            }
          
         // echo '<img src='.htmlspecialchars($apuntes_data['url']).'width="100" height="100"><br>';
          echo '<form id="'.$apuntes_data['id'].'"method = "post" >'; // no manda el id , buscar: mandar array como
          echo '<input type="hidden"name="apunte_id" value='.$apuntes_data['id'].'>';
          echo '<input type="submit" name="action" value="ELIMINAR">';
          echo '<input type="submit" name="action" value="ACTUALIZAR">';
          echo '</form>';
          echo '</div>';
          echo '<br><br>';
          // aquí podría ir el form para actualizar en modo disabled , al apretar el botón aparece y tendrá el mismo id del apunte ya que se creará acá
          // con $apuntes_data['id´']
 

        if($i ==  (mysqli_num_rows($result)-1)   )
        {  // en caso de haber contenido toma el último id y le suma 1 para ser usado como el siguiente
            ultimoID($last_id,$apuntes_data['id']+1);
        }
    }
 }
 elseif ($result && mysqli_num_rows($result) == 0){
    //x($last_id);
    $last_id = 1; // si no hay contenido deja el próximo id a utilizar como 1, cambiar nombre de variable
    echo "no hay apuntes";
}
elseif (!$result){
  echo " error al conectar con la base de datos";
}




// crear apuntes
if(ISSET($_POST['nombre']) && ISSET($_POST['url']) && ISSET($_POST['tipoApunte']) ){ // cambiar query y valroes de $_POST _SERVER['REQUEST_METHOD'] == "POST"
    //Algo fue "POSTEADO"
   $nombre = strval($_POST['nombre']);
   $url = strval($_POST['url']);
   $tipoApunte =strval($_POST['tipoApunte']);

   if(!empty($nombre) && !empty($url)  && !is_numeric($nombre))
   {
     //leer desde la base de datos
    
     $query = "INSERT into apunte (id,nombre,url,tipoApunte,contenido_id)"." values('$last_id','$nombre','$url','$tipoApunte','$contenido_id')";// error: contenido.id =/= AI, cambiar el  1 en el primer campo de values()


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
<title>APUNTES</title>
 <link rel="stylesheet" href="CSS/styles.css" type="text/css" />
</head>
<body>
  <div id="formBOX">
     <form method="POST">
     <fieldset style="width: 10%">
        <legend style="text-align:center;">CREAR APUNTE</legend>
        <input type="text" name="nombre" placeholder="nombre" autocomplete="off"><br><br>
        <select name="tipoApunte">
        <option value="DEFAULT">ELIGE UN TIPO DE APUNTE</option>
        <option value="TEXTO">TEXTO</option>
        <option value="IMAGEN">IMAGEN</option>
        <option value="VIDEO">VIDEO</option>
        </select><br><br>
        <input type="url" name="url" placeholder="url.com..." autocomplete="off"><br><br>  
        <input type="submit" value="CREAR" >
     </fieldset>  
     </form>
  </div>
</body>

</html>