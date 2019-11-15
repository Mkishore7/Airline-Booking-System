<?php

session_start();
if(isset($_POST["pay"]))
{
	$connection = new mysqli("localhost","root","","airlineresvervationsystem");
    if($connection->connect_error){
    die("Connection failed: ".$connection->connect_error."\n");}
    	$User_ID = $_SESSION['username'];
	for($x=1;$x<=$_SESSION["No_of_Seats_indirect"];$x++)
	{
	$Flight_no=$_SESSION["Start_Flight_no"];
	$sql = "SELECT Airline_ID,Account_No FROM Flights where Flight_no = '$Flight_no'";
	$result = $connection->query($sql);
	$Ticket_ID="";
	$Account_credited="";
	while($row = $result->fetch_assoc())
    {
       $Ticket_ID = $row["Airline_ID"];
       $Account_credited = $row["Account_No"];
    }
    $sql = "SELECT count(*) as Total from Ticket ";
	$result = $connection->query($sql);
	$var="";
	while($row = $result->fetch_assoc())
    {
       $var = $row["Total"];
    }
    $Ticket_ID = $Ticket_ID." ".$var;
    $Class=$_SESSION["Class_indirect"];
    $Airport_Id_Src=$_SESSION["Airport_Id_Src"];
    $Airport_Id_Dst=$_SESSION["Mid_Airport"];
    $Flight_no=$_SESSION["Start_Flight_no"];
    $Passenger_name_=$_SESSION["Passenger_name_".$x.""];
    $Passenger_email_=$_SESSION["Passenger_email_".$x.""];
    $Passenger_contact_=$_SESSION["Passenger_contact_".$x.""];
    $ArrivalTime=$_SESSION["Start_DepartureTime"];
    $DepartureTime=$_SESSION["Mid_ArrivalTime"];
    $Date_of_travelling=$_SESSION["Date_of_travelling"];
    $Account_No=$_POST["Account_No"];
    $Total_Price=$_SESSION["Total_Price"];
    $sql = "insert into Ticket VALUES(1,0,0,NULL,'$Ticket_ID','$Class',CURRENT_TIMESTAMP(),'$Airport_Id_Src','$Airport_Id_Dst','$Flight_no','$User_ID','$Passenger_name_','$Passenger_email_','$Passenger_contact_','$ArrivalTime','$DepartureTime','$Date_of_travelling')";
    $connection->query($sql);
   // var_dump($sql);
    $sql = "insert into Payment(Account_credited,Account_debited,TimeOfPayment,ModeOfPayment,Amount,Ticket_ID)
      VALUES('$Account_credited','$Account_No',CURRENT_TIMESTAMP(),'online','$Total_Price','$Ticket_ID')";
    $result = $connection->query($sql);
    }
    for($x=1;$x<=$_SESSION["No_of_Seats_indirect"];$x++)
	{
	$Flight_no=$_SESSION["Mid_Flight_No"];
	$sql = "SELECT Airline_ID,Account_No FROM Flights where Flight_no = '$Flight_no'";
	$result = $connection->query($sql);
	$Ticket_ID="";
	$Account_credited="";
	while($row = $result->fetch_assoc())
    {
       $Ticket_ID = $row["Airline_ID"];
       $Account_credited = $row["Account_No"];
    }
    $sql = "SELECT count(*) as Total from Ticket ";
	$result = $connection->query($sql);
	$var="";
	while($row = $result->fetch_assoc())
    {
       $var = $row["Total"];
    }
    $Ticket_ID = $Ticket_ID." ".$var;
    $Class=$_SESSION["Class_indirect"];
    $Airport_Id_Src=$_SESSION["Mid_Airport"];
    $Airport_Id_Dst=$_SESSION["Airport_Id_Dst"];
    $Flight_no=$_SESSION["Mid_Flight_No"];
    $Passenger_name_=$_SESSION["Passenger_name_".$x.""];
    $Passenger_email_=$_SESSION["Passenger_email_".$x.""];
    $Passenger_contact_=$_SESSION["Passenger_contact_".$x.""];
    $ArrivalTime=$_SESSION["Mid_DepartureTime"];
    $DepartureTime=$_SESSION["Final_ArrivalTime"];
    $Date_of_travelling=$_SESSION["Date_of_travelling"];
    $Account_No=$_POST["Account_No"];
    $Total_Price=$_SESSION["Total_Price"];
    $sql = "insert into Ticket VALUES(1,0,0,NULL,'$Ticket_ID','$Class',CURRENT_TIMESTAMP(),'$Airport_Id_Src','$Airport_Id_Dst','$Flight_no','$User_ID','$Passenger_name_','$Passenger_email_','$Passenger_contact_','$ArrivalTime','$DepartureTime','$Date_of_travelling')";
    $connection->query($sql);
   // var_dump($sql);
    $sql = "insert into Payment(Account_credited,Account_debited,TimeOfPayment,ModeOfPayment,Amount,Ticket_ID)
      VALUES('$Account_credited','$Account_No',CURRENT_TIMESTAMP(),'online','$Total_Price','$Ticket_ID')";
    $result = $connection->query($sql);
    }
    echo "<script type='text/javascript'>window.location.href = 'payment_done.php';</script>";
    exit();
}



echo "<h2>Summary :- </h2>";
echo "Flight_no : ".$_SESSION["Start_Flight_no"]."<br><br>";
echo "Class : ".$_SESSION["Class_indirect"];
echo "DepartureTime : ".$_SESSION["Date_of_travelling"]." ".$_SESSION["Start_DepartureTime"]."<br><br>";
echo "Mid_Airport : ".$_SESSION["Mid_Airport"]."<br><br>";
echo "Mid_ArrivalTime : ".$_SESSION["Date_of_travelling"]." ".$_SESSION["Mid_ArrivalTime"]."<br><br>";
echo "Mid_Flight_No : ".$_SESSION["Mid_Flight_No"]."<br><br>";
echo "Mid_DepartureTime : ".$_SESSION["Date_of_travelling"]." ".$_SESSION["Mid_DepartureTime"]."<br><br>";
echo "Final_ArrivalTime : ".$_SESSION["Date_of_travelling"]." ".$_SESSION["Final_ArrivalTime"]."<br><br>";
echo "Total Price :".$_SESSION["No_of_Seats_indirect"]*$_SESSION["Price_indirect"]."<br><br>";
echo "Total Duration: ".$_SESSION["Duration_indirect"]."<br><br>";
$_SESSION["Total_Price_inderect"]=$_SESSION["No_of_Seats_indirect"]*$_SESSION["Price_indirect"];
for($x=1;$x<=$_SESSION["No_of_Seats_indirect"];$x++)
{
	echo "Passenger ".$x." Details : "."<br>";
	echo 'Name: "'.$_POST["Passenger_name_".$x.""].'"  Email:  "'.$_POST["Passenger_email_".$x.""].'" Contact : "'.$_POST["Passenger_contact_".$x.""];
	echo "<br><br>";
	$_SESSION["Passenger_name_".$x.""]=$_POST["Passenger_name_".$x.""];
	$_SESSION["Passenger_email_".$x.""]=$_POST["Passenger_email_".$x.""];
    $_SESSION["Passenger_contact_".$x.""]=$_POST["Passenger_contact_".$x.""];
}
echo "<br><br>";
echo '<form action="payment_indirect.php" method = "post">';
echo 'Account No : <input type="text" name="Account_No" required>';
echo '<input type="Submit" name="pay" value = "PAY!!">';
?>
