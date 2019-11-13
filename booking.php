<?php
  require_once('server.php');
  require_once('templates/header.php');

  if(isset($_POST['reject'])){

    //connect to database

    $dbc= mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$dbc) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $id = mysqli_real_escape_string($dbc, trim($_GET['id']));

    $update_status_query = "UPDATE bookings SET status='rejected' WHERE id='$id'";
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

// Delete the booking in bookedrooms
    try {
      $update_status_query = "DELETE FROM bookedrooms WHERE id='$id'";
      $update_status = mysqli_query($dbc, $update_status_query);
    } finally {
      //finally block is required.
    }

    $activeTab = $_GET['tab'];
  }
?>

<body>
  <?php include('templates/navbar.php'); ?>

    <div class="tab-content" id="TabContent">
        <table class="table">
          <thead class="thead-light">
            <tr>
              <th scope="col">S.No.</th>
              <th scope="col">Indentor Name</th>
              <th scope="col">Guest Name</th>
              <th scope="col">Guest Number</th>
              <th scope="col">Rooms Alloted</th>
              <th scope="col">Arrival</th>
              <th scope="col">Departure</th>
              <th scope="col">Change Status</th>
            </tr>
          </thead>

          <?php
          $dbc= mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
          if (!$dbc) {
            die("Connection failed: " . mysqli_connect_error());
          }

            $query = "SELECT id, indentorname, guestname, guestphone, requestedrooms, arrival, departure FROM ticket WHERE status='accepted'";
            $data = mysqli_query($dbc, $query);
            if(mysqli_num_rows($data) != 0){
          ?>
          <tbody>
            <?php
              $curr = 1;
              while($row = mysqli_fetch_array($data)){
                echo '<tr><th scope="row">' . $curr . '</th>' .
                          '<td>' . $row["indentorname"] . '</td>' .
                          '<td>' . $row["guestname"] . '</td>' .
                          '<td>' . $row["guestphone"] . '</td>' .
                          '<td>' . $row["requestedrooms"] . '</td>' .
                          '<td>' . $row["arrival"] . '</td>' .
                          '<td>' . $row["departure"] . '</td>' .
                          '<td><form action="' . $_SERVER['PHP_SELF'] . '?id=' . $row["id"] . '&tab=1" method="post">' .
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


</body>
