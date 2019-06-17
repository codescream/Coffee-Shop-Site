<?php
session_start();
  require("includes/package.php");
  // include("models/getproducts.php");

  if(isset($_SESSION['session_loginerr']) || isset($_SESSION['session_signuperr']))
  {
    $message = "";
    if(isset($_SESSION['session_loginerr']))
    {
      $message = $_SESSION['session_loginerr'];
    }
    else if (isset($_SESSION['session_signuperr']))
    {
      $message = $_SESSION['session_signuperr'];
    }
    echo "<div class='message' id='loginerr' style='top: -80px'>".$message."</div>";
    echo('<script type="text/javascript">
    $("#loginerr").animate({top: 30},3000, ()=>{$("#loginerr").animate({top: -80},6000);});
    </script>'
    );
    $_SESSION['session_loginerr'] = null;
    $_SESSION['session_signuperr'] = null;
  }

  if(isset($_SESSION['session_signupsuccess']))
  {
    echo "<div class='message' id='signupsuccess' style='top: -80px'>".$_SESSION['session_signupsuccess']."</div>";
    echo('<script type="text/javascript">
    $("#signupsuccess").animate({top: 30},1000, ()=>{$("#signupsuccess").animate({top: -80},3000);});
    </script>'
    );
    $_SESSION['session_signupsuccess'] = null;
  }

  $qty_right = '29px';
  $qty_margin_right = '-8px';
  $count = 0;

  if(isset($_SESSION['item_count']))
  {
    if($_SESSION['item_count'] == "")
    {
      $count = 0;
    }
    else
    {
      $count = $_SESSION['item_count'];
    }
    
    if($_SESSION['item_count'] >= 10)
    {
      $qty_right = '32px';
      $qty_margin_right = '-15px';
    }
  }

  $cartshow = "none";

  if(isset($_SESSION['temp_session']))
  {
    $cartshow = "unset";

    if(isset($_SESSION['item_count']))
    {
      $count = $_SESSION['item_count'];
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link rel="stylesheet" href="css/app.css">
  <title>Document</title>
</head>
<body id="body">
  <img id="logo" src="assets/logo2.png" alt="boom" title="home">
  <img id="pan" src="assets/henrici_panorama.jpg" alt="boom">
  <div class="dir" id="moveleft" onclick="MoveLeft();">
    <i id="left" class="fas fa-angle-left fa-2x"></i>
  </div>
  <div class="dir" id="moveright" onclick="MoveRight();">
    <i id="right" class="fas fa-angle-right fa-2x"></i>
  </div>
  <!-- <div id="iconcontainer"></div> -->
  <!-- <img class="hover" src="assets/hover1.svg" alt="boom">
  <img class="hover" src="assets/hover2.svg" alt="boom"> -->
  
  <?php if(isset($_SESSION['cart_avail']) || isset($_SESSION['temp_session']))
  {
    echo '<form id="loginform" action="models/user_model.php" style="top: -30px;" method="post">';
  }
  else
  {
    echo '<form id="loginform" action="models/user_model.php" style="top: 0px;" method="post">';
  }
  ?>
    <?php 
      if(isset($_SESSION['session_user_name']))
      { 
        echo '<p id="welcome">Welcome '. $_SESSION['session_user_name'] . '</p>';
        echo '<input type="hidden" name="action" value="logout">';
        echo '<input type="hidden" id="cartId" value="'. $_SESSION['cart_avail'] . '">';
        echo '<img id="cart" src="assets/cart5.png" alt="boom" onclick="ShowMiniCart();" title="preview cart">';
        echo '<span id="qty" style="right:'. $qty_right .'; margin-right:'. $qty_margin_right .'" onclick="ShowMiniCart();" title="preview cart">'. $count .'</span>';
        echo '<input type="submit" id="signout" name="signout" value="Sign Out">';
      }
      else
      {
        echo '<input type="text" id="username" name="username" placeholder="username" required>';
        echo '<input type="password" name="password" id="password" placeholder="password" required>';
        echo '<input type="hidden" name="action" value="login">';
        echo '<input type="submit" id="signin" name="signin" value="Sign In">';
        echo '<img id="cart" style="display:' . $cartshow . ';" src="assets/cart5.png" alt="boom" onclick="ShowMiniCart();" title="preview cart">';
        echo '<span id="qty" style="display:' . $cartshow . '; right:'. $qty_right .'; margin-right:'. $qty_margin_right .'" onclick="ShowMiniCart();" title="preview cart">' . $count . '</span>';
        echo '<input type="button" id="signup" name="signup" value="Sign Up" onclick="ShowSignUp();">';
      }  
    ?>
  </form>

  <form id="signupform" action="models/user_model.php" method="post">
    <input type="email" class="signupform" name="email" id="email" placeholder="enter email" required><br>
    <input type="password" class="signupform" name="password" id="signuppass" placeholder="password" required><br>
    <input type="password" class="signupform" name="confpass" id="confpass" placeholder="confirm password" required><br>
    <input type="hidden" name="action" value="signup">
    <input id="signupbtn" class="signupform" type="submit" value="Create Account">
  </form>
  
  <div class="products" id="products1">
    <img class="prdicons" id="coffee" src="assets/Coffee.png" alt="boom">
    <img class="prdicons" id="frozen" name="leftadjust" src="assets/frozen.png" alt="boom">
    <img class="prdicons" id="fruit_yogurt" src="assets/fruit&yogurt.png" alt="boom">
    <img class="prdicons" id="breakfast" src="assets/breakfast.png" alt="boom">
    <img class="prdicons" id="latte" src="assets/latte.png" alt="boom">
  </div>
  <div class="products" id="products2">
    <img class="prdicons" id="tea" src="assets/tea.png" alt="boom">
    <img class="prdicons" id="bakery" name="leftadjust" src="assets/bakery.png" alt="boom">
    <img class="prdicons" id="blended" src="assets/blend.png" alt="boom">
    <img class="prdicons" id="cold_Brew" src="assets/coldBrew.png" alt="boom">
    <img class="prdicons" id="desserts" src="assets/desert.png" alt="boom">
  </div>
  <div class="products" id="products3">
    <img class="prdicons" id="lunchbox" src="assets/lunch.png" alt="boom">
    <img class="prdicons" id="products" name="leftadjust" src="assets/products.png" alt="boom">
    <img class="prdicons" id="refreshers" src="assets/refreshers.png" alt="boom">
    <img class="prdicons" id="espresso" src="assets/espresso.png" alt="boom">
    <img class="prdicons" id="frappuccino" src="assets/frapp.png" alt="boom">
  </div>

  <nav>
    <a href="#"><p id="menu" onclick="ShowMenu();">MENU</p></a>
    <img class="coffeebeans" src="assets/coffee-beans.png" alt="boom">
    <a id="about_page" href="#"><p>ABOUT US</p></a>
    <img class="coffeebeans" src="assets/coffee-beans.png" alt="boom">
    <a id="contact_bage" href="#"><p>CONTACT US</p></a>
  </nav>

  <div id="menupanel">

  </div>

  <div id="hoverpanel">
  </div>

  <!-- <div id='loginerr'>Error in username or password!!!</div> -->
  <div id="minicart" class="cart">
  </div>
  <div class="nextpage" id="fullcart">
  <!-- <div style="height: 150vh"></div> -->
  </div>
  <div class="nextpage" id="details">
    
  </div>

  <!---------------------------ABOUT-------------------------------------->
  <div class="nextpage" id="about_details">
    <div id="about_main">
      <div class="about_img">
        <img class="about_imgMa" src="assets/markpix.jpg" alt="Mark Ogilo">
        <div class="about_textMa">
        <p>
          I am currently a student, web and mobile designer and applications developer currently 
          living in London, ON, Canada. My interests range from programming to web development.
          I am also interested and skilled in various web technologies such as HTML, Javascript, CSS,
          Php, MySql, JQuery, C#, and currently studying to acquire my MCSA in SQL server 2012/2014.
            Hence you could say I'm on my way to becoming a full-stack developer. I also create some 
            unique assets I use on the web pages I have built using photoshop, illustrator, expressions
            etc.
            When I am not coding, which I find difficult to get away from, I am singing in my church 
            choir, watching a movie, or following my favorite football team in the English premier 
            league, Arsenal!
        </p>
        </div>
      </div>
      <div class="about_img">        
        <img class="about_imgOs"src="assets/Osamaj.JPG" alt="Osama Jouda">
        <p class="about_textOs">BH'S degree in computer information system & Web and Mobile developer from triOS college,
           Oracle, Oracle Database, SQL , JAVA, JavaScript, C#, Worked in Programming , Automotive
            industry,Hottel, transportation.
        </p>
      </div>
      <i class="fas fa-times fa-2x" id="close_about" onclick="Close_about();"></i>
    </div>
  </div>
<!-------------------------CONTACT-------------------------------------->
  <div class="nextpage" id="contact_details">
    <div id="contact_main">
      <i class="fas fa-times fa-2x" id="close_contact" onclick="Close_about();"></i>
      
      <div id="address">
        <label  class="locHeader">LOCATED AT:</label><br>
        <label  class="location">520 FIRST STREET</label><br>
        <label  class="location">LONDON, ON, N5V 3C6 </label><br>
        <label  class="locHeader">CONTACT </label><br>
        <label class="location">PHONE: (519) 455-0551 </label><br>
        <label class="location">PHONE: (519) 455-0551 </label><br>
      </div>

      <div id="map">
        <!--<img class="map_img" src="assets/map.png" alt="map">-->
        <script>var options = {disableDoubleClickZoom: true,draggable: false,scrollwheel: false,panControl: false}; var map;function initMap(_LatCord, _LngCord){ map = new google.maps.Map(document.getElementById('map'), { center: { lat: 43.003895, lng:  -81.195916},zoom: 17,
                                 disableDefaultUI: true , zoomControl: true ,  scrollwheel: false});var marker = new google.maps.Marker({position: { lat: 43.003895, lng:  -81.195916},map: map,title: 'London Campus'});} </script>
        <script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJsOlVnfUyr7MT6cw2bOGp9K1dp5atQpI&amp;callback=initMap" type="text/javascript"></script>
      </div>
     
      <div id="mail">
      <div class="container">
                <form method="post" class="cntForm" action="mailto:osama_jouda@hotmail.com" 
                            enctype="text/plain">
                    <label for="name">Name :</label>   
                    <input type="text" class="inputarea" name="name" value="name"/>
                    <br>
                    <label for="email">Email :</label>
                    <input type="text" class="inputarea" name="email" value="email"/>
                    <br>
                    <label for="message">Message :</label>
                    <textarea id="comment" name="comment" placeholder="Enter comments here..." required="" rows="10"></textarea>
                    <input id="submit" type="submit" value="Email">
                </form>
            </div>
    </div>
    </div>
    </div>
  
  
  <div class="nextpage" id="history_details">
    <input id="userID" type="hidden" value="<?php echo $_SESSION['user_id'];?>">
  </div>
  <!-------------------------------------------------------------------------------------------------------------->

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script> -->
  <script src="js/app.js"></script>
</body>
</html>