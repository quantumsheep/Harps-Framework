<?php
namespace Harps\Utils;

class Database
{
    /**
     * Storage for database host
     * @var string
     */
    private static $db_host = DB_HOST_PORT;

    /**
     * Storage for database user
     * @var string
     */
    private static $db_user = DB_USER;

    /**
     * Storage for database pass
     * @var string
     */
    private static $db_pass = DB_PASS;

    /**
     * Storage for database name
     * @var string
     */
    private static $db_name = DB_NAME;

    /**
     * Initializing optionals Database parameters
     * @param string $db_host Optional database host
     * @param string $db_user Optional database username
     * @param string $db_pass Optional database password
     * @param string $db_name Optional database name
     */
    function __construct(string $db_host = DB_HOST_PORT, string $db_user = DB_USER, string $db_pass = DB_PASS, string $db_name = DB_NAME)
    {
        self::$db_host = $db_host;
        self::$db_user = $db_user;
        self::$db_pass = $db_pass;
        self::$db_name = $db_name;
    }

    /**
     * Get the mysqli connection to your database using properties in /Config/Parameters.php
     * @return \mysqli
     */
    public static function getConnection()
    {
        $mysqli = new \mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
        return $mysqli;
    }

    /**
     * Send a SQL query
     * @param \mysqli $conn The mysqli connection (use Database::getConnection() to get one)
     * @param string $command The SQL command to send
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the query's result
     * @throws \InvalidArgumentException
     * @throws \mysqli_sql_exception
     * @return mixed
     */
    public static function send(\mysqli $conn, string $command, bool $returnResult = true)
    {
        $backtrace = debug_backtrace()[0];

        $result = $conn->query($command);

        if (!$conn->error && $result->num_rows > 0 && $returnResult == true) {
            $result = $result->fetch_all();
        } elseif ($conn->error) {
            throw new \mysqli_sql_exception($conn->error);
        }

        return $result;
    }

    /**
     * Send a prepared SQL query
     * @param \mysqli $conn The mysqli connection (use Database::getConnection() to get one)
     * @param string $command The SQL prepared command to send
     * @param array|string $params Parameters to bind to the prepared statement
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the smtp object
     * @throws \InvalidArgumentException
     * @throws \mysqli_sql_exception
     * @return mixed
     */
    public static function send_prepared(\mysqli $conn, string $command, $params, bool $returnResult = true)
    {
        $backtrace = debug_backtrace()[0];

        $stmt = $conn->prepare($command);

        if ($stmt !== false) {
            if (is_array($params)) {
                $prm_val = "";
                foreach ($params as $prm) {
                    $prm_val .= self::GetParamType($prm);
                    if ($prm_val != false) {
                    } else {
                        throw new \InvalidArgumentException("Invalid parameter");
                    }
                }

                $params_bind = array();
                $params_bind[] = &$prm_val;

                $n = count($params);
                for ($i = 0; $i < $n; $i++) {
                    $params_bind[] = &$params[$i];
                }

                call_user_func_array(array($stmt, 'bind_param'), $params_bind);
            } else {
                $prm_val = self::GetParamType($params);
                if ($prm_val != false) {
                    $stmt->bind_param($prm_val, $params);
                } else {
                    throw new \InvalidArgumentException("Invalid parameter");
                }
            }

            $stmt->execute();

            if (!$stmt->error && $returnResult == true) {
                return ($stmt->get_result())->fetch_all(MYSQLI_NUM);
            } elseif ($stmt->error) {
                throw new \mysqli_sql_exception($stmt->error);
            }
        }

        return $stmt;
    }

    /**
     * Get the parameter type for stmt use
     * @param mixed $param
     * @return \boolean|string
     */
    private static function GetParamType($param)
    {
        if (is_string($param)) {
            return 's';
        } elseif (is_float($param) || is_double($param) || is_real($param)) {
            return 'd';
        } elseif (is_int($param) || is_integer($param) || is_long($param)) {
            return 'i';
        } else {
            return false;
        }
    }
}
