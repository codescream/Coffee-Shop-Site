<?php
session_start();

    include('database.php');

     /***********jun11*****************/
    /************************************OSAMA CODE**********************************/
    
if($_GET['action'] == "click")
{
  $id = $db->quote($_GET['prod_name']); 
  $catName = $_GET['prod_name'];

  $id = $db->quote($_GET['prod_name']);
  $query = ("SELECT * FROM tbl_product /***********jun11*****************/
    JOIN tbl_prodcategory as pr 
    ON tbl_product.cat_id = pr.cat_id 
    WHERE pr.cat_name = $id ");
  $statement = $db->prepare($query);    
  $statement->execute();    
  $products = $statement->fetchAll();?>
  <i class="fas fa-times fa-2x close" id="closedetails" onclick="Close(this.id);"></i>
  <div class="header"><?php echo strtoupper($catName).' Menu'?></div> <!--/***********jun11*****************/-->
  <div id="order_page">               
  <?php foreach($products as $p){?>   
    <div class="main_order_div">
      <div class="item_main">   

             

        <div class="img" ><img class = "prod_img" src='<?php echo 'assets/item/' . $p['prod_img'] . '.webp' ?>' alt="product" ></div>
        <table class="tableStyl">
          <caption>"<?php echo $p['prod_name']?>"</caption>
          <caption>Description : <?php echo $p['prod_desc']?></caption>
          <tr class="tr_order">
            <td class="td_order">Size</td>
            <td class="td_order"> large </td>
            <td class="td_order"> medium </td>
            <td class="td_order"> small</td>
          </tr>
          <tr class="tr_order">
            <td class="td_order">price</td>
            <td class="td_order"><?php echo '$ '.$p['prod_price'] ?></td>
            <td class="td_order"><?php echo '$ '.($p['prod_price'] * 0.7)?></td>
            <td class="td_order"><?php echo '$ '.($p['prod_price'] * 0.5)?></td>
          </tr> 
        </table>                
      </div>
      <div class="order_main">  
        <div class="prodSize">
        <input id=<?php echo "pd_price".$p['prod_id'] ?> value =<?php echo $p['prod_price'] ?> type="hidden">
          <select id=<?php echo "prodSize".$p['prod_id'] ?> class="sizeList" 
                        onchange="UpdatePrice(<?php echo $p['prod_id']?>)"
                        onclick= enabel(<?php echo $p['prod_id']?>)>

            <option value="W">What Size?</option>
            <option value="3">Large</option> <!--value sould be 123-->
            <option value="2">Medium </option>
            <option value="1">Small</option>                
          </select>
        </div>
        <div id="qtys" >
          <input id=<?php echo "bott".$p['prod_id'] ?> class="bott"  disabled type="button" value="+"  
                    onclick="addItems(<?php echo $p['prod_id']?>); enabelAdd(<?php echo $p['prod_id']?>);">

          <select id=<?php echo "list".$p['prod_id'] ?> class="list" disabled 
                    onchange="UpdatePrice(<?php echo $p['prod_id']?>) ; enabelAdd(<?php echo $p['prod_id']?>);"
                    onmousedown="if(this.options.length>8){this.size=10;}"  onchange='this.size=0;' onblur="this.size=0;" >
            <?php
              for ($i=0; $i<=100; $i++)
              {
                ?>
                  <option  value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php
              }
            ?>
          </select>
          <input id=<?php echo "bott2".$p['prod_id'] ?> class="bott" type="button" value="-" disabled 
                  onclick="remItems(<?php echo $p['prod_id']?>); enabelAdd(<?php echo $p['prod_id']?>);">
        </div>          
        <div id=<?php echo "prices".$p['prod_id'] ?> class="prices"></div>
        <input id=<?php echo "addCartBt".$p['prod_id'] ?> type="button" class="addCartBt" value="Add to cart" disabled onclick="AddToCart(<?php echo $p['prod_id'] ?>); clearOrder(<?php echo $p['prod_id'] ?>)">        
      </div>      
    </div>              
  <?php } ?>
  </div> <!--order page     -->
<?php } 
else if($_GET['action'] == "getMultiplier")
{
  global $db;
  $size_id = $db->quote($_GET['prod_size']);
  $query = "SELECT * FROM tbl_sizemultiplier WHERE size_id = $size_id";

  $result = $db->query($query);
  $result_row = $result->fetch();

  echo $result_row['multiplier'];
}
  /***************************************OSAMA CODE END*************************************************/
  else if($_GET['action'] == "hover")
  {
    $id = $db->quote($_GET['prod_name']); 

    $query = "SELECT * FROM tbl_prodcategory WHERE cat_name = $id";

    $statement = $db->prepare($query);    
    $statement->execute();    
    $prods_cat = $statement->fetchAll();
    foreach($prods_cat as $cat)
    {
      $cat_id = $cat['cat_id'];
    }
    $id = $db->quote($cat_id); 
    $query = "SELECT * FROM tbl_product WHERE cat_id = $id";
    $statement = $db->prepare($query);    
    $statement->execute();    
    $prods_per_cat = $statement->fetchAll();
    echo "<ul>";
    foreach($prods_per_cat as $prod)
    {
      echo "<li>" . $prod['prod_name'] . "</li>";
    }
    echo "</ul>";
  }
?>