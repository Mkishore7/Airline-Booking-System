<?php
  require_once('server.php');
  require_once('templates/header.php');

?>

<body>
  <?php include('templates/navbar.php'); ?>

<?php
  if(isset($_POST['reject'])){

    //connect to database

    $dbc= mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($dbc, trim($_GET['Ticket_ID']));

    $update_status_query = "UPDATE ticket SET Booking_Status='0',Cancellation_Status='1',CancellationTime=Current_timestamp() WHERE Ticket_ID='$id'";
    $update_status = mysqli_query($dbc, $update_status_query);
    if(!$update_status){
      echo '<div class="container"><div class="alert alert-warning alert-dismissible fade show" role="alert">' .
        'Failed to update. Please try again.' . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
        '<span aria-hidden="true">&times;</span></button></div></div>';
      die("QUERY FAILED ".mysqli_error($dbc));
    } else {
      echo '<div class="container"><div class="alert alert-success alert-dismissible fade show" role="alert">' .
          'Successfully Updated.<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button></div></div>';
    }
  }
?>

  <div class="container" style="margin: 50px 25px 100px 100px;">
    <div class="tab-content" id="TabContent">
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">S.No.</th>
              <th scope="col">User ID</th>
              <th scope="col">Passenger Name</th>
              <th scope="col">Source-Destination</th>
              <th scope="col">Flight Number</th>
              <th scope="col">Arrival</th>
              <th scope="col">Departure</th>
              <th scope="col">Date of Travel</th>
              <th scope="col">Cancel</th>
            </tr>
          </thead>

          <?php
          $dbc= mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          if (!$dbc) {
            die("Connection failed: " . mysqli_connect_error());
          }

            $query = "SELECT * FROM ticket WHERE Booking_Status='1'";
            $data = mysqli_query($dbc, $query);
            if(mysqli_num_rows($data) != 0){
          ?>
          <tbody>
            <?php
              $curr = 1;
              while($row = mysqli_fetch_array($data)){
                echo '<tr><th scope="row">' . $curr . '</th>' .
                          '<td>' . $row["User_ID"] . '</td>' .
                          '<td>' . $row["PassengerName"] . '</td>' .
                          '<td>' . $row["Src"] . '-' . $row["Dst"] .'</td>' .
                          '<td>' . $row["Flight_no"] . '</td>' .
                          '<td>' . $row["ArrivalTime"] . '</td>' .
                          '<td>' . $row["DepartureTime"] . '</td>' .
                          '<td>' . $row["Date_of_departure"] . '</td>' .
                          '<td><form action="' . $_SERVER['PHP_SELF'] . '?Ticket_ID=' . $row["Ticket_ID"] . '&tab=1" method="post">' .
                          '<button type="reject" class="btn btn-outline-danger" name="reject">Cancel</button></form></td>' .
                      '</tr>';
                $curr = $curr + 1;
              }
            ?>
          </tbody>
          <?php } else { ?>
            <tr>
              <td>No data</td>
            </tr>
          <?php } ?>
        </table>
      </div>
  </div>


</body>

<?php require_once('templates/footer.php'); ?>
