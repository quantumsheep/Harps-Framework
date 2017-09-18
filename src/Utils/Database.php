<?php
namespace Harps\Utils;

class Database
{
    private static $db_string;

    /**
     * Initializing optionals Database parameters
     * @param string $db_connexion_name Optional database connexion name, configure the databases in the parameters configuration
     */
    public function __construct(string $db_connexion_name = DB_CONNEXION_DEFAULT)
    {
        $this->define_db_args($db_connexion_name);
    }

    /**
     * Get the PDO connection to your database using properties in /Config/Parameters.php
     * @return \PDO
     */
    public static function getConnection()
    {
        if(!empty(self::$db_string)) {
            $db = self::$db_string;
        } else {
            $db = DB_CONNEXIONS[DB_CONNEXION_DEFAULT];
        }

        // Generate the PDO connexion's string
        return (new \PDO($db["driver"].":host=".$db["host"].(isset($db["port"]) && $db["port"] != "" ? ":" . $db["port"] : "").";dbname=".$db["name"], $db["user"], $db["pass"]));
    }

    private function define_db_args(string $db_connexion_name)
    {
        $this::$db_string = DB_CONNEXIONS[$db_connexion_name];
    }

    /**
     * Send a SQL query
     * @param \PDO $conn The PDO connection (use Database::getConnection() to get one)
     * @param string $command The SQL command to send
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the query's result
     * @throws \InvalidArgumentException
     * @throws \mysqli_sql_exception
     * @return mixed
     */
    public static function send(\PDO $conn, string $command, bool $returnResult = true)
    {
        $sth = $conn->prepare($command);
        $sth->execute();

        if ($sth->errorCode() == 0 && $sth->rowCount() > 0 && $returnResult == true) {
            return $sth->fetchAll();
        } elseif ($sth->errorCode() != 0) {
            throw new \PDOException($sth->errorInfo()[2]);
        }

        return $result;
    }

    /**
     * Send a prepared SQL query
     * @param \PDO $conn The PDO connection (use Database::getConnection() to get one)
     * @param string $command The SQL prepared command to send
     * @param array|string $params Parameters to bind to the prepared statement
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the smtp object
     * @throws \InvalidArgumentException
     * @throws \mysqli_sql_exception
     * @return mixed
     */
    public static function send_prepared(\PDO $conn, string $command, $params, bool $returnResult = true)
    {
        $sth = $conn->prepare($command);

        if ($sth !== false) {
            if (is_array($params)) {
                $n_prms = count($params);

                for ($i = 0; $n_prms > $i; $i++) {
                    $prm_val = self::GetParamType($params[$i]);

                    if ($prm_val != false) {
                        $sth->bindParam($i + 1, $params[$i], $prm_val);
                    } else {
                        throw new \InvalidArgumentException("Invalid parameter");
                    }
                }
            } else {
                $prm_val = self::GetParamType($params);
                if ($prm_val != false) {
                    $sth->bindParam(1, $params, $prm_val);
                } else {
                    throw new \InvalidArgumentException("Invalid parameter");
                }
            }

            $sth->execute();

            if ($sth->errorCode() == 0 && $sth->rowCount() > 0 && $returnResult == true) {
                return $sth->fetchAll();
            } elseif ($sth->errorCode() != 0) {
                throw new \PDOException($sth->errorInfo()[2]);
            }
        }

        return $sth;
    }

    /**
     * Get the parameter type for stmt use
     * @param mixed $param
     * @return \boolean|string
     */
    private static function GetParamType($param)
    {
        if (is_string($param) || is_float($param) || is_double($param) || is_real($param)) {
            return \PDO::PARAM_STR;
        } elseif (is_int($param) || is_integer($param) || is_long($param)) {
            return \PDO::PARAM_INT;
        } else if(is_bool($param)) {
            return \PDO::PARAM_BOOL;
        } else {
            return false;
        }
    }
}
