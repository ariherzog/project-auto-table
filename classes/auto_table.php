<?php
    require_once("database.php");

    class auto_table {

        private $database  =  "";
        private $table     =  "";
        private $fields    =  "";
        private $buttons   =  "";
        private $ondition  =  "";
        private $orderby   =  "";
        private $id_delete =  "";
        private $add       =  "";
        
        function urlParam($param) {
            return isset($_GET[$param]) ? $_GET[$param] : "";
        }
    
        function __construct($database, $table, $fields, $buttons, $condition, $orderby, $id_delete, $add) {

            $this->database  = $database;
            $this->table     = $table;
            $this->fields    = $fields;
            $this->buttons   = $buttons;
            $this->condition = $condition;
            $this->orderby   = $orderby;
            $this->id_delete = $id_delete;
            $this->add       = $add;

            // router
            $db      = $this->urlParam('database');
            $tbl     = $this->urlParam('table');
            $order   = $this->urlParam('order');
            $delete  = $this->urlParam('delete');
            $id      = $this->urlParam('id');
            $add     = $this->urlParam('add');
            $search  = $this->urlParam('search');
            
            // check if the table is the correct table for changes
            if ($database == $db && $table == $tbl) {
                $action  = $this->urlParam('action');
                switch ($action) {
                    case "update" : $this->updateField(); break;
                    case "delete" : $id_delete = $id;     break;
                    case "add"    : $add = $add ;         break;
                    case "order"  : $orderby = $orderby;  break;
                    case "search" : $condition =  $this->getCondition($search, $id); break;
                }
            }
            // show the table
            $this->showTable($database, $table, $fields, $buttons, $condition, $orderby, $id_delete, $add);
        }

        function getCondition($search, $id) {
            if ($id == ""){
                $condition = "concat(" . implode(',', array_keys($this->fields)) . ") LIKE '%$search%' ";
            }else{
                $condition = "concat(" . implode(', \' \', ', array_keys($this->fields)) . ") LIKE '%$search%' AND $this->condition";
            }
            return $condition;
        }
         // funcation for update value        
        function updateField() {
            DATABASE::INIT_TABLE($this->database, $this->table);
            $fname = $this->urlParam('fname');
            $fvalue =  $this->urlParam('fvalue');
            $id = $this->urlParam('id');
            DATABASE::UPDATE($this->table, [$fname], [$fvalue], $id);
        }

        function showTable($database, $table, $fields, $buttons, $condition, $orderby, $id_delete, $add) {
            DATABASE::INIT_TABLE($database, $table);

            // funcation for delete
            if($id_delete != "") DATABASE::DELETE($table, $id_delete);

            // funcation for adding
            if($add != ""){
                $values = array();
                foreach($fields as $fi){
                    $values[] =  "";
                }
                DATABASE::INSERT($table, array_keys($fields), $values,) ;
            }
            // show the table or show the search result
            $condition != "" ?  $rows = DATABASE::SEARCH($condition) : $rows = DATABASE::GET_TABLE($orderby);
            
                
          
          
         
            // create table headers
            $values = array_values($fields);
            $keys = array_keys($fields);
            $ex_action = $this->urlParam('ex_action');
            $id = $this->urlParam('id');

        
            //buttons by option- search
            if(in_array("search",$buttons)){
                echo "<div style='display: inline-block; margin-right:8px;'><form>
                    <input type='text'   name='search'  placeholder='הזן שם או ערך לחיפוש' style='border:2px solid black '>
                    <input type='hidden' name='action' value='search'>
                    <input type='hidden' name='table' value='$this->table'>
                    <input type='hidden' name='database' value='$this->database'>";
                //Search the other tables
                if(isset($_GET["ex_action"])) echo "<input type='hidden' name='ex_action' value='$ex_action'>";
                echo "</form></div>";
            }
            //buttons by option- adding new customer
            if(in_array("add",$buttons)) echo "<div style='display: inline-block;'><a href='?action=add&add=1'>צור לקוח חדש<img src='assets/user.png'></a></div>";
            
            // create the main table
            echo"<table border='1'>";

            // create the main table title bar(by 3 commands)
            echo"<tr><br>";
            // 1) create the columns values
            $i = 0;
            foreach ($values as $value ) echo "<th><a href='?action=order&orderby=$keys[$i]'>$value[name]</a></th>" ; $i++;
            // 2) create the delete optoin
            if (array_key_exists('delete', $buttons)) echo "<th>" . $buttons['delete']['name'] . "</th>";
            // 3) create the links for another table
            if (array_key_exists('custom', $buttons)){ 
                foreach($buttons['custom'] as $button ){
                    echo "<th>" . $button['name'] . "</th>";
                }
            }
            echo "</tr>";

            //cteate the table rows
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    if (in_array($key, $keys)) {
                        isset($row['id'])  ? $id = $row['id'] : "";

                        // create rows style or select default 
                        isset($fields[$key]['style']) ? $style = $fields[$key]['style'] : $style = "'width:130px'";
                        echo "<td style='$style'>";

                        // rows wite no update option
                        if(!in_array("update", $buttons)){
                            echo "$value</td>";
                        // rows wite update option  
                        }else{ 
                            echo "<form style='margin-bottom:0px'>
                            <input type='hidden' name='action'   value='update'>
                            <input type='hidden' name='database' value='$database'>
                            <input type='hidden' name='table'    value='$table'>
                            <input type='hidden' name='id'       value='$id'>
                            <input type='hidden' name='fname'    value='$key'>
                            <input type='text'   name='fvalue'   value='$value' style='width:100%'";
                            if(isset($fields[$key]['send'])){
                                echo "onchange='this.form.submit()'";
                            } 
                            foreach($fields[$key] as $rows => $row){
                                echo "$rows='$row'";
                            }
                            echo ">";
                        }
                        echo "</form></td>";
                        }
                    }
                    // create the rows for the delete optoin
                    if(array_key_exists("delete", $buttons)) echo "<td><a href='?action=delete&id=$id'><img src='assets/delete.png'></a></td>";
        
                    // create the rows wite the links for another table
                    if (array_key_exists('custom', $buttons)){ 
                        $url = "?database=$this->database&table=$this->table&id=$id&";
                        foreach($buttons['custom'] as $button ){
                            $url .= $button['url'];
                            echo "<td><a href='$url'><img src='" . $button['icon'] . "'></a></td>";
                        }
                    }
                }
                echo "</table>";
            }
        }

?>