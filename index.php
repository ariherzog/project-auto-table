<html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>אדמין</title>
    <style>
        .pimage {
            width: 40px;
            height: 40px;
        }
    </style>

</head>
<body>
    <?php
        require_once("classes/auto_table.php");

        function customers(){
            $customers = new auto_table(
                // database
                "crm",  
                // table
                "customers", 
                // table columns 
                [
                "first"  => ["name"=>"שם פרטי",  "style" => "width:100px;", "type"=>"text", "placeholder"=>"הזן שם פרטי" ],
                "last"    => ["name"=>"שם משפחה", "style" => "width:100px; border-color:green", "type"=>"text", "placeholder"=>"הזן שם משפחה"],
                "phone"   => ["name"=>"טלפון",   "style" => "width:100px;", "type"=>"tel","placeholder"=>"הזן טלפון"],
                "email"   => ["name"=>"איימיל",  "style" => "width:160px;", "type"=>"email", "placeholder"=>"הזן כתובת אימייל"],
                ], 
                // buttons
                [
                "add", 
                "delete"=> ["name"=> "מחק"],
                "update",
                "search",
                "custom"=> [
                    ["name"=>"פירוט לקוח", "icon"=>"assets/diagram.png", "title"=>"Show Additional values", "url"=>"ex_action=orders_for_client"],
                    ["name"=>"פירוט מוצרים", "icon"=>"assets/diagram.png", "title"=>"Show Additional values", "url"=>"ex_action=products_in_order"]
                    ]
                ], 
                // condition
                "", 
                // orderby 
                "first", 
                // id_delete
                "", 
                // add
                ""
            );
        }
        
        function products(){
            $products = new auto_table(
                 // database
                "crm", 
                // table
                "products",  
                 // table columns 
                [
                    "sku"      => ["name"=>"מקט"],
                    "p_name"   => ["name"=>"שם מוצר"],
                    "p_desc"   => ["name"=>"תיאור מוצר"],
                    "p_image"  => ["name"=>"תמונת מוצר", "type"=>"image",  "src"=>["images/" , ".jpg"]],
                    "inventory"=> ["name"=>"מלאי זמין"],
                    "price"    => ["name"=>"מחיר"],
                ], 
                // buttons
                [ 
                    "update", 
                    "delete",
                    "search",
                    "custom"=>[["name"=>"עבור", "icon"=>"assets/diagram.png", "title"=>"Show Additional values", "url"=>"ex_action=show_customers"]
                ]], 
                // condition
                "", 
                // orderby 
                "p_name", 
                // id_delete
                "", 
                // add 
                ""
            );
        }
        function orders_for_client(){
            echo "<h1>in orders_for_client</h1>";
            $id = $_GET['id'];
            $orders_full = new auto_table(
                // database
                "crm",  
                // table
                "orders_full",  
                // table columns 
                [
                    "id"           => ["name" => "מספר הזמנה"],
                    "total_items"   => ["name"=>"סך פרטים "],
                    "total_price"   => ["name"=>"סך מחיר "],
                    "order_date"   => ["name"=>"תאריך הזמנה"],
                ], 
                // buttons
                [ 
                    "search",
                    "custom"=>[["name"=>"עבור", "icon"=>"assets/diagram.png", "title"=>"Show Additional values", "url"=>"ex_action=products_in_order"]]
                ], 
                // condition
                "customer_id = $id", 
                // orderby
                "order_date",  
                 // id_delete
                "",
                // add
                ""
            );
        }
        function products_in_order(){
            $id = $_GET['id'];
            $order_detail_full = new auto_table(
                // database
                "crm",  
                // table
                "order_detail_full",  
                // table columns
                [
                    "product_id" => ["name"=>"מספר מוצר"],
                    "sku"        => ["name"=>"מקט"],
                    "p_name"     => ["name"=>"שם מוצר "],
                    "quantity"   => ["name"=>"כמות"],
                    "price"      => ["name"=>"מחיר ליחידה"],
                    "total"      => ["name"=>"סך מחיר"],
                ], 
                // buttons
                ["search"], 
                // condition
                "order_id = $id", 
                // orderby 
                "product_id", 
                // id_delete
                "", 
                // add 
                ""
            );
        }
       
        function products_detail(){
            $id = $_GET['id'];
            $products_del = new auto_table(
                // database
                "crm",  
                // table
                "products",  
                // table columns
                [
                    "sku"      => ["name"=>"מקט"],
                    "p_name"   => ["name"=>"שם מוצר"],
                    "p_desc"   => ["name"=>"תיאור מוצר"],
                    "p_image"  => ["name"=>"תמונת מוצר", "type"=>"image",  "src"=>["images/" , ".jpg"]],
                    "inventory"=> ["name"=>"מלאי זמין"],
                    "price"    => ["name"=>"מחיר"],
                ], 
                // buttons
                [ 
                    "search",
                    "custom"=>[["name"=>"עבור", "icon"=>"assets/diagram.png", "title"=>"Show Additional values", "url"=>"ex_action=show_customers"]]
                ], 
                // condition
                "product_id=$id", 
                // orderby 
                "p_name", 
                 // id_delete
                "",
                // add 
                ""
            );
        }
        // router
        if(isset($_GET['ex_action'])){
            switch($_GET['ex_action']){
                case  "add_products"         : products() ; break;
                case  "show_customers"       : customers(); break;
                case  "orders_for_client"    : orders_for_client(); break;
                case  "products_in_order"    : products_in_order(); break;
                case  "products_detail"      : products_detail(); break;
            }
        } else {
            customers();
        }
    ?>   
</body>
</html>

