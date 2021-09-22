<?php
    class DATABASE {

        private static $server   = "localhost";
        private static $username = "root";
        private static $password = "";
        public  static $database = "";
        public  static $table    = "";

        private static function connectSQL() {
            return new mysqli(self::$server, self::$username, self::$password, self::$database);
        }

        public static function INIT_TABLE($database, $table) {
            self::$database = $database;
            self::$table = $table;
        }

        public static function QUERY($sql) {
            echo "$sql<hr>";
            $conn = self::connectSQL();
            $results = $conn->query($sql);
            $rows = array();
            while($row = $results->fetch_assoc()) $rows[] = $row;
            $conn->close();
            return $rows;
        }

        public static function SEARCH($condition) {
            $table = self::$table;
            return self::QUERY("SELECT * FROM $table WHERE $condition");
        } 

        public static function GET_TABLE($order) {
        // echo "in GET_TABLE, table=" . self::$table ."<br>";
            $table = self::$table;
            if ($order != "") {
                return self::QUERY("SELECT * FROM $table ORDER BY $order");
            } else {
                return self::QUERY("SELECT * FROM $table");
            }
        }

        public static function INSERT($table, $fields, $values) {
            $sql = "INSERT INTO $table (" . implode(',',$fields) . ") VALUES ('" . implode("','",$values) . "')";
            echo $sql;
            $conn = self::connectSQL();
            $conn->query($sql);
            $id = $conn->insert_id;
            $conn->close();
            return $id;
        }

        public static function UPDATE($table, $fields, $values, $id) {
            $sql = "UPDATE $table SET ";
            for($i=0; $i<count($fields); $i++) $sql .= "$fields[$i]='$values[$i]',";
            $sql = trim($sql,',');
            $sql .= " WHERE id=$id";
            //echo $sql;
            $conn = self::connectSQL();
            $conn->query($sql);
            $conn->close();
        }

        public static function DELETE($table, $id) {
            $conn = self::connectSQL();
            $sql = "DELETE FROM $table WHERE id=$id";
            echo "$sql<hr>";
            $conn->query($sql);
            $conn->close();
        }

        public static function COMMAND($sql) {
            $conn = self::connectSQL();
            $conn->query($sql);
            $conn->close();
        }

        public static function web_print_r($arr) {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        }
    }
?>