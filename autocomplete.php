<?php
    $dbhost="localhost";
	$dbuser="root";
	$dbpass="";
	$dbname="airlineresvervationsystem";
	$conn= mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
    $conn1= new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(!$conn){
		die("Connection failed: \n");
	}
    $search=$_GET['term'];
    $sql="select * from airport where city like '%".$search."%' order by city";
    $result=mysqli_query($conn,$sql);
    $sdata=array();
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){
            $data['id']=$row['Airport_ID'];
            $data['value']=$row['City'];
            array_push($sdata,$data);
        }
    }

echo json_encode($sdata);
?>
