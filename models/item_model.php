<?php
session_start();

  include("database.php");
  // include('cart_model.php');

  $count = 0;

  if(isset($_GET['action']))
  {
    if($_GET['action'] == "addtocart")
    {
      $prodId = $_GET["prod_id"];
      $qty = $_GET["qty"];
      $size = $_GET["size"];
      addItem($qty, $prodId, $size);
    }
    else if($_GET['action'] == "getcartitem")
    {
      $cart_id = 0;
      if(isset($_SESSION['user_id']))
      {
        $cart_id = $_GET["cart_id"];
      }

      getCartItems($cart_id);
    }
    else if($_GET['action'] == "getcartqty")
    {
      getCartQty();
    }
    else if($_GET['action'] == "getfullcart")
    {
      $cart_id = 0;
      if(isset($_SESSION['user_id']))
      {
        $cart_id = $_GET["cart_id"];
      }

      getFullCart($cart_id);
    }
    else if($_GET['action'] == "addRemoveItem")
    {
      $cart_id = 0;
      $prodId = $_GET["prod_id"];
      $sizeId = $_GET["size_id"];
      $qty = $_GET["qty"];
      if(isset($_SESSION['user_id']))
      {
        $cart_id = $_SESSION['cart_avail'];
      }

      AddRemoveItem($cart_id, $prodId, $sizeId, $qty);
    }
    else if($_GET['action'] == "removeItem")
    {
      $cart_id = 0;
      $prodId = $_GET["prod_id"];
      $sizeId = $_GET["size_id"];
      $qty = $_GET["qty"];
      if(isset($_SESSION['user_id']))
      {
        $cart_id = $_SESSION['cart_avail'];
      }

      RemoveItem($cart_id, $prodId, $sizeId, $qty);
    }
    else if($_GET['action'] == "getProductCats")
    {
      getProductCats();
    }
    else if($_GET['action'] == "closecart")
    {
      $cart_id = $_GET['cart_id'];
      closeCart($cart_id);
    }
  }
  
  function fetch_cartItem($cartId)
  {
    global $db;

    $count = 0;

    $cart_id = $db->quote($cartId);

    $query = "SELECT * FROM tbl_item WHERE cart_id = $cart_id";

    $result = $db->query($query);

    $allrows = $result->fetchAll(); // will be need to show the item in the cart


    $query = "SELECT SUM(qty) AS count FROM tbl_item it JOIN tbl_cart ct ON it.cart_id = ct.cart_id WHERE ct.cart_id = $cart_id AND ct.cart_close IS NULL";

    $result = $db->query($query);

    $result_row = $result->fetch();

    if($result_row != null)
    {
      $_SESSION['item_count'] = $result_row['count'];
    }
  }

  function addItem($qty, $prodId, $size)
  {
    global $db;
    // $count = 0;
    if(isset($_SESSION['user_id']))
    {
      $qtty = $db->quote($qty);
      $prod_id = $db->quote($prodId);
      $prod_size = $db->quote($size);

      checkcart($_SESSION['user_id']);

      $cart_id = $db->quote($_SESSION['cart_avail']);

      $query = "SELECT * FROM tbl_item WHERE prod_id = $prod_id AND cart_id = $cart_id AND size_id = $prod_size";

      $result = $db->query($query);

      $result_row = $result->fetch();

      if($result_row == null)
      {
        $query = "INSERT INTO tbl_item (qty, prod_id, cart_id, size_id) VALUES ($qtty, $prod_id, $cart_id, $prod_size)";

        $insert_count = $db->exec($query);

        if($insert_count)
        {
          $query = "SELECT SUM(qty) AS count FROM tbl_item WHERE cart_id = $cart_id";

          $result = $db->query($query);

          $result_row = $result->fetch();

          $_SESSION['item_count'] = $result_row['count'];

          echo $_SESSION['item_count'] . '.' . $_SESSION['cart_avail'];
        }
      }
      else
      {
        $quantity = $result_row['qty'];

        $quantity += $qty; //= $db->quote((int)$qty + (int)$qtty);

        $query = "UPDATE tbl_item SET qty = $quantity WHERE prod_id = $prod_id AND cart_id = $cart_id AND size_id = $prod_size";

        $process = $db->exec($query);

        $query = "SELECT SUM(qty) AS count FROM tbl_item WHERE cart_id = $cart_id";

        $result = $db->query($query);

        $result_row = $result->fetch();

        $_SESSION['item_count'] = $result_row['count'];

        echo $_SESSION['item_count'] . '.' . $_SESSION['cart_avail'];;
      }
    }
    else // for non-loggedIn users
    {
      $newId = 0;
      if(!isset($_SESSION['temp_session']))
      {
        $_SESSION['temp_tbl_item'] = array();

        array_push($_SESSION['temp_tbl_item'], array($prodId, $qty, $size));

        /*get pc physical address*/
        ob_start(); // Turn on output buffering
        system('ipconfig /all');
        $mycom = ob_get_contents();
        ob_clean();
        $findme = "Physical";
        $pmac = strpos($mycom, $findme);
        $mac = substr($mycom,($pmac+36),17); 
        $_SESSION['temp_session'] = $mac; // register temp session id
        /***********************/
      }
      else
      {
        for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
        {
          if($_SESSION['temp_tbl_item'][$i][0] == $prodId && $_SESSION['temp_tbl_item'][$i][2] == $size)
          {
            $_SESSION['temp_tbl_item'][$i][1] += $qty;
            $newId = 0;
            break;
          }
          else
          {
            $newId = $prodId;
          }
        }
      }

      if($newId != 0)
      {
        array_push($_SESSION['temp_tbl_item'], array($newId, $qty, $size));
      }

      print_r(json_encode($_SESSION['temp_tbl_item'])); // array not being used in javascript
      
    }
  } // end addItem()

  function getCartItems($cartId)
  {
    global $db;
    $total = 0;
    echo '<table id="cartheader" class="cartitems">';
    echo '<tr class="cart">';
    echo '<td class="cart" style="width: 30%;"></td>';
    echo '<td class="cart" style="width: 50%;">Item</td>';
    echo '<td class="cart" style="width: 10%;">Qty</td>';
    echo '<td class="cart" style="width: 10%;">Price</td>';
    echo '</tr>';
    echo '</table>';
    if(isset($_SESSION['user_id']))
    {
      $total = 0;
      $cart_id = $db->quote($cartId);

      $query = "SELECT pr.prod_img, pr.prod_name, pr.prod_price, pr.prod_desc, it.qty, it.size_id, sz.multiplier_size, sz.multiplier, it.cart_id FROM 
      tbl_product AS pr JOIN tbl_item AS it ON pr.prod_id = it.prod_id JOIN tbl_sizemultiplier AS sz ON it.size_id = sz.size_id WHERE it.cart_id = $cart_id";

      $result = $db->query($query);

      $result_rows = $result->fetchAll();

      foreach($result_rows as $rows)
      {
        echo '<table class="cartitems">';
        echo '<tr class="cart">';
        echo '<td class="cart" style="width: 30%; text-align: left;"><img src="assets/item/'. $rows['prod_img'] .'.webp" alt="boom"></td>';
        echo '<td class="cart" style="width: 50%;"><p>'. $rows['prod_name'] . '(' . $rows['multiplier_size'] .')</p></td>';
        echo '<td class="cart" style="width: 10%;"><p>'. $rows['qty'] .'</p></td>';
        $total += number_format($rows['prod_price'] * $rows['qty'] * $rows['multiplier'] , 2);
        echo '<td class="cart" style="width: 10%;"><p>'. "$" . number_format($rows['prod_price'] * $rows['qty'] * $rows['multiplier'] , 2) .'</p></td>';
        echo '</tr>';
        echo '</table>';
      }
    }
    else // for non-loggedIn users
    {
      $total = 0;
      for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
      {
        $prod_id = $db->quote($_SESSION['temp_tbl_item'][$i][0]);
        $query = "SELECT prod_name, prod_img, prod_price FROM tbl_product WHERE prod_id = $prod_id";

        $result = $db->query($query);

        $result_rows = $result->fetchAll();

        $size_id = $db->quote($_SESSION['temp_tbl_item'][$i][2]);

        $query_size = "SELECT multiplier_size, multiplier FROM tbl_sizemultiplier WHERE size_id = $size_id";

        $size_result = $db->query($query_size);

        $size_row = $size_result->fetch();

        foreach($result_rows as $rows)
        {
          echo '<table class="cartitems">';
          echo '<tr class="cart">';
          echo '<td class="cart" style="width: 30%; text-align: left;"><img src="assets/item/'. $rows['prod_img'] .'.webp" alt="boom"></td>';
          echo '<td class="cart" style="width: 50%;"><p>'. $rows['prod_name'] . '(' . $size_row['multiplier_size'] .')</p></td>';
          echo '<td class="cart" style="width: 10%;"><p>'. $_SESSION['temp_tbl_item'][$i][1] .'</p></td>';
          $total += number_format(($rows['prod_price'] * $_SESSION['temp_tbl_item'][$i][1] * $size_row['multiplier']), 2);
          echo '<td class="cart" style="width: 10%;"><p>'. "$" . number_format(($rows['prod_price'] * $_SESSION['temp_tbl_item'][$i][1] * $size_row['multiplier']), 2) .'</p></td>';
          echo '</tr>';
          echo '</table>';
        }
      }
    }
    echo '<hr id="carthr">';
    echo('<div id="subtotaldiv">
          <p id="subtotal">SubTotal:
          <span class="totalprice">$' . number_format($total, 2) . '</span>
          </p>
          </div>');
    echo '<button id="gotocart" onclick="OpenCart();">Go To Cart</button>';
  } // end getCartItems()

  function getFullCart() // using variable length parameter listing
  {
    global $db;
    $total = 0;
    $total_qty = 0;

    if(isset($_SESSION['user_id']))
    {
      $total = 0;
      $cart_id = $db->quote(func_get_arg(0)); // cartId is passed in

      $query = "SELECT pr.prod_id, pr.prod_img, pr.prod_name, pr.prod_price, pr.prod_desc, it.qty, it.size_id, sz.multiplier_size, sz.multiplier, it.cart_id FROM 
      tbl_product AS pr JOIN tbl_item AS it ON pr.prod_id = it.prod_id JOIN tbl_sizemultiplier AS sz ON it.size_id = sz.size_id WHERE it.cart_id = $cart_id";

      $result = $db->query($query);

      $result_rows = $result->fetchAll();

      echo '<div id="fullcartheader"><h1 id="cartdesc">Your Cart</h1></div>';
      echo '<i class="fas fa-times fa-2x close" id="closefullcart" onclick="Close(this.id);"></i>';
      echo '<div id="itemscontainer">';
      echo '<div id="cartitems" class="cartdivs">';
      foreach($result_rows as $rows)
      {
        $total += number_format($rows['prod_price'] * $rows['qty'] * $rows['multiplier'] , 2);
        $total_qty += intVal($rows['qty']);
        echo '<div class="items">';
        echo '<p class="namedesc">' . $rows['prod_name'] . '(' . $rows['multiplier_size'] .')' . '-' . $rows['prod_desc'] . '</p>';
        echo '<div class="imgdiv">';
        echo '<div class="cartimgdiv removediv"><img src="assets/item/'. $rows['prod_img'] . '.webp" alt="boom"></div>';
        echo '<div class="addremove removediv">';
        echo '<p>$' . number_format($rows['prod_price'] * $rows['multiplier'], 2) . '</p>';
        if( $rows['qty'] == 1)
        {
          echo '<p><span class="spanminusdisbabled"><i class="fas fa-minus"></i></span>' . $rows['qty'] . 
          '<span class="spanplus" id="spanplus_' . $rows['prod_id'] . '_' . $rows['size_id'] .'" onclick="AddRemoveItem(this.id);"><i class="fas fa-plus"></i></span></p>';
          echo '<input type="hidden" id="'. $rows['prod_id'] . '_' . $rows['size_id'] .'" name="'. $rows['prod_id'] .'" value="' . $rows['qty'] .'">';
        }
        else
        {
          echo '<p><span class="spanminus" id="spanminus_' . $rows['prod_id'] . '_' . $rows['size_id'] .'" onclick="AddRemoveItem(this.id);"><i class="fas fa-minus"></i></span>' . $rows['qty'] . 
          '<span class="spanplus" id="spanplus_' . $rows['prod_id'] . '_' . $rows['size_id'] .'" onclick="AddRemoveItem(this.id);"><i class="fas fa-plus"></i></span></p>';
          echo '<input type="hidden" id="'. $rows['prod_id'] . '_' . $rows['size_id'] .'" name="'. $rows['prod_id'] .'" value="' . $rows['qty'] .'">';
        }
        echo '</div>';
        echo '</div>';
        echo '<p class="remove"><span class="removespan" id="remove_' . $rows['prod_id'] . '_' . $rows['size_id'] . '" onclick="AddRemoveItem(this.id);">Remove</span></p>';
        echo '<hr class="fullcarthr">';
        echo ('<div class="prdTotaldiv">
              <p class="prdTotal">Product Total
              <span class="totalprice">$' . number_format($rows['prod_price'] * $rows['qty'] * $rows['multiplier'], 2) . '</span>
              </p>
              </div>');
        echo '</div>'; 
      }
      $_SESSION['item_count'] = $total_qty;
      $plural = ($total_qty > 1) ? 'items' : 'item';
      echo '<input type="hidden" id="fullcartqty" value=' . $total_qty . '>'; // cart span qty
      echo '</div>';
      echo '<div id="cartsummary" class="cartdivs">';
      echo '<h2>Order Summary</h2>';
      echo '<p>Product Subtotal<span class="totalprice">$' . number_format($total, 2) . '</span></p>';
      echo '<p>Order Discounts<span class="totalprice">-$' . '0.00' . '</span></p>';
      echo '<p>Estimated Shipping<span class="totalprice">' . 'FREE' . '</span></p>';
      $tax = number_format($total * 0.15, 2);
      echo '<p>Estimated Taxes<span class="totalprice">$' . $tax . '</span></p>';
      echo '<hr class="fullcarthr">';
      echo '<p>Estimated Total<span class="totalprice">$' . number_format(($tax + $total), 2) . '</span></p>';
      echo '<button onclick="CloseCart(' . $_SESSION['cart_avail'] . ');">Continue to Checkout</button>';
      echo '</div>';
      echo ('<div id="totsummary">
            <p>Estimated Total<span class="totalprice">' . ' $' . number_format(($tax + $total), 2) . '</span><span id="itemtotal" class="totalprice">(' . $total_qty . ' ' . $plural . ')</span></p>
            <button onclick="CloseCart(' . $_SESSION['cart_avail'] . ');">Continue to Checkout</button>
            </div>');
      echo '</div>';
    }
    else
    {
      echo '<div id="fullcartheader"><h1 id="cartdesc">Your Cart</h1></div>';
      echo '<i class="fas fa-times fa-2x close" id="closefullcart" onclick="Close(this.id);"></i>';
      echo '<div id="itemscontainer">';
      echo '<div id="cartitems" class="cartdivs">';

      for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
      {
        $prod_id = $db->quote($_SESSION['temp_tbl_item'][$i][0]);
        $size_id = $db->quote($_SESSION['temp_tbl_item'][$i][2]);

        $query = "SELECT pr.prod_id, pr.prod_img, pr.prod_name, pr.prod_desc, pr.prod_price FROM tbl_product AS pr WHERE pr.prod_id = $prod_id";

        $result = $db->query($query);
        $rows = $result->fetch();

        $query_size = "SELECT sz.multiplier_size, sz.multiplier FROM tbl_sizemultiplier AS sz WHERE sz.size_id = $size_id";

        $size_result = $db->query($query_size);
        $size_rows = $size_result->fetch();

        $total += number_format($rows['prod_price'] * $_SESSION['temp_tbl_item'][$i][1] * $size_rows['multiplier'] , 2);
        $total_qty += intVal($_SESSION['temp_tbl_item'][$i][1]);
        echo '<div class="items">';
        echo '<p class="namedesc">' . $rows['prod_name'] . '(' . $size_rows['multiplier_size'] .')' . '-' . $rows['prod_desc'] . '</p>';
        echo '<div class="imgdiv">';
        echo '<div class="cartimgdiv removediv"><img src="assets/item/'. $rows['prod_img'] . '.webp" alt="boom"></div>';
        echo '<div class="addremove removediv">';
        echo '<p>$' . number_format($rows['prod_price'] * $size_rows['multiplier'], 2) . '</p>';
        if( $_SESSION['temp_tbl_item'][$i][1] == 1)
        {
          echo '<p><span class="spanminusdisbabled"><i class="fas fa-minus"></i></span>' . $_SESSION['temp_tbl_item'][$i][1] . 
          '<span class="spanplus" id="spanplus_' . $rows['prod_id'] . '_' . $_SESSION['temp_tbl_item'][$i][2] .'" onclick="AddRemoveItem(this.id);"><i class="fas fa-plus"></i></span></p>';
          echo '<input type="hidden" id="'. $rows['prod_id'] . '_' . $_SESSION['temp_tbl_item'][$i][2] .'" name="'. $rows['prod_id'] .'" value="' . $_SESSION['temp_tbl_item'][$i][1] .'">';
        }
        else
        {
          echo '<p><span class="spanminus" id="spanminus_' . $rows['prod_id'] . '_' . $_SESSION['temp_tbl_item'][$i][2] .'" onclick="AddRemoveItem(this.id);"><i class="fas fa-minus"></i></span>' . $_SESSION['temp_tbl_item'][$i][1] . 
          '<span class="spanplus" id="spanplus_' . $rows['prod_id'] . '_' . $_SESSION['temp_tbl_item'][$i][2] .'" onclick="AddRemoveItem(this.id);"><i class="fas fa-plus"></i></span></p>';
          echo '<input type="hidden" id="'. $rows['prod_id'] . '_' . $_SESSION['temp_tbl_item'][$i][2] .'" name="'. $rows['prod_id'] .'" value="' . $_SESSION['temp_tbl_item'][$i][1] .'">';
        }
        echo '</div>';
        echo '</div>';
        echo '<p class="remove"><span class="removespan" id="remove_' . $rows['prod_id'] . '_' . $_SESSION['temp_tbl_item'][$i][2] .'" onclick="AddRemoveItem(this.id);">Remove</span></p>';
        echo '<hr class="fullcarthr">';
        echo ('<div class="prdTotaldiv">
              <p class="prdTotal">Product Total
              <span class="totalprice">$' . number_format($rows['prod_price'] * $_SESSION['temp_tbl_item'][$i][1] * $size_rows['multiplier'], 2) . '</span>
              </p>
              </div>');
        echo '</div>'; 
      }
      $plural = ($total_qty > 1) ? 'items' : 'item';
      echo '<input type="hidden" id="fullcartqty" value=' . $total_qty . '>'; // cart span qty
      echo '</div>';
      echo '<div id="cartsummary" class="cartdivs">';
      echo '<h2>Order Summary</h2>';
      echo '<p>Product Subtotal<span class="totalprice">$' . number_format($total, 2) . '</span></p>';
      echo '<p>Order Discounts<span class="totalprice">-$' . '0.00' . '</span></p>';
      echo '<p>Estimated Shipping<span class="totalprice">' . 'FREE' . '</span></p>';
      $tax = number_format($total * 0.15, 2);
      echo '<p>Estimated Taxes<span class="totalprice">$' . $tax . '</span></p>';
      echo '<hr class="fullcarthr">';
      echo '<p>Estimated Total<span class="totalprice">$' . number_format(($tax + $total), 2) . '</span></p>';
      echo '<button onclick="CloseCart(0);">Continue to Checkout</button>';
      echo '</div>';
      echo ('<div id="totsummary">
            <p>Estimated Total<span class="totalprice">' . ' $' . number_format(($tax + $total), 2) . '</span><span id="itemtotal" class="totalprice">(' . $total_qty . ' ' . $plural . ')</span></p>
            <button onclick="CloseCart(0);">Continue to Checkout</button>
            </div>');
      echo '</div>';
    }
  } // end getFullCart()

  function getCartQty()
  {
    $cartqty = 0;
    if(isset($_SESSION['temp_session']))
    {
      for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
      {
        $cartqty += $_SESSION['temp_tbl_item'][$i][1];
      }

      print_r(json_encode($_SESSION['temp_tbl_item']));
    }
    else
    {
      if(isset($_SESSION['item_count']))
      {
        echo $_SESSION['item_count'];
      }
      else
      {
        echo "success everywhere!";
      }
    }
  } // end getCartQty()

  function moveTempData($id)
  {
    global $db;

    $same = false;
    $prod_qty = 0;
    $prod_id = 0;
    $size = 0;

    $user_id = $db->quote($id);

    $query = "SELECT cart_id FROM tbl_cart WHERE user_id = $user_id AND cart_close IS NULL";

    $result = $db->query($query);

    $result_row = $result->fetch();

    if($result_row != null)
    {
      $cart_id = $db->quote($result_row['cart_id']);
      $query = "SELECT * FROM tbl_item WHERE cart_id = $cart_id";

      $result = $db->query($query);

      $result_rows = $result->fetchAll();
      for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
      {
        foreach($result_rows as $row)
        {
          if($row['prod_id'] == $_SESSION['temp_tbl_item'][$i][0] && $row['size_id'] == $_SESSION['temp_tbl_item'][$i][2])
          {
            $prod_qty = intval($row['qty']);
            $prod_id = $row['prod_id'];
            $size = $row['size_id'];
            $same = true;
            break;
          }
        }
        if($same)
        {
          $tmp_qty = intval($_SESSION['temp_tbl_item'][$i][1]);
          $new_qty = $db->quote($tmp_qty + $prod_qty);
          $query = "UPDATE tbl_item SET qty = $new_qty WHERE prod_id = $prod_id AND cart_id = $cart_id AND size_id = $size";

          $process = $db->exec($query);
        }
        else
        {
          $tmp_qty = $db->quote($_SESSION['temp_tbl_item'][$i][1]);
          $prod_id = $db->quote($_SESSION['temp_tbl_item'][$i][0]);
          $size_id = $db->quote($_SESSION['temp_tbl_item'][$i][2]);

          $query = "INSERT INTO tbl_item (qty, prod_id, cart_id, size_id) VALUES ($tmp_qty, $prod_id, $cart_id, $size_id)";

          $insert_count = $db->exec($query);
        }
      }
    }

    unset($_SESSION['temp_tbl_item']);
  } // end moveTempData()

  function AddRemoveItem($cart_id, $prod_id, $size_id, $qty)
  {
    global $db;

    if(isset($_SESSION['user_id']))
    {
      $cartId = $db->quote($cart_id);
      $prodId = $db->quote($prod_id);
      $sizeId = $db->quote($size_id);
      $qtty = $db->quote($qty);

      $query = "UPDATE tbl_item SET qty = $qtty WHERE prod_id = $prodId AND cart_id = $cartId AND size_id = $sizeId";

      $process = $db->exec($query);

      getFullCart($cart_id);
    }
    else
    {
      for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
      {
        if($_SESSION['temp_tbl_item'][$i][0] == $prod_id && $_SESSION['temp_tbl_item'][$i][2] == $size_id)
        {
          $_SESSION['temp_tbl_item'][$i][1] = $qty;
        }
      }
      getFullCart();
    }
  } // AddRemoveItem()

  function RemoveItem($cart_id, $prodId, $sizeId, $qty)
  {
    global $db;

    if(isset($_SESSION['user_id']))
    {
      $cartId = $db->quote($cart_id);
      $prod_id = $db->quote($prodId);
      $size_id = $db->quote($sizeId);

      $query = "DELETE FROM tbl_item WHERE cart_id = $cartId AND prod_id = $prod_id AND size_id = $size_id";

      $delete = $db->exec($query);

      getFullCart($cart_id);
    }
    else
    {
      for($i = 0; $i < count($_SESSION['temp_tbl_item']); $i++)
      {
        if($_SESSION['temp_tbl_item'][$i][0] == $prodId && $_SESSION['temp_tbl_item'][$i][2] == $sizeId)
        {
          unset($_SESSION['temp_tbl_item'][$i]);
          $_SESSION['temp_tbl_item'] = array_values($_SESSION['temp_tbl_item']);
          if(count($_SESSION['temp_tbl_item']) <= 0)
          {
            unset($_SESSION['temp_session']);
          }
        }
      }
      getFullCart();
    }
  } // RemoveItem()

  function getProductCats()
  {
    global $db;
    $query = "SELECT * FROM tbl_prodcategory";

    $result = $db->query($query);

    $result_row = $result->fetch();

    if(isset($_SESSION['user_id']))
    {
      echo '<h2 id="ordhistory" onclick="test();">ORDER HISTORY</h2>';
      echo '<hr id="hrhistory">';
    }
    
    echo '<table id="menutable">';
    while($result_row != null)
    {
      echo '<tr class="menurow">';
      for($i = 0; $i < 3; $i++)
      {
        echo '<td class="menuitem" id="' . $result_row['cat_name'] . '" onclick="ShowCatDetails(this.id);"><i class="far fa-window-minimize fa-2x pipe"></i><p class="menudata">' 
        . $result_row['cat_name'] . '</p></td>';
        $result_row = $result->fetch();
      }
      echo '</tr>';
      // $result_row = $result->fetch();
    }
    echo '</table>';
  } // getProductCats()

  function closeCart($cartId)
  {
    global $db;
    if(isset($_SESSION['user_id']))
    {
      $cart_Id = $db->quote($cartId);

      $query = "UPDATE tbl_cart SET cart_close = NOW() WHERE cart_id = $cart_Id";

      $result = $db->exec($query);

      $_SESSION['item_count'] = 0;
    }
    else
    {
      unset($_SESSION['temp_tbl_item']);
      unset($_SESSION['temp_session']);
    }
    

    echo '<div id="fullcartheader"><h1 id="cartdesc">Your Cart</h1></div>';
    echo '<i class="fas fa-times fa-2x close" id="closefullcart" onclick="Close(this.id);"></i>';
    echo '<h1 id="checkout">CHECKOUT COMPLETED</h1>';
    echo '<h2 id="cartclosed">CART CLOSED</h2>';
  }

  function checkcart($id)
  {
    global $db;
    
    $user_id = $db->quote($id);

    $query = "SELECT * FROM tbl_cart WHERE user_id = $id AND cart_close IS NULL";

    $result = $db->query($query);

    $result_row = $result->fetch();

    if($result_row != null)
    {
      $_SESSION['cart_avail'] = $result_row['cart_id'];
      fetch_cartItem($result_row['cart_id']); // in item_model.php
    }
    else
    {
      $query = "INSERT INTO tbl_cart (cart_open, user_id) VALUES (NOW(), $user_id)";

      // echo "id is:".$id;
      $insert_cart = $db->exec($query);

      if(isset($_SESSION['cart_avail']))
      {
        unset($_SESSION['cart_avail']);
      }
      $_SESSION['cart_avail'] = $db->lastInsertId();
    }
  }
?>





