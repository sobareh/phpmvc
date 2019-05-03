<?php

class Database {
    //constant yg berisi data login ke database tujuan agar safety can be fun
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    //database handler dan statement untuk instansiasi db dan query
    private $dbh;
    private $stmt;


    public function __construct()
    {
        //data source name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }


    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    //binding type data yang masuk untuk menghindari sql injection
    public function bind($param, $value, $type = null)
    {
        if( is_null($type) ) {
            switch( true ) {
                case is_int($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) :
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value) :
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
                
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // eksekusi statement query
    public function execute()
    {
        $this->stmt->execute();
    }

    // menampilkan hasil pengambilan data berupa data keseluruhan
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // menampilkan hasil pengambilan data berupa data detail/tertentu sesuai dengan kondisi yang ditetapkan
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
}