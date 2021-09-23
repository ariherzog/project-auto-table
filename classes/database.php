<?php
    class DATABASE {

        private static $server   = "localhost";
        private static $username = "root";
        private static $password = "";
        public  static $database = "";
        public  static $table    = "";

        private static function connectSQL() {
            $mysqli = new mysqli(self::$server, self::$username, self::$password, self::$database);
            if ($mysqli -> connect_errno) {
                echo _DB_CONNECTION_FAILED;
                exit();
            } else {
                return $mysqli;
            }
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
            $table = self::$table;
            if ($order != "") {
                return self::QUERY("SELECT * FROM $table ORDER BY $order");
            } else {
                return self::QUERY("SELECT * FROM $table");
            }
        }

        public static function INSERT($table, $fields, $values) {
            $sql = "INSERT INTO $table (" . implode(',',$fields) . ") VALUES ('" . implode("','",$values) . "')";
            $conn = self::connectSQL();
            $conn->query($sql);
            if(!$conn->query($sql)) {
                echo _INSERT_FAILED;
                exit();
            }else{
                $id = $conn->insert_id;
                $conn->close();
                echo _SUCCESS;
                return $id;
            }
        }

        public static function UPDATE($table, $fields, $values, $id) {
            $sql = "UPDATE $table SET ";
            for($i=0; $i<count($fields); $i++) $sql .= "$fields[$i]='$values[$i]',";
            $sql = trim($sql,',');
            $sql .= " WHERE id=$id";
            $conn = self::connectSQL();
            $conn->query($sql);
            if(!$conn->query($sql)) {
                echo _UPDATE_FAILED;
                exit();
            } else {
                $conn->close();
                echo _SUCCESS;
            }
        }

        public static function DELETE($table, $id) {
            $conn = self::connectSQL();
            $sql = "DELETE FROM $table WHERE id=$id";
            echo "$sql<hr>";
            $conn->query($sql);
            if(!$conn->query($sql)) {
                echo _DELETE_FAILED;
                exit();
            } else {
                $conn->close();
                echo _SUCCESS;
            }
        }
    }

    const _SUCCESS  = "הפעולה בוצעה בהצלחה\n";
    const _CONNECTION_FAILED = '<script>alert("שלום משתמש! \nאין חיבור לרשת האינטרנט")</script>';
    const _DB_CONNECTION_FAILED = '<script>alert("שלום משתמש!\n אירעה שגיאה בחיבור למסד הנתונים")</script>';
    const _UPDATE_FAILED = '<script>alert("שלום משתמש!\nחלה שגיאה- עדכון הנתונים לא בוצע")</script>';
    const _DELETE_FAILED = '<script>alert("שלום משתמש! \nחלה שגיאה- מחיקת הנתונים לא בוצעה")</script>';
    const _INSERT_FAILED = '<script>alert("שלום משתמש! \nחלה שגיאה- יצירת שורה חדשה נכשלה")</script>';

?>