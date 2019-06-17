<?php
    session_start();

    include('database.php');?>
<div class="headerHistory">"Order History"</div>
<i class="fas fa-times fa-2x close" id="close_contact" onclick="Close_about();"></i>
<input id="userID" type="hidden" value="<?php echo $_SESSION['user_id'];?>">
<?php
    if($_GET['action'] == "click")
    {
        $userID = $_GET['userID'];
        $query1= ("SELECT * FROM tbl_item AS itm
                    JOIN tbl_cart crt ON itm.cart_id = crt.cart_id
                    WHERE crt.cart_close !='NULL' and crt.user_id =$userID
                    GROUP BY crt.cart_id ");
        $cartID = $db->prepare($query1);    
        $cartID->execute();    
        $cartIDs = $cartID->fetchAll();?>
        <?php if(count($cartIDs) > 0){
            foreach($cartIDs as $cart_id ){ 
            $id_cart = $cart_id['cart_id'];
            $query = ("SELECT * FROM tbl_product AS pro
                        JOIN tbl_item AS itm
                        ON pro.prod_id = itm.prod_id 
                        JOIN tbl_cart AS car
                        ON itm.cart_id = car.cart_id
                        JOIN tbl_sizemultiplier AS siz
                        ON itm.size_id = siz.size_id
                        WHERE car.cart_id = $id_cart ");
            $statement = $db->prepare($query);    
            $statement->execute();    
            $cartItems = $statement->fetchAll();?>
            <div id="history_page">
            <div class="caption_hist"> DATE : <?php echo  $cart_id['cart_close']?></div>
            <?php foreach($cartItems as $cart){ ?>            
                <div class="histOrder_main">
                <div class="hist_img" ><img class = "history_img" src='<?php echo 'assets/item/' . $cart['prod_img'] . '.webp' ?>' alt="product" ></div>                
                    <table>
                        <tr>
                            <td class="td_header">Name</td>
                            <td class="td_header">Size</td>                            
                            <td class="td_header">Qty</td>                            
                            <td class="td_header">Price</td>
                            <td class="td_header">Total</td>
                        </tr>
                        <tr>
                            <td class="td_hist"><?php echo  $cart['prod_name']?></td>
                            <td class="td_hist"><?php echo  $cart['multiplier_desc']?></td>                            
                            <td class="td_hist"><?php echo  $cart['qty']?></td>
                            <td class="td_hist"><?php echo ($cart['prod_price'] * $cart['multiplier']).' $' ?></td>
                            <td class="td_hist"><?php echo ($cart['prod_price'] * $cart['multiplier'] * $cart['qty']) .' $' ?></td>
                        </tr>
                    </table>
                </div>
            <?php }?>
            </div>
        <?php } 
        }
        else{?>
           <div class="noHistory">"No Order History"</div> 
        <?php }   
    } ?>
