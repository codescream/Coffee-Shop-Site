
let image = document.getElementById("pan");
let body = document.getElementById("body");
let width = body.offsetWidth;
let imgwidth = image.offsetWidth;
let width_overlap = image.offsetWidth - body.offsetWidth;
let move = 0;
let prdicons;
let position = 2;
let cat_name;
let signup = false;
let showminicart = false;
let openmenu = false;
let temp_tblItem = [];
let cartqty = 0;
$("#pan").css("left", body.offsetWidth/2)
$("#iconcontainer").css("width", imgwidth);

$("#products2").css({"width": (body.offsetWidth - 80) + "px", "display": "block"});
    $("#products2").animate({
      height: "100vh"
    }, 1000);

function MoveLeft()
{
  if(parseInt($("#pan").css("left")) === Math.floor(body.offsetWidth/2))
  {
    move = image.offsetWidth/2;
    $("#products1").css({"width": (body.offsetWidth - 80) + "px", "display": "block"});
    $("#products2").css({"height": "0vh", "display": "none"});
    
    $("#products1").animate({
      height: "100vh"
    }, 1000);

    position = 1;
  }
  else
  {
    position = 2;
    move = body.offsetWidth/2;
    $("#products1").css({"height": "0vh", "display": "none"});
    $("#products3").css({"height": "0vh", "display": "none"});
    $("#products2").css({"width": (body.offsetWidth - 80) + "px", "display": "block"});
    $("#products2").animate({
      height: "100vh"
    }, 1000);
  }

  $("#pan").animate({
    left: move
  }, 1000);

  $("#iconcontainer").animate({
    left: move
  }, 1000);
}

function MoveRight()
{
  $("#products1").css({"height": "0vh", "display": "none"});
  $("#products2").css({"height": "0vh", "display": "none"});
  if(parseInt($("#pan").css("left")) === Math.floor(body.offsetWidth/2))
  {
    position = 3;
    $("#products3").css({"width": (body.offsetWidth - 80) + "px", "display": "block"});
    $("#products3").animate({
      height: "100vh"
    }, 1000);
    move = body.offsetWidth - image.offsetWidth/2;
  }
  else
  {
    position = 2;
    move = body.offsetWidth/2;
    $("#products2").css({"width": (body.offsetWidth - 80) + "px", "display": "block"});
    $("#products3").css({"height": "0vh", "display": "none"});
    $("#products2").animate({
      height: "100vh"
    }, 1000);
  }
  $("#pan").animate({
    left: move
  }, 1000);
  
  $("#iconcontainer").animate({
    left: move
  }, 1000);
}

$(window).resize(()=>{
  $("#pan").css("left", body.offsetWidth/2);
  position = 2;
  $("#products2").css({"width": (body.offsetWidth - 80) + "px", "height": "100vh", "display": "block"});
  $("#products1").css({"height": "0vh", "display": "none"});
  $("#products3").css({"height": "0vh", "display": "none"});
});

function AddToCart(id)
{
  let prod_id = id;
  let qty = $("#list"+id).val();
  let size = $("#prodSize" + prod_id).val();
  let newId = 0;
  let action = "addtocart";
  cartqty = 0;
  
  $.ajax({
    type: "GET",
    url: "./models/item_model.php",
    data: {"action": action, "prod_id": prod_id, "qty": qty, "size": size},
    success: (data)=>{
      if(isNaN(data))
      {
        
        temp_tblItem.length = [];

        for(let i = 0; i < JSON.parse(data).length; i++)
        {
          temp_tblItem.push(JSON.parse(data)[i]);
          cartqty += parseInt(JSON.parse(data)[i][1]);
        }

        if(cartqty >= 10)
        {
          $("#qty").css({"right": "32px", "margin-right": "-15px"});
        }
        else
        {
          $("#qty").css({"right": "29px", "margin-right": "-8px"});
        }

        $("#qty").html(cartqty);
        $("#cart").css("display","unset");
        $("#qty").css("display","unset");
        $("#loginform").css("top", "-30px");
      }
      else
      {
        let count_cart = data.split(".");
        $("#qty").html(Number(count_cart[0]));
        $("#cartId").val(count_cart[1]);
        if(parseInt(data) >= 10)
        {
          $("#qty").css({"right": "32px", "margin-right": "-15px"});
        }
        else
        {
          $("#qty").css({"right": "29px", "margin-right": "-8px"});
        }
      }
    }
  });
}

