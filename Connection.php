<?php
$servername="localhost";
$username="root";
$password="";
$db_name="zeusxaldrin";

$conn=mysqli_connect($servername,$username,$password,$db_name);
if(!$conn)
{
die("zeusXaldrin Connection Failed: ".mysqli_connect_error());
}
echo "zeusXaldrin !! You have Connected Successfully !!!";