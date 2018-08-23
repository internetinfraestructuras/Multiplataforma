<?php



 class UtilPDF
 {
     public function conectar(){


         $link = new mysqli("5.40.80.11", "root", "telereq1430*sql","multiplataforma");

         if (mysqli_connect_errno()) {
             printf("Falló la conexión: %s\n", mysqli_connect_error());
             exit();
         }


         $link->query("set names 'utf8'");

         return $link;


         /* verificar la conexión */



     }

     function cleanstring($string) {

         $string= str_replace("\""," ",$string);
         $string= str_replace("'"," ",$string);
         $string= str_replace("="," ",$string);
         $string= str_replace("%"," ",$string);
         $string= str_replace("\\"," ",$string);
         $string= str_replace("&"," ",$string);
         $string= str_replace(" and "," ",$string);
         $string= str_replace(" AND "," ",$string);
         $string= str_replace("select"," ",$string);
         $string= str_replace("SELECT"," ",$string);
         $string= str_replace("count"," ",$string);
         $string= str_replace("COUNT"," ",$string);
         $string= str_replace("NULL"," ",$string);
         $string= str_replace("null"," ",$string);
         $string= str_replace("like"," ",$string);
         $string= str_replace("LIKE"," ",$string);
         $string= str_replace("insert"," ",$string);
         $string= str_replace("INSERT"," ",$string);
         $string= str_replace("DELETE"," ",$string);
         $string= str_replace("delete"," ",$string);
         $string= str_replace("drop"," ",$string);
         $string= str_replace("DROP"," ",$string);
         $string= str_replace("WHERE"," ",$string);
         $string= str_replace("where"," ",$string);
         $string= str_replace(" OR "," ",$string);
         $string= str_replace(" or "," ",$string);
         $string= str_replace(" Or "," ",$string);
         $string= str_replace(" oR "," ",$string);
         $string= str_replace("||"," ",$string);
         $string = stripslashes($string);
         $string = strip_tags($string);
         return  $string;
     }

     public function selectWhere3($tabla, $campos, $where=null, $order=null, $group=null){


         try {

             $link = $this->conectar();

             $columnas = implode($campos, ",");

             $query = 'SELECT '. $columnas . ' FROM ' . $tabla;

             if($where != null)
                 $query = $query  . " WHERE " . $where;

             if($group != null)
                 $query = $query . " GROUP BY ".$group ;

             if ($order != null)
                 $query = $query . " ORDER BY ".$order ;

//            echo "<br>".$query."<br>";

             if (!($result = $link->query($query)))
                 throw new Exception();

             $fieldNames=array();

             while ($row = mysqli_fetch_array($result))
             {
                 array_push($fieldNames, $row);
             }
             // var_dump($fieldNames);
             $link->close();

             return $fieldNames;

         } catch (Exception $e) {
             $this->log('Error SelectWhere 3: ' . $query);

         }
         return $fieldNames;

     }
 }

 ?>