<?php
include "../../../Config/database.php";

$dbOBJ = new DATABASE_CONFIG();

$dbArr = $dbOBJ->default;

mysql_connect($dbArr['host'],$dbArr['login'],$dbArr['password']) or die(mysql_error());
mysql_select_db($dbArr['database']) or die(mysql_error());

/*echo '<pre>';
print_r($dbArr);
*/

//$query = $_POST['query'];
if(isset($_POST['query'])||!empty($_POST['query']))
{
 //$query = $_POST['query']
 $query=stripslashes($_POST['query']);
 $result=mysql_query($query) or die(mysql_error());
 $number_cols=mysql_num_fields($result);
 echo "<font color=darkblue><b> query : $query</b></font><br><br>";
 echo "<table border=2 style=\"BORDER-COLLAPSE: collapse\" borderColor=blue>\n"; //layout table header
 echo "<tr align=center>\n";
 for($i=0;$i<$number_cols;$i++)
 {
  echo "<th><font color=darkblue>".mysql_field_name($result,$i)."</font></th>\n";
 }
echo "<tr>\n"; //end of table header
 while($row=mysql_fetch_row($result)) //lay out table body
 {
  echo "<tr align=left>\n";
for($i=0;$i<$number_cols;$i++)
  {
   echo "<td>";
   if(!isset($row[$i])) //test for null values
   {
    echo "NULL";
    }
   else
   {
   echo $row[$i];
   }
  echo "</td>\n";
  }  //end of for
 echo "</tr>\n";
} //end of while
echo "</table>";
} //end of if
?>
<form action="" method="post">
<input type="text" name="query" size="70"><br>
<input type="submit" value=" Submit Query ">
</form>
