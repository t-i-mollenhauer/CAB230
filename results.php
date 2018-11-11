<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'loginsystem/db.php';

//gets the number of the park from the url bar and gathers information about the park to display
$id = $_GET['number'];

$result = $connect->prepare('SELECT * FROM data WHERE id = :id');
$result->execute( array(':id' => $id));

$count=$result->rowCount();
if($count>0){
    $row = $result->fetch(PDO::FETCH_ASSOC);
}
else {
   $_SESSION['message'] = "Park not found";
}


//rating system

//checks to see if the user is logged in
if (isset($_SESSION['username'])){



    //checks to see if the user has selected the submit button
    if(isset($_POST['submit'])) {
        if(isset($_POST['Rating'])){        
            $rating = $_POST['Rating'];
            $review = $_POST['writtenreview'];
            $userid = $_SESSION['id'];
            echo $rating;
            

            //makes sure a user does not review the same park twice
            $testusername = $connect->prepare('SELECT * FROM rating WHERE userid = :userid 
             AND  parkid=:parkid');
            $testusername->execute( array(':userid' => $userid, ':parkid'=>$id));
            $testnumber=$testusername->rowCount();
            if($testnumber>0){
                echo "<script type='text/javascript'>alert('You have already reviewed this park!')</script>";
                header("Refresh:0");

            }
            else{

            //inserts new review information into the database
                try{
                    $stmt = 'INSERT INTO rating (userid, parkid, review, outofnumber) VALUES (:userid, :parkid, :review, :outofnumber)';
                    $query = $connect->prepare( $stmt );
                    $query->execute( array(':userid'=>$userid, ':parkid'=>$id, ':review'=>$review, ':outofnumber'=>$rating) );
                }
                catch(PDOException $e) {
                    echo $e->getMessage();
                } 
            }
        }
    }
} 





//gets rating information about the park
$id = $_GET['number'];
$test = 0;
$result1 = $connect->prepare('SELECT * FROM rating WHERE parkid = :id');
$result1->execute( array(':id' => $id));

$count1=$result1->rowCount();
if($count1>0){
    $test ++;
    $row1 = $result1->fetchALL(PDO::FETCH_ASSOC);
}
?>








<html>

<head>
    <title>input title</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/results.css" media="screen" />
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script type="text/javascript">


    //gets the geolocation data from the url
    function getUrlVars() {
      var vars = {};
      var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
      return vars;
  }

  //sets up the map
  function initialize() {

    var lat = getUrlVars()["lat"];
    var lon = getUrlVars()["lon"];


    var myLatlng = new google.maps.LatLng(lat,lon);
    var mapOptions = {
        zoom: 14,
        center: myLatlng,
        disableDefaultUI: true
    }
    
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    
    //displays a pin in the correct location
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Hello World!'
    });

    
    overlay = new CustomMarker(
        myLatlng, 
        map,
        {
            marker_id: '123'
        }
        );
}

google.maps.event.addDomListener(window, 'load', initialize);



</script>




</head>
<body>
    <!-- Nav Bar -->
    <div class="top_bar_2">

        <button onclick="location.href='searchcurrent.php'".style.display='block'"" id="btnHome">home</button>
        <?php if (isset($_SESSION['username'])):?>
        <button onclick="location.href='loginsystem/logout.php'".style.display='block'"" id="btnLogOut">logout</button>
    <?php else: ?>
    <button onclick="location.href='loginsystem/register.php'".style.display='block'"" id="btnSignUp">Sign Up</button>
    <button onclick="location.href='loginsystem/login.php'".style.display='block'"" id="btnLogin">login</button>
<?php endif; ?>
</div>
<!-- Main Body -->

<div id="container">
    <div id="overallBackground"></div>
    <div id="map-canvas"></div>
    <div id="border"></div>
    <div id="text">
        <h1 id="mainheadding"> <?php echo $row['Name']; ?> </h1>
        <h2 id="Address"><?php echo $row['Street']; ?>, <?php echo $row['Suburb']; ?> </h2>
        
        <!--If there is a review on the park display it in a table -->
        <?php if ($test > 0):?>  
        <table class="table">
            <h1 id="headerReview">User reviews</h1>
            <tr>
                <th>Review</th>
                <th>Raiting</th>
            </tr>
            <?php foreach( $row1 as $row ){
                echo "<tr><td>";
                echo $row['outofnumber']. '/5';
                echo "</td><td>";
                echo $row['review'];
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>


    <!--If are no reviews display that there are no reviews -->
    <?php else: ?>
    <h2 id="Address" class="ofItsOwn">There are no reviews on this park yet</h2>

<?php endif; ?>


<?php


//if the user is logged in display the option to leave a review
if (isset($_SESSION['username'])):?>

<div class="body-content">
  <div class="module" id="module">
    <h1 id="headerLeaveReview">Leave a review of this park</h1>
    <form class="form" method="post" enctype="multipart/form-data" autocomplete="off">

        <input type="radio" name="Rating" value="1"/> One
        <input type="radio" name="Rating" value="2"/> Two
        <input type="radio" name="Rating" value="3"/> Three
        <input type="radio" name="Rating" value="4"/> Four
        <input type="radio" name="Rating" value="5"/> Five
        <br/>
        <input type="text" placeholder="Write a rewview about this park" name="writtenreview" required>        
        <input type="submit" value="submit" name="submit" class="btn btn-block btn-primary" />
        
        
    </form>
</div>
</div>


<?php else: ?>
    <!--If the user is not logged in ask the user to login to leave a review-->
    <h1 id="headerNotLogin"><A HREF = "loginsystem/login.php">Login</a> to leave a review</h1>
<?php endif; ?>


<!--Displays micordata with the geolocation of the park-->
<ul>
  <li itemscope itemtype="http://schema.org/Place">

    <div itemprop="geo"
    itemscope
    itemtype="http://schema.org/GeoCoordinates">
    <meta itemprop="latitude" content=<?php echo $_GET['lat']; ?> />
    <meta itemprop="longitude" content=<?php echo $_GET['lon']; ?>/>
</div>
</li>
</ul>

</body>
</html>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg_b3lC32x3jzGuD_XnMgQkN8AtmhLREo&callback=myMap"></script>