<?php
$Airport_Id_Src = $_POST['Source'];
$Airport_Id_Dst = $_POST['Destination'];
$Date_of_travelling =$_POST['Date'];


$connection = new mysqli("localhost","root","","airlineresvervationsystem");
if($connection->connect_error){
	die("Connection failed: ".$connection->connect_error."\n");}
$day_number = date("N", strtotime($Date_of_travelling));

// Query for direct flights

$sql = "SELECT Flight_no,DepartureTime,ArrivalTime FROM Passes where Airport_ID_Src='$Airport_Id_Src' && Airport_ID_Dst='$Airport_Id_Dst'  && Position('$day_number' in ArrivalDays) && Position('$day_number' in DepartureDays)";


echo "<div>";
echo "<table style='float : left' border='4' cellspacing='0' >
<tr>
<td  colspan='8'>ALL DIRECT FLIGHTS</td>
</tr>
<tr>
<th>Flight_no</th>
<th>DepartureTime</th>
<th>ArrivalTime</th>
<th><b>TOTAL DURATION</b></th>
</tr>";
$result = $connection->query($sql);
while($row = $result->fetch_assoc())
{
	          echo "<tr>";
              echo "<td>" . $row["Flight_no"]. "</td>";
              echo "<td>" . $row["DepartureTime"] . "</td>";
              echo "<td>" . $row["ArrivalTime"] . "</td>";
              $datetime1 = new DateTime($Date_of_travelling.$row["DepartureTime"]);
              $datetime2 = new DateTime($Date_of_travelling.$row["ArrivalTime"]);
              $interval = $datetime1->diff($datetime2);
              if(strtotime($row["ArrivalTime"])<=strtotime($row["DepartureTime"]))
              {
               $datetime2 = new DateTime(date('Y-m-d', strtotime($Date_of_travelling. ' + 1 days')).$row["ArrivalTime"]);
               $interval = $datetime1->diff($datetime2);
              }
              echo "<td>" .$interval->format('%h')." Hours ".$interval->format('%i')." Minutes". "</td>";
              echo "</tr>";
}
echo "</table>";
$result->free();
echo "<table border='4' cellspacing='0' style='float : left'>
<tr>
<td  colspan='8'>ALL ONE STOP FLIGHTS</td>
</tr>
<tr>
<th>Start_Flight_no</th>
<th>Start_DepartureTime</th>
<th>Mid_ArrivalTime</th>
<th>Mid_Airport</th>
<th>Mid_Flight_No</th>
<th>Mid_DepartureTime</th>
<th>Final_ArrivalTime</th>
<th><b>TOTAL DURATION</b></th>
</tr>";
$sql="SELECT Table1.Flight_no as Start_Flight_no,Table1.DepartureTime as Start_DepartureTime,Table1.ArrivalTime as Mid_ArrivalTime,Table1.Airport_ID_Dst as Mid_Airport,Table2.Flight_no as Mid_Flight_No,Table2.DepartureTime as Mid_DepartureTime,Table2.ArrivalTime as Final_ArrivalTime FROM Passes as Table1, Passes as Table2 
WHERE Table1.Airport_ID_Dst = Table2.Airport_ID_Src  && Table1.Airport_ID_Src='DEL' && Table2.Airport_ID_Dst='CCU'
&& Position('2' in Table1.DepartureDays) && Position('2' in Table1.ArrivalDays) && Position('2' in Table2.DepartureDays)
&& Position('2' in Table2.DepartureDays) && time(Table1.ArrivalTime)<(Table2.DepartureTime)";
$result = $connection->query($sql);
while($row = $result->fetch_assoc())
{
	          echo "<tr>";
              echo "<td>" . $row["Start_Flight_no"]. "</td>";
              echo "<td>" . $row["Start_DepartureTime"] . "</td>";
              echo "<td>" . $row["Mid_ArrivalTime"] . "</td>";
              echo "<td>" . $row["Mid_Airport"] . "</td>";
              echo "<td>" . $row["Mid_Flight_No"] . "</td>";
              echo "<td>" . $row["Mid_DepartureTime"] . "</td>";
              echo "<td>" . $row["Final_ArrivalTime"] . "</td>";
              $datetime1 = new DateTime($Date_of_travelling.$row["Start_DepartureTime"]);
              $datetime2 = new DateTime($Date_of_travelling.$row["Final_ArrivalTime"]);
              $interval = $datetime1->diff($datetime2);
              if(strtotime($row["Final_ArrivalTime"])<strtotime($row["Start_DepartureTime"]))
              {
               $datetime2 = new DateTime(date('Y-m-d', strtotime($Date_of_travelling. ' + 1 days')).$row["Final_ArrivalTime"]);
               $interval = $datetime1->diff($datetime2);
              }
              echo "<td>" .$interval->format('%h')." Hours ".$interval->format('%i')." Minutes". "</td>";
              echo "</tr>";
}
echo "</table>";
echo "</div>";
$result->free();
$connection->close();
?>
