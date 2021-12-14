<?php

/**
 * Class DB
 */
class DB {

    private static $_instance = null;
    private $_pdo, 
            $_query,
            $_error = false,
            $_results,
            $_count = 0;

    /**
     * DB constructor.
     */
    private function __construct() {
        try {
//            $this->_pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));

        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Create Database Object
     * @return DB|null
     */
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    /**
     * Run command at Database
     * @param $sql
     * @param array $params
     * @return $this
     */
    public function query($sql, $params = array()) {
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {

            $x = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    /**
     * Make query and Run command at database
     * @param $action: action for database, include insert, select , update etc
     * @param $table: table name
     * @param array $where: array include target , operator , value
     * @return $this|bool\
     */
    public function action_org($action, $table, $where = array()) {
        if(count($where) === 3) {

            $operators = array('=', '>', '<', '>=', '<=');
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function action($action, $table, $where = array(),$order = null) {
        if(count($where) === 3) {

            $operators = array('=', '>', '<', '>=', '<=');
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)) {
                if($order){

                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$order} ASC"; // or DESC

                }else{
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                }

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            } else if ( $operator == 'LIKE' ) {
                if($order){

                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$order} ASC"; // or DESC

                }else{
                    $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

                }

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    public function actionOrderBy($action, $table, $where = array(),$order) {
        if(count($where) === 3) {

            $operators = array('=', '>', '<', '>=', '<=');
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? ORDER BY {$order} DESC";

                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

    /**
     * Get data following value at table
     * @param $table: table name
     * @param $where: array include target , operator , value
     * @return $this|bool|\bool\
     */
    public function get_org($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }

    public function get($table, $where,$order = null) {
        return $this->action('SELECT *', $table, $where,$order);
    }

    public function getOrderBy($table, $where,$order) {
        return $this->actionOrderBy('SELECT *', $table, $where,$order);
    }

    /**
     * Delete data at table
     * @param $table: table name
     * @param $where: array include target , operator , value
     * @return $this|bool|\bool\
     */
    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }

    /**
     * Insert data at table
     * @param $table: table name
     * @param array $fields: array include target = value
     * @return bool
     */
    public function insert($table, $fields = array()) {

        if(count($fields)) {
            $keys = array_keys($fields);
            $values = null;
            $x = 1;
            foreach ($fields as $field) {
                $values .= "?";
                if($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO $table (`".implode('`, `', $keys)."`) VALUES ({$values})";

            if(!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Update date at table
     * @param $table: table name
     * @param $id: The id for updating data
     * @param $fields: array include target = value
     * @return bool
     */
    public function update($table, $id, $fields) {

            $set = '';
            $x = 1;

            foreach($fields as $name => $value) {
                $set .= "`{$name}` = ?";
                if($x < count($fields)) {
                    $set .= ', ';
                }
                $x++;
            }
            // die($set);

            $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

            if(!$this->query($sql, $fields)->error()) {
                return true;
            }

        return false;
    }


    public function updateContent($table,$replaceField,$replaceFrom,$replaceTo) {

        // Create connection
        $conn = new mysqli(Config::get('mysql/host'),  Config::get('mysql/username'), Config::get('mysql/password'), Config::get('mysql/db'));

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        /*UTF-8 format*/
        $conn->set_charset("utf8");

        $sql = "UPDATE $table SET $replaceField = REPLACE($replaceField, '$replaceFrom', '$replaceTo');";

        if ($conn->query($sql) === TRUE) {

            $conn -> close();
            return true;
        } else {

            $conn -> close();
            return false;
        }


        return false;
    }


    public function updateNameAtPlants($table, $id, $fields) {

        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "`{$name}` = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        // die($set);

        $sql = "UPDATE {$table} SET {$set} WHERE genetic = {$id}";

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    /**
     * Get result after operate
     * @return mixed
     */
    public function results() {
        return $this->_results;
    }

    /**
     * Get first array of result data
     * @return mixed
     */
    public function first() {
        return $this->results()[0];
    }

    /**
     * Error
     * @return bool
     */
    public function error() {
        return $this->_error;
    }

    /**
     * Get count of data after operate
     * @return int
     */
    public function count() {
        return $this->_count;
    }       

}