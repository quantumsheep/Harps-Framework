<?php
namespace Harps\Database;

use Harps\Database\Connection;
use Harps\Database\Exceptions\QueryException;

class Query
{
    public $conn;

    /**
     * Initializing optionals Database parameters
     * @param Connection|\PDO $conn Optional database connexion name, configure the databases in the parameters configuration
     */
    public function __construct($conn)
    {
        $this->define_conn_args($db_connexion_name);
    }

    private function define_conn_args(string $db_connexion_name)
    {
        $this::$conn = DB_CONNEXIONS[$db_connexion_name];
    }

    /**
     * Send a SQL query
     * @param Connection|\PDO $conn The PDO connection (use Database::getConnection() to get one)
     * @param string $command The SQL command to send
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the query's result
     * @throws QueryException
     * @return mixed
     */
    public static function send($conn, string $command, bool $returnResult = true)
    {
        if($conn instanceof Connection) {
            $conn = $conn->connection;
        }

        $sth = $conn->prepare($command);
        $sth->execute();

        if ($sth->errorCode() == 0 && $sth->rowCount() > 0 && $returnResult == true) {
            return $sth->fetchAll();
        } elseif ($sth->errorCode() != 0) {
            throw new QueryException($sth->errorInfo()[2]);
        }

        return $result;
    }

    /**
     * Send a prepared SQL query
     * @param Connection|\PDO $conn The PDO connection (use Database::getConnection() to get one)
     * @param string $command The SQL prepared command to send
     * @param array|string $params Parameters to bind to the prepared statement
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the smtp object
     * @throws \InvalidArgumentException
     * @throws QueryException
     * @return mixed
     */
    public static function send_prepared($conn, string $command, $params, bool $returnResult = true)
    {
        if($conn instanceof Connection) {
            $conn = $conn->connection;
        }

        $sth = $conn->prepare($command);

        if ($sth !== false) {
            if (is_array($params)) {
                $n_prms = count($params);

                for ($i = 0; $n_prms > $i; $i++) {
                    $prm_val = self::get_param_type($params[$i]);

                    if ($prm_val != false) {
                        $sth->bindParam($i + 1, $params[$i], $prm_val);
                    } else {
                        throw new \InvalidArgumentException("Invalid parameter");
                    }
                }
            } else {
                $prm_val = self::get_param_type($params);
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
                throw new \QueryException($sth->errorInfo()[2]);
            }
        }

        return $sth;
    }

    /**
     * Get the parameter type for stmt use
     * @param mixed $param
     * @return \boolean|string
     */
    private static function get_param_type($param)
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
