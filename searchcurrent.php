<!DOCTYPE>
<html>
<head>
<?php



  require 'loginsystem/db.php';


  error_reporting(E_ALL);
  ini_set('display_errors', 1);


    // Selects the park information from the Park information database "data" and puts it into an array
  $sql = "SELECT * FROM data";
  $query = $connect->prepare( $sql );
  $query->execute();
  $emparray = array();
  while($results = $query->fetchAll( PDO::FETCH_ASSOC ))
  {
    $emparray[] = $results;
  }
  ?>




  <title>Input Title for Website</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/random.css" media="screen" />


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- Font from Google Font! -->
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"> </script>

  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>


  <!-- creates a map on the home page -->
  <script type="text/javascript">
  function initialize() {

    var myLatlng = new google.maps.LatLng(-27.49972065,153.0858355);
    var mapOptions = {
      zoom: 14,
      center: myLatlng,
      disableDefaultUI: true
    }

    var map = new google.maps.Map(document.getElementById('map'), mapOptions);
  }
  google.maps.event.addDomListener(window, 'load', initialize);

  </script>


</head>
<body>

  <!-- top nav -->
  <div class="top_bar">
    <div id="backgroundNav"></div>

    <!-- if the user is logged in displays the logout button and hides signup and login -->
    <?php if (isset($_SESSION['username'])):?>
    <button onclick="location.href='loginsystem/logout.php'".style.display='block' id="btnLogOut">logout</button>
  <?php else: ?>
  <button onclick="location.href='loginsystem/register.php'".style.display='block' id="btnSignUp">Sign Up</button>
  <button onclick="location.href='loginsystem/login.php'".style.display='block' id="btnLogin" >login</button>
<?php endif; ?>


</div>
<div id="container">

  <div id="backgroundMainBody"></div>

  <div id="searchSection">
    <!-- div for the map -->
    <div id="map"></div>



    <!-- Main heading -->
    <h1 id="searchSecMainHeader">Welcome to MAPS!</h1>
    <h2 id="searchSecSecondHeader">Search to find your PARK! </h2>
    <form id="firstForm">
     <div>


      <!-- displays the list of suburbs for the user to select -->
      <?php if (isset($_GET['Suburbs'])):?>
      <input type="text" id="txt-search" placeholder="Search for a park"
      value="<?php echo $_GET['Suburbs'] ?>">

    <?php else: ?>
    <input type="text" id="txt-search" placeholder="Search for a park">
  <?php endif; ?>
  <input id="btnSearch" type="submit" value="Search">
</div>


<!-- Gets the lits of suburbs for the above div to display -->
<?php
$result = $connect->query('SELECT DISTINCT Suburb '.'FROM data '.'WHERE id > 0 ORDER BY Suburb');
echo '<select id="dropSuburb" name = "Suburbs">';
echo '<option value="',null,'">','Select Suburb..','</option>';
foreach ($result as $suburb){
  $iterator++;
  echo '<option value="',$suburb['Suburb'],'">',$suburb['Suburb'],'</option>';
}
echo '</select>';


?>
<input type="submit" value="Locate parks near you" id="submitSearch">
<br/> <br/>




<div id="filter-records"></div>



</div>

<div id="footer">
  <p>CAB230 Maps Tim and Jesse </p>
</div>

</body>
</html>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg_b3lC32x3jzGuD_XnMgQkN8AtmhLREo&callback=myMap"></script>





<script type="text/javascript">
// find a way to gather data from json file rather then from the html document
$(document).ready(function(){

    //converts php array of data into json array
    var data = <?php echo json_encode($emparray[0]); ?>


    console.log(data);

    var  test = $('#txt-search');

//unified serach bar
test.keyup(function(){
  var searchField = $(this).val();
  if(searchField === '')  {
    $('#filter-records').html('');
    return;
  }

            //searches using regular expressions for a number a suburb or a name
            var regex = new RegExp(searchField, "i");
            var numsearch = new RegExp(searchField, "i");
            var output = '<div class="row">';
            var count = 1;
            $.each(data, function(key, val){
              if ((val.Name.search(regex) != -1) || (val.Suburb.search(regex) != -1)

                    //edit search so it is literal rather then searching for any characater
                    || (val.id.search(numsearch) != -1)) {
                var address = ".php?number="+val.id+"&lat="+val.Latitude+"&lon="+val.Longitude+"";

                  // displays reuslts
                  output += '<div><a href=results'+ address +'>';
                  output += '</div>';

                  if (count < 20){
                    output += '<div class= "test">';
                    output += '<h5>' + val.Name + '</h5>';
                    output += '</div>';
                  }
                  count++;
                  
                }
              });
            $('#filter-records').html(output);
          });
});

</script>