$(window).load(function(){
  prdicons = document.getElementsByName("leftadjust");

  let action = "getcartqty";
  
  $.ajax({
    type: "GET",
    url: "./models/item_model.php",
    data: {"action": action},
    success: (data)=>{
      if(isNaN(Number(data)))
      {
        for(let i = 0; i < JSON.parse(data).length; i++)
        {
          temp_tblItem.push(JSON.parse(data)[i]);
          cartqty += parseInt(JSON.parse(data)[i][1]);
        }
      }
      else
      {
        if(data == "")
        {
          cartqty = 0;
        }
        else
        {
          cartqty = parseInt(data);
        }
      }
      
      if(cartqty >= 10)
      {
        $("#qty").css({"right": "32px", "margin-right": "-15px"});
      }
      else
      {
        $("#qty").css({"right": "29px", "margin-right": "-8px"});
      }
      
      $("#qty").html(cartqty);

      $("#cart").css("display","unset");
      $("#qty").css("display","unset");
      $("#loginform").css("top", "-30px");
    }
  });
});

function Close(id)
{
  let div_id = $("#" + id).parent().attr("id");

  if(id == "closefullcart")
  {
    $("#" + div_id).animate({
      top: '-100vh'
    }, 2000);
  }
  else
  {
    $("#" + div_id).css({"width": "0%", "height": "0vh"});
  }
  $(".close").css({"position": "absolute", "right": "9px"});
}

function ShowSignUp()
{
  if(!signup)
  {
    $("#signupform").animate({
      top: 30
    }, 1000, ()=>{signup = true;});
  }
  else
  {
    $("#signupform").animate({
      top: -200
    }, 1000, ()=>{signup = false;});
  }
}

function ShowMiniCart()
{
  let opencart =  ($("#qty").text() > 0) ? true : false;

  if(opencart)
  {
    let cart_id = $("#cartId").val();
    let action = "getcartitem";
    if(showminicart)
    {
      $("#minicart").animate({
        top: -400
      }, 1000, ()=>{showminicart = false;});
    }
    else
    {
      $("#minicart").animate({
        top: 30
      }, 1000, ()=>{showminicart = true;});
    }

    $.ajax({
      type: "GET",
      url: "./models/item_model.php",
      data: {"cart_id":cart_id, "action": action},
      success: function(data)
      {
        $("#minicart").html(data);
      }
    });
  }
}

function OpenCart()
{
  $("#minicart").animate({
    top: -400
  }, 1000, ()=>{showminicart = false;});

  // $(".close").css("display", "block");

  $("#fullcart").animate({
    top: 0
  }, 3000, ()=>{
    $("#fullcart").animate({
      top: '-3vh'
    }, 500, ()=>{
      $("#fullcart").animate({
        top: 0
      }, 1000, ()=>{
        if(isOverflown($("#fullcart")[0]))
        {
          $("#closefullcart").css({"position": "fixed", "right": "25px"});
        }
        else
        {
          $("#closefullcart").css({"position": "absolute", "right": "9px"});
        }
      });
    });
  });

  let action = "getfullcart";
  let cart_id = $("#cartId").val();

  $.ajax({
    type: "GET",
    url: "./models/item_model.php",
    data: {"action": action, "cart_id": cart_id},
    success: (data)=>{
      $("#fullcart").html(data);
    }
  });
}


$(".prdicons").on("click", (event)=>{
  let x = event.clientX, y = event.clientY, elementClick = document.elementFromPoint(x, y);

  $("#details").css({"left": event.clientX, "top": event.clientY});

  $("#details").animate({
    top: 0,
    left: 0,
    width: "100%",
    height: "100vh",
  }, 1000, ()=>{
    if(isOverflown($("#details")[0]))
    {
      $("#closedetails").css({"position": "fixed", "right": "25px"});
    }
    else
    {
      $("#closedetails").css({"position": "absolute", "right": "9px"});
    }
  });

  let prod = $(elementClick).attr("id");
  let action = "click";

  $.ajax({ 
    type: "GET",
    url: "./models/getproducts.php",
    data: {"prod_name":prod , "action":action},
    success: (data)=>{
      $("#details").html(data);
    }
    });
});

$(".prdicons").hover(
  (event)=>{
    let x = event.clientX, y = event.clientY, elementClick = document.elementFromPoint(x, y);

    if($(elementClick).attr("class") === "prdicons")
    {
      let prod = $(elementClick).attr("id");

      let action = "hover";
    
      $.ajax({ 
        type: "GET",
        async: false,
        url: "./models/getproducts.php",
        data: {"prod_name":prod ,"action":action},
        success: (data)=>{
          $("#hoverpanel").html(data);
        }
        });

      let top = (event.clientY + 15) + "px";
      if($(elementClick).position().left <= $(prdicons[position - 1]).position().left)
      {
        $("#hoverpanel").css({"display": "block", "left": ($(elementClick).position().left + 30) + "px", "top": top});
      }
      else
      {
        $("#hoverpanel").css({"display": "block", "left": ($(elementClick).position().left - 50) + "px", "top": top});
      }
    }
  },
  ()=>{
    // let x = event.clientX, y = event.clientY, elementClick = document.elementFromPoint(x, y);
    // if(!(elementClick.id === "hoverpanel"))
    // {
    //   $("#hoverpanel").css({"display": "none", "left": "unset", "top": "unset"});
    // }
    $("#hoverpanel").css({"display": "none", "left": "unset", "top": "unset"});
  }
);

function AddRemoveItem(id)
{
  let params = id.split("_");
  let prod_id = params[1];
  let size_id = params[2];
  let action = "addRemoveItem";
  let qty = parseInt($("#" + params[1] + "_" +  params[2]).val());

  if(params[0] === "spanplus")
  {
    qty += 1;
  }
  else if (params[0] === "spanminus")
  {
    qty -= 1;
  }
  else
  {
    action = "removeItem";
  }

  $.ajax({
    type: "GET",
    url: "./models/item_model.php",
    data:{"action": action, "prod_id": prod_id, "size_id": size_id, "qty": qty},
    success: (data)=>{
      $("#fullcart").html(data);

      if(isOverflown($("#fullcart")[0]))
      {
        $("#closefullcart").css({"position": "fixed", "right": "25px"});
      }
      else
      {
        $("#closefullcart").css({"position": "absolute", "right": "9px"});
      }

      let qty = $("#fullcartqty").val();

      if(qty >= 10)
        {
          $("#qty").css({"right": "32px", "margin-right": "-15px"});
        }
        else
        {
          $("#qty").css({"right": "29px", "margin-right": "-8px"});
        }

        $("#qty").html(qty);;
        $("#cart").css("display","unset");
        $("#qty").css("display","unset");
        $("#loginform").css("top", "-30px");
    }
  });
}

$(document).on("click", (event)=>{
  let x = event.clientX, y = event.clientY, elementClick = document.elementFromPoint(x, y);

  if($("#email").is(':focus') || $("#signuppass").is(':focus') || $("#confpass").is(':focus'))
  {
    $(document).focus();
  }
  else
  {
    if(!($(elementClick).attr("id") === "signupform" || $(elementClick).attr("class") === "signupform"))
    {
      if(signup)
      {
        $("#signupform").animate({
          top: -200
        }, 1000, ()=>{signup = false;});
      }
    }
  }

  if(!($(elementClick).attr("id") === "qty" || $(elementClick).attr("id") === "cart" 
  || $(elementClick).parent().attr("class") === "cart" || $(elementClick).attr("class") === "cart"))
  {
    $("#minicart").animate({
      top: -400
    }, 1000, ()=>{showminicart = false;});
  }

  if(!($(elementClick).attr("class") == "menudata" || $(elementClick).attr("id") == "menu" || 
  $(elementClick).attr("class") == "menuitem" || $(elementClick).attr("id") == "menupanel"))
  {
    if(openmenu)
    {
        $("#menupanel").animate({
          bottom: "-340px"
        }, 1000);
        openmenu = false;
      }
    }
});

function ShowMenu()
{
  if(!openmenu)
  {
    $("#menupanel").animate({
      bottom: "50px"
    }, 1000, ()=>{
      $("#menupanel").animate({
        bottom: "40px"
      }, 500);
    });
    openmenu = true;
  }
  else
  {
    $("#menupanel").animate({
      bottom: "-340px"
    }, 1000);
    openmenu = false;
  }

  let action = "getProductCats";

  $.ajax({
    type: "GET",
    url: "./models/item_model.php",
    data: {"action": action},
    success: (data)=>{

      $("#menupanel").html(data);
    }
  });
}

function ShowCatDetails(name) // Category details from Menu
{
  $("#details").animate({
    top: 0,
    left: 0,
    width: "100%",
    height: "100vh",
  }, 1000, ()=>{
    if(isOverflown($("#details")[0]))
    {
      $("#closedetails").css({"position": "fixed", "right": "25px"});
    }
    else
    {
      $("#closedetails").css({"position": "absolute", "right": "9px"});
    }
  });

  let prod = name;
  let action = "click";

  $.ajax({ 
    type: "GET",
    url: "./models/getproducts.php",
    data: {"prod_name":prod , "action":action},
    success: (data)=>{
      $("#details").html(data);
    }
    })
}

function CloseCart(id)
{
  let action = "closecart";

  $.ajax({
    type: "GET",
    url: "./models/item_model.php",
    data: {"action": action, "cart_id": id},
    success: (data)=>{
      $("#fullcart").html(data);

      qty = 0;
      $("#qty").css({"right": "29px", "margin-right": "-8px"});
     

        $("#qty").html(qty);
        // $("#cart").css("display","unset");
        // $("#qty").css("display","unset");
        // $("#loginform").css("top", "-30px");
    }
  });
}

function isOverflown(element) {
  return element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth;
}
/**************************************OSAMA CODE*************************************/

function addItems(valID){
  let item = document.getElementById('list' + valID).value;
  item++;
  document.getElementById('list' + valID).value = item;
  UpdatePrice(valID)
}
function remItems(valID){
  let item = document.getElementById('list'+ valID).value;
  if(item >0){
  item--;
  document.getElementById('list' + valID).value = item;
  UpdatePrice(valID)
  }
}
function UpdatePrice(val) {
  let prod_size = document.getElementById('prodSize' + val).value; 
  let prod_qty = document.getElementById('list' + val).value;
  let price = document.getElementById('pd_price' + val).value;
  let priceHtml = document.getElementById('prices'+ val);
  let price_id = 'prices'+ val;
  //price.innerHTML =" Total : "+ " $" + (prod_price * prod_qty).toFixed(2);
  let action = "getMultiplier";
  
  $.ajax({ 
    type: "GET",
    url: "./models/getproducts.php",
    data: {"prod_size": prod_size, "action": action},
    success: (data)=>{
      $('#prices'+ val).html(" Total : "+ " $" + (parseFloat(data)*parseFloat(price)* parseInt(prod_qty)).toFixed(2));
    }
    });
   
}
function clearOrder(val){
  document.getElementById('prodSize' + val).value = "";
  document.getElementById('list' + val).value = "";
  let price = document.getElementById('prices'+ val);
  price.innerHTML = 0 + " $" ;
  document.getElementById('list' + val).disabled =  true;
  document.getElementById('bott' + val).disabled =  true;
  document.getElementById('bott2' + val).disabled = true;
  document.getElementById('addCartBt' + val).disabled = true; 
}
function enabel(val){
  document.getElementById('list' + val).disabled = false;
  document.getElementById('bott' + val).disabled = false;
  document.getElementById('bott2' + val).disabled = false;

}
function enabelAdd(val){
  let prod_qty = document.getElementById('list' + val).value;
  if (prod_qty > 0 ){
    document.getElementById('addCartBt' + val).disabled = false;  
  }
  else {
    document.getElementById('addCartBt' + val).disabled = true;  
  }
}
///////////////////////////////////////////////////////////////////////////
let abouticons;

$("#about_page").on("click", (event)=>{
  let x = event.clientX, y = event.clientY, elementClick = document.elementFromPoint(x, y);
  $("#about_details").css({"left": event.clientX, "top": event.clientY});
  $("#about_details").animate({
    top: 0,
    left: 0,
    width: "100%",
    height: "100vh",
  }, 1000);

});

$("#contact_bage").on("click", (event)=>{
  let x = event.clientX, y = event.clientY, elementClick = document.elementFromPoint(x, y);
  $("#contact_details").css({"left": event.clientX, "top": event.clientY});
  $("#contact_details").animate({
    top: 0,
    left: 0,
    width: "100%",
    height: "100vh",
  }, 1000);

});

function test()
{
  $("#history_details").animate({
    top: 0,
    left: 0,
    width: "100%",
    height: "100vh",
  }, 1000);
  let userID = document.getElementById("userID").value;
  let action = "click";
  
  $.ajax({ 
    type: "GET",
    url: "./models/orderHistory.php",
    data: {"userID":userID , "action":action},
    success: (data)=>{
      $("#history_details").html(data);
    }
    });
}

function Close_about()
{
  $("#about_details").css({"width": "0%", "height": "0vh"});
  $("#contact_details").css({"width": "0%", "height": "0vh"});
  $("#history_details").css({"width": "0%", "height": "0vh"});
}