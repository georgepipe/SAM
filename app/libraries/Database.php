<?php

/**
 * PDO Database Class
 * Connect to database
 * Create prepared statements
 * Bind Values
 * Return rows and results
 * 
 * 
 */
 class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASS;
    private $dbname = DB_NAME;

    private $dbhandler;
    private $stmt;
    private $error;
    /**
     * Sets the $dsn and $options for database connections and creates a PDO instance
     */
    public function __construct() {
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            //more elegant way to handle errors
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
        );
        
        try{
            $this->dbhandler = new PDO($dsn, $this->user, $this->password, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Prepare a statement for query
     * @param mixed $sql
     * @return void
     */
    public function query($sql){
        $this->stmt = $this->dbhandler->prepare($sql);
    }

    /**
     * Binds values for database queries
     * @param mixed $param
     * @param mixed $value
     * @param mixed $type
     * @return void
     */
    public function bind($param, $value, $type = null){
        //check type of param passed
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Executes a prepared statment that has bound values
     * @return bool or last index
     */
    public function execute(){
        // echo "\nPDO::errorInfo():\n<PRE>";
        // print_r($this);
        // return $this->stmt->execute();

        $exe = $this->stmt->execute();
        if ($exe) {
            if($this->dbhandler->lastInsertId()>0) {
                return $this->dbhandler->lastInsertId();
            } else {return $exe;}
        } else {return $exe;}

    }

    /**
     * Return results from sql query as an array of objects
     * @return mixed
     */
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Returns results from an sql query as a single row
     * @return mixed
     */
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    //get row count
    /**
     * Returns a count of all the rows which match the sql query
     * @return mixed
     */
    public function rowCount(){
        return $this->stmt->rowCount();
    }
 }
