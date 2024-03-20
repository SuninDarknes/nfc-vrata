<?php



class MySQLDB

{



    var $host = 'localhost';

    var $username = 'studentadmin';

    var $password = 'password';

    var $database = 'zavrsni2024';



    protected $db;



    function __construct()

    {

        $this->connect();

        // $this->mysql_error("CONNECT", "connected");

    }



    function get_db()

    {

        if (!isset($this->db)) {

            $this->connect();

        }

        return $this->db;

    }



    function connect()

    {



        try {

            $dsn = "mysql:host={$this->host};dbname={$this->database}";

            $options = array(

                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                PDO::ATTR_EMULATE_PREPARES => false,

            );



            $this->db = new PDO($dsn, $this->username, $this->password, $options);

        } catch (PDOException $e) {

            $this->mysql_error("CONNECT", $e->getMessage()); // TODO nebre logati ako ne uspe konekcija

        }

    }



    function select($query, $params = array(), $key = "")

    {



        $db = $this->get_db();

        try {

            $stmt = $db->prepare($query);



            $stmt->execute($params);

            $result['row_count'] = $stmt->rowCount();

            $i = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($key !== "") {

                    $result['result'][$row[$key]] = $row;

                } else {

                    $result['result'][$i] = $row;

                    $i++;

                }

            }

        } catch (mysqli_sql_exception $e) {

            $this->mysql_error("SELECT", $e->getMessage());

        }



        return $result;

    }



    function select_one($query, $params = array())

    {



        $db = $this->get_db();

        try {

            $stmt = $db->prepare($query);

            $stmt->execute($params);



            $result['row_count'] = $stmt->rowCount();

            $result['result'] = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (mysqli_sql_exception $e) {

            $this->mysql_error("SELECT ONE", $e->getMessage());

        }



        return $result;

    }



    function delete($query, $params = array())

    {

        $db = $this->get_db();

        try {

            $stmt = $db->prepare($query);

            $stmt->execute($params);

        } catch (mysqli_sql_exception $e) {

            $this->mysql_error("DELETE", $e->getMessage());

        } // TODO dodati delete success i id

    }



    function insert($query, $params = array())

    {

        $db = $this->get_db();

        try {

            $stmt = $db->prepare($query);

            $stmt->execute($params);

            $insert_id = $db->lastInsertId();

        } catch (mysqli_sql_exception $e) {

            $this->mysql_error("INSERT", $e->getMessage());

            return array('success' => false, 'insert_id' => -1);

        }



        return array('success' => true, 'insert_id' => $insert_id);

    }



    function update($query, $params = array())

    {

        $db = $this->get_db();

        try {

            $stmt = $db->prepare($query);

            $stmt->execute($params);

        } catch (mysqli_sql_exception $e) {

            $this->mysql_error("UPDATE", $e->getMessage());

        }

    }



    function mysql_error($query, $msg)

    {

        $db = $this->get_db();

        $stmt = $db->prepare("INSERT INTO vl_mysql_errors (query, message) VALUES (?, ?)");

        $stmt->execute(array($query, $msg));

    }

}