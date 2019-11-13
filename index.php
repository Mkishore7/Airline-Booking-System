
<?php
  session_start();
  require_once('templates/header.php');
  require_once('connect.php');
?>

<body>
  <nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Home</a>
    <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button> -->
    <!-- <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
      </ul>
      <span class="navbar-text">
        Navbar text with an inline element
      </span>
        <a class="nav-link" href="#">Pricing</a>
    </div> -->
    <?php if(!isset($_SESSION['username'])) {?>

    <div class="dropdown">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Sign In
      </a>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <a class="dropdown-item" href="#">Sign In</a>
        <a class="dropdown-item" href="#">Sign Up</a>
      </div>
    </div>
  <?php } else {echo $_SESSION['username'];} ?>
  </nav>

  <div class="container-fluid">

    <span>
    <h1 class="display-4" style="text-align:center; margin: 50px 0px 30px 0px">AIRLINE BOOKING SYSTEM</h1>
    <h1 class="display-5" style="text-align:center;">Book Now</h1>
    </span>

    <!-- <div class="container px-lg-5">
      <div class="row mx-lg-n5">
        <div class="col py-3 px-lg-5 border bg-light">Custom column padding</div>
        <div class="col py-3 px-lg-5 border bg-light">Custom column padding</div>
        <div class="col py-3 px-lg-5 border bg-light">Custom column padding</div>
        <div class="col py-3 px-lg-5 border bg-light">Custom column padding</div>
      </div>
    </div>
 -->
 <div class="container" style="margin:50px 100px 100px 100px;">
   <h1><b>Search Flights</b></h1>
   <br />
   <p><b>Choose your flight option</b></p>
   <div class="btn-group btn-group-justified">
     <div class="btn-group">
       <button id="button1" type="button" href="#oneway" class="btn btn-dark">One-way</button>
     </div>
     <div class="btn-group">
       <button id="button2" type="button" href="#roundtrip" class="btn btn-dark">Round-trip</button>
     </div>
     <div class="btn-group">
       <button id="button3" type="button" href="#all" class="btn btn-dark">Search all flights</button>
     </div>
   </div>
   <hr />
   <div id="oneway">
     <form role="form" action="SearchResultOneway.php" method="post">
       <div class="row">
         <div class="col-sm-6">
           <label for="from">From:</label>
           <input type="text" class="form-control" id="from" name="from" placeholder="City or Code" required>
         </div>
         <div class="col-sm-6">
           <label for="to">To:</label>
           <input type="text" class="form-control" id="to" name="to" placeholder="City or Code" required>
         </div>
       </div>
       <hr >
       <div class="row">
         <div class="col-sm-6">
           <label for="depart">Depart:</label>
           <input type="date" class="form-control" id="depart" name="depart" required>
         </div>
         <hr >
         <div class="col-sm-6">
           <label for="class">Class:</label>
           <select class="form-control" name="class">
             <option value="Economy">Economy</option>
             <option value="Business">Business</option>
           </select>
         </div>
       </div>
       <br>
       <div class="row">
         <div class="col-sm-6">
           <label class="radio-inline">
             <input type="radio" name="stop" value="nonstop" checked>Non-Stop
           </label>
           <label class="radio-inline">
             <input type="radio" name="stop" value="1stop">1 Stop
           </label>
         </div>
       </div>
       <br>
       <div class="btn-group btn-group-justified">
         <div class="btn-group">
           <button type="submit" class="btn btn-success">Submit</button>
         </div>
         <div class="btn-group">
           <button type="reset"  class="btn btn-info" value="Reset">Reset</button>
         </div>
       </div>
     </form>
   </div>
   <div id="roundtrip">
     <form role="form" action="SearchResultRoundtrip.php" method="post">
       <div class="row">
         <div class="col-sm-6">
           <label for="from">From:</label>
           <input type="text" class="form-control" id="from" name="from" placeholder="Code " required>
         </div>
         <div class="col-sm-6">
           <label for="to">To:</label>
           <input type="text" class="form-control" id="to" name="to" placeholder="Code" required>
         </div>
       </div>
       <hr >
       <div class="row">
         <div class="col-sm-6">
           <label for="depart">Depart:</label>
           <input type="date" class="form-control" id="depart" name="depart" required>
         </div>
         <div class="col-sm-6">
           <label for="return">Return:</label>
           <input type="date" class="form-control" id="return" name="return" required>
         </div>
       </div>
       <hr >
       <div class="row">
         <div class="col-sm-6">
           <label for="class">Class:</label>
           <select class="form-control" name="class">
             <option value="Economy">Economy</option>
             <option value="Business">Business</option>
           </select>
         </div>
       </div>
       <br>
       <div class="form-group">
         <label class="radio-inline">
           <input type="radio" name="stop" value="nonstop" checked>Non-Stop
         </label>
       </div>
       <div class="btn-group btn-group-justified">
         <div class="btn-group">
           <button type="submit" class="btn btn-success">Submit</button>
         </div>
         <div class="btn-group">
           <button type="reset"  class="btn btn-info" value="Reset">Reset</button>
         </div>
       </div>
     </form>
   </div>
   <div id="all">
     <form role="form" action="SearchResultAll.php" method="post">
       <div class="row">
         <div class="col-sm-6">
           <label for="selectdate">Select a date:</label>
           <input type="date" class="form-control" id="selectdate" name="selectdate" required>
         </div>
       </div>
       <br>
       <div class="row">
         <div class="col-sm-6">
           <button type="submit" class="btn btn-primary">Show ALL</button>
         </div>
       </div>
     </form>

   </div>
 </div>

</div>




<?php
  require_once('templates/footer.php');
?>
