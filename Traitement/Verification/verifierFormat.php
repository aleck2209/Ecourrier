<?php
function gererFormat($file){

    if(isset($file) && $file["error"]===0) {
       # si on entre ici c'est qu'on a reçu le fichier
       
      
       # On vérifie si l'extension du fichier est correcte 
       $tableau_extension_autorises = [
          "pdf"=>"application/pdf",
          "xlsx"=>"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
       ];
    
       #On récupère le nom du fichier, l'extension, le type MIME
 
       $filename = $file['name'];
       $filetype = $file['type'];
       $fileextension = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
       $filesize = $file['size'];
 
       #On vérifie l'absence de l'extension dans les clés ou celle du type MIME dans les valeurs du tableau $tableau_extension_autorises
       
       if (!array_key_exists($fileextension,$tableau_extension_autorises)||!in_array($filetype,$tableau_extension_autorises) ) {
          # Si on entre ici c'est que soit c'est l'extension qui n'est pas conforme soit c'est le type MIME
          die("Erreur le format du fichier ne corresponds pas");
       }
       #Si on est ici c'est qu'on a la bonne extension 
       #On vérifie la taille du fichier et on autorise uniquement les fichiers de moins de 3 Mo
 
       if ($filesize >3*1024*1024 ) {
          die("Erreur le fichier est trop volumineux");
       }
 
 
       // var_dump($file);
      return $file;
   }else {
      die('<script>alert("erreur  fichier non envoyé")<script>');
   }
 }
 

?>