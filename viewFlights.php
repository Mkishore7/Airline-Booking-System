<?php
session_start();

$Airport_Id_Src = $_POST['Source'];
$Airport_Id_Dst = $_POST['Destination'];
$_SESSION["Airport_Id_Dst"]=$Airport_Id_Dst;
$_SESSION["Airport_Id_Src"]=$Airport_Id_Src;
$Date_of_travelling =$_POST['Date'];
$_SESSION["Date_of_travelling"]=$Date_of_travelling;
$Class = $_POST['Class'];
$No_of_Seats = $_POST['no_of_seats'];
$Via = $_POST['Via'];

$connection = new mysqli("localhost","root","","airlineresvervationsystem");
if($connection->connect_error){
  die("Connection failed: ".$connection->connect_error."\n");}
$day_number = date("N", strtotime($Date_of_travelling));

$sql = "SELECT DISTINCT Flight_no,DepartureTime,ArrivalTime FROM Passes where Airport_ID_Src='$Airport_Id_Src' && Airport_ID_Dst='$Airport_Id_Dst'  && Position('$day_number' in ArrivalDays) && Position('$day_number' in DepartureDays)";

$col = $Class.'Price';


if($Via=='DIRECT')
{
echo "<table style='float : left' border='4' cellspacing='0' >
<tr>
<td  colspan='8'>ALL DIRECT FLIGHTS</td>
</tr>
<tr>
<th>Flight_no</th>
<th>DepartureTime</th>
<th>ArrivalTime</th>
<th>TOTAL DURATION</th>
<th>PRICE</th>
<th><b>BOOK</b><th>
</tr>";
$result = $connection->query($sql);
while($row = $result->fetch_assoc())
{
              $var = $row["Flight_no"];
              $sql1 = "SELECT $col from flights where Flight_no='$var'";
              $result1 = $connection->query($sql1);
              $price =0;
              while($row1 = $result1->fetch_assoc())
              {
                 $price = $row1["$col"];
              }
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
              $duration = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
              echo "<td>" .$interval->format('%h')." Hours ".$interval->format('%i')." Minutes". "</td>";
              echo "<td>". $price."</td>";
              echo "<td>";
              echo '<form action="\AirlineReservationSystem\ticketInformation.php" method="post">';
              echo '<input type="hidden" name="DepartureTime" value= '.$row["DepartureTime"].' > ';
              echo '<input type="hidden" name="ArrivalTime" value= '.$row["ArrivalTime"].' > ';
              $x = $row["Flight_no"];
              echo '<input type="hidden" name="Flight_no" value="'.$x.'" >';
              echo '<input type="hidden" name="Duration" value= "'.$duration.'" > ';
              echo '<input type="hidden" name="No_of_Seats" value= '.$No_of_Seats.' > ';
              echo '<input type="hidden" name="Price" value= '.$price.' > ';
              echo '<input type="hidden" name="Class" value= '.$Class.' > ';
              echo '<input type="submit" name="submit" value ="submit">';
              echo '</form>';
              echo "</td>";
              echo "</tr>";
}
echo "</table>";
$result->free();
}
else if($Via=='ONE STOP')
{
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
<th>Price</th>
<th>TOTAL DURATION</th>
<th><b>BOOK NOW</b></th>
</tr>";

$STARTING=$_SESSION["Airport_Id_Src"];
$ENDING=$_SESSION["Airport_Id_Dst"];
$sql="SELECT DISTINCT Table1.Flight_no as Start_Flight_no,Table1.DepartureTime as Start_DepartureTime,Table1.ArrivalTime as Mid_ArrivalTime,Table1.Airport_ID_Dst as Mid_Airport,Table2.Flight_no as Mid_Flight_No,Table2.DepartureTime as Mid_DepartureTime,Table2.ArrivalTime as Final_ArrivalTime FROM Passes as Table1, Passes as Table2 
WHERE Table1.Airport_ID_Dst = Table2.Airport_ID_Src  && Table1.Airport_ID_Src='$STARTING' 
&& Table2.Airport_ID_Dst='$ENDING'
&& Position('$day_number' in Table1.DepartureDays) && Position('$day_number' in Table1.ArrivalDays) && Position('$day_number' in Table2.DepartureDays)
&& isAvailable(Table1.Flight_no,Table1.ArrivalTime,Table1.DepartureTime,'$Class',convert('$Date_of_travelling',date),$No_of_Seats)>0
&& isAvailable(Table2.Flight_no,Table2.ArrivalTime,Table2.DepartureTime,'$Class',convert('$Date_of_travelling',date),$No_of_Seats)>0
&& Position('$day_number' in Table2.DepartureDays) && convert(Table1.ArrivalTime,time) < convert(Table2.DepartureTime,time) && convert(Table1.DepartureTime,time) < convert(Table1.ArrivalTime,time) && convert(Table2.DepartureTime,time) < convert(Table2.ArrivalTime,time) ORDER BY (TIME_TO_SEC(TIMEDIFF(Table1.ArrivalTime, Table1.DepartureTime))+TIME_TO_SEC(TIMEDIFF(Table2.DepartureTime, Table1.ArrivalTime))+TIME_TO_SEC(TIMEDIFF(Table2.ArrivalTime, Table2.DepartureTime)))";


$result = $connection->query($sql);
while($row = $result->fetch_assoc())
{
              $var = $row["Start_Flight_no"];
              $sql1 = "SELECT $col from flights where Flight_no='$var'";
              $result1 = $connection->query($sql1);
              $price =0;
              while($row1 = $result1->fetch_assoc())
              {
                 $price = $row1["$col"];
              }
              $var = $row["Mid_Flight_No"];
              $sql1 = "SELECT $col from flights where Flight_no='$var'";
              $result1 = $connection->query($sql1);
              $price =0;
              while($row1 = $result1->fetch_assoc())
              {
                 $price = $price + $row1["$col"];
              }
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
              $duration=$interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
              echo "<td>" . $price . "</td>";
              echo "<td>" .$interval->format('%h')." Hours ".$interval->format('%i')." Minutes". "</td>";
              echo "<td>";
              echo '<form action="\AirlineReservationSystem\ticketInformation_indirect.php" method="post">';
              echo '<input type="hidden" name="Start_DepartureTime" value= '.$row["Start_DepartureTime"].' > ';
              echo '<input type="hidden" name="Mid_ArrivalTime" value= '.$row["Mid_ArrivalTime"].' > ';
              echo '<input type="hidden" name="Start_Flight_no" value="'.$row["Start_Flight_no"].'" >';
              echo '<input type="hidden" name="Mid_Airport" value="'.$row["Mid_Airport"].'" >';
              echo '<input type="hidden" name="Mid_Flight_No" value="'.$row["Mid_Flight_No"].'" >';
              echo '<input type="hidden" name="Mid_DepartureTime" value="'.$row["Mid_DepartureTime"].'" >';
              echo '<input type="hidden" name="Final_ArrivalTime" value="'.$row["Final_ArrivalTime"].'" >';
              echo '<input type="hidden" name="Duration" value= "'.$duration.'" > ';
              echo '<input type="hidden" name="No_of_Seats" value= '.$No_of_Seats.' > ';
              echo '<input type="hidden" name="Price" value= '.$price.' > ';
              echo '<input type="hidden" name="Class" value= '.$Class.' > ';
              echo '<input type="submit" name="submit" value ="submit">';
              echo '</form>';
              echo "</td>";
              echo "</tr>";
}
echo "</table>";
$result->free();
}

else
{
echo "<table border='4' cellspacing='0' style='float : left'>
<tr>
<td  colspan='8'>ALL TWO STOP FLIGHTS</td>
</tr>
<tr>
<th>Start_Airport</th>
<th>Start_Flight_no</th>
<th>Start_DepartureTime</th>
<th>First_Stop_ArrivalTime</th>
<th>First_Stop_Airport</th>
<th>First_Stop_Flight_No</th>
<th>First_Stop_DepartureTime</th>
<th>Second_Stop_ArrivalTime</th>
<th>Second_Stop_Airport</th>
<th>Second_Stop_Flight_No</th>
<th>Second_Stop_DepartureTime</th>
<th>Final_ArrivalTime</th>
<th>Final_Airport</th>
<th>Price</th>
<th>TOTAL DURATION</th>
<th><b>BOOK NOW</b></th>
</tr>";

$sql = "SELECT DISTINCT t1.Airport_ID_Src as Start_Airport,t1.Flight_no as Start_Flight_no,t1.DepartureTime as Start_DepartureTime,t1.ArrivalTime as First_Stop_ArrivalTime, t1.Airport_ID_Dst as First_Stop_Airport, t2.Flight_no as First_Stop_Flight_No, t2.DepartureTime as First_Stop_DepartureTime, t2.ArrivalTime as Second_Stop_ArrivalTime, t2.Airport_ID_Dst as Second_Stop_Airport, t3.Flight_no as Second_Stop_Flight_No, t3.DepartureTime as Second_Stop_DepartureTime , t3.ArrivalTime as Final_ArrivalTime ,t3.Airport_ID_Dst as Final_Airport from Passes as t1, Passes as t2, Passes as t3  where 
t1.Airport_ID_Src = '$Airport_Id_Src' 
&& t1.Airport_ID_Dst = t2.Airport_ID_Src 
&& t2.Airport_ID_Dst = t3.Airport_ID_Src 
&& t3.Airport_ID_Dst = '$Airport_Id_Dst' 
&& isAvailable(t1.Flight_no,t1.ArrivalTime,t1.DepartureTime,'$Class',convert('$Date_of_travelling',date),$No_of_Seats)>0
&& isAvailable(t2.Flight_no,t2.ArrivalTime,t2.DepartureTime,'$Class',convert('$Date_of_travelling',date),$No_of_Seats)>0
&& isAvailable(t3.Flight_no,t3.ArrivalTime,t3.DepartureTime,'$Class',convert('$Date_of_travelling',date),$No_of_Seats)>0
&& Position('$day_number' in t1.DepartureDays) && Position('$day_number' in t1.ArrivalDays) 
&& Position('$day_number' in t2.DepartureDays) && Position('$day_number' in t1.DepartureDays)
&& Position('$day_number' in t3.DepartureDays) && Position('$day_number' in t3.DepartureDays)
&& convert(t2.DepartureTime,time)< convert(t2.ArrivalTime,time)
&& convert(t1.ArrivalTime,time) < convert(t2.DepartureTime,time)
&& convert(t2.ArrivalTime,time) < convert(t3.DepartureTime,time)
&& convert(t1.DepartureTime,time) < convert(t1.ArrivalTime,time)
&& convert(t3.DepartureTime,time) < convert(t3.ArrivalTime,time)
ORDER BY (TIME_TO_SEC(TIMEDIFF(t1.ArrivalTime, t1.DepartureTime))
+TIME_TO_SEC(TIMEDIFF(t2.DepartureTime, t1.ArrivalTime))
+TIME_TO_SEC(TIMEDIFF(t2.ArrivalTime, t2.DepartureTime))
+TIME_TO_SEC(TIMEDIFF(t3.DepartureTime, t2.ArrivalTime))
+TIME_TO_SEC(TIMEDIFF(t3.ArrivalTime, t3.DepartureTime))) limit 100";
//var_dump($sql);

$result = $connection->query($sql);
while($row = $result->fetch_assoc())
{
              $price =0;
              /*
               Calculate price...
              */
              echo "<tr>";
              echo "<td>" . $row["Start_Airport"]. "</td>";
              echo "<td>" . $row["Start_Flight_no"]. "</td>";
              echo "<td>" . $row["Start_DepartureTime"]. "</td>";
              echo "<td>" . $row["First_Stop_ArrivalTime"]. "</td>";
              echo "<td>" . $row["First_Stop_Airport"]. "</td>";
              echo "<td>" . $row["First_Stop_Flight_No"]. "</td>";
              echo "<td>" . $row["First_Stop_DepartureTime"]. "</td>";
              echo "<td>" . $row["Second_Stop_ArrivalTime"]. "</td>";
              echo "<td>" . $row["Second_Stop_Airport"]. "</td>";
              echo "<td>" . $row["Second_Stop_Flight_No"]. "</td>";
              echo "<td>" . $row["Second_Stop_DepartureTime"]. "</td>";
              echo "<td>" . $row["Final_ArrivalTime"]. "</td>";
              echo "<td>" . $row["Final_Airport"]. "</td>";
              $datetime1 = new DateTime($Date_of_travelling.$row["Start_DepartureTime"]);
              $datetime2 = new DateTime($Date_of_travelling.$row["Final_ArrivalTime"]);
              $interval = $datetime1->diff($datetime2);
              if(strtotime($row["Final_ArrivalTime"])<strtotime($row["Start_DepartureTime"]))
              {
               $datetime2 = new DateTime(date('Y-m-d', strtotime($Date_of_travelling. ' + 1 days')).$row["Final_ArrivalTime"]);
               $interval = $datetime1->diff($datetime2);
              }
              $duration=$interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
              echo "<td>" . $price . "</td>";
              echo "<td>" .$interval->format('%h')." Hours ".$interval->format('%i')." Minutes". "</td>";
              echo "<td>";
              echo '<form action="\AirlineReservationSystem\ticketInformation_twostop.php" method="post">';
              echo '<input type="hidden" name="Start_Airport" value="'.$row["Start_Airport"].'" >';
              echo '<input type="hidden" name="Start_Flight_no" value="'.$row["Start_Flight_no"].'" >';
              echo '<input type="hidden" name="Start_DepartureTime" value="'.$row["Start_DepartureTime"].'" >';
              echo '<input type="hidden" name="First_Stop_ArrivalTime" value="'.$row["First_Stop_ArrivalTime"].'" >';
              echo '<input type="hidden" name="First_Stop_Airport" value="'.$row["First_Stop_Airport"].'" >';
              echo '<input type="hidden" name="First_Stop_Flight_No" value="'.$row["First_Stop_Flight_No"].'" >';
              echo '<input type="hidden" name="First_Stop_DepartureTime" value="'.$row["First_Stop_DepartureTime"].'" >';
              echo '<input type="hidden" name="Second_Stop_ArrivalTime" value="'.$row["Second_Stop_ArrivalTime"].'" >';
              echo '<input type="hidden" name="Second_Stop_Airport" value="'.$row["Second_Stop_Airport"].'" >';
              echo '<input type="hidden" name="Second_Stop_Flight_No" value="'.$row["Second_Stop_Flight_No"].'" >';
            echo '<input type="hidden" name="Second_Stop_DepartureTime" value="'.$row["Second_Stop_DepartureTime"].'" >';
              echo '<input type="hidden" name="Final_ArrivalTime" value="'.$row["Final_ArrivalTime"].'" >';
              echo '<input type="hidden" name="Final_Airport" value="'.$row["Final_Airport"].'" >';
              echo '<input type="hidden" name="Duration" value= "'.$duration.'" > ';
              echo '<input type="hidden" name="No_of_Seats" value= '.$No_of_Seats.' > ';
              echo '<input type="hidden" name="Price" value= '.$price.' > ';
              echo '<input type="hidden" name="Class" value= '.$Class.' > ';
              echo '<input type="submit" name="submit" value ="submit">';
              echo '</form>';
              echo "</td>";
              echo "</tr>"; 
}

} 

$connection->close();
?>