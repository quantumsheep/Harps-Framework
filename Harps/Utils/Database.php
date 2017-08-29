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
    function __construct(string $db_host = DB_HOST_PORT, string $db_user = DB_USER, string $db_pass = DB_PASS, string $db_name = DB_NAME) {
        self::$db_host = $db_host;
        self::$db_user = $db_user;
        self::$db_pass = $db_pass;
        self::$db_name = $db_name;
    }

    /**
     * Get the mysqli connection to your database using properties in /Config/Parameters.php
     * @return \mysqli
     */
    public static function getConnection() {
        $mysqli = new \mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
        return $mysqli;
    }

    /**
     * Send secure SQL queries with prepared statements (or without)
     * @param \mysqli $conn The mysqli connection (use Database::getConnection() to get one)
     * @param string $prepared_command The SQL Command to send (use prepare statement with '?' mark) if you need it
     * @param bool $returnResult If true the function will return the result of the query, if false it will return the query's result
     * @param mixed $params Parameters to bind if you use prepared statement, leave null if don't using it
     * @throws \InvalidArgumentException
     * @throws \mysqli_sql_exception
     * @return mixed
     */
    public static function Send_Request(\mysqli $conn, string $command, bool $returnResult = true, $params = null) {
        $backtrace = debug_backtrace()[0];

        if(isset($params)) {
            $stmt = $conn->prepare($command);

            if($stmt !== false) {
                if(is_array($params)) {
                    foreach($params as $prm) {
                        $prm_val = self::GetParamType($prm);
                        if($prm_val != false) {
                            $stmt->bind_param($prm_val, $prm);
                        } else {
                            throw new \InvalidArgumentException("Invalid parameter" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                        }
                    }
                } else {
                    $prm_val = self::GetParamType($params);
                    if($prm_val != false) {
                        $stmt->bind_param($prm_val, $params);
                    } else {
                        throw new \InvalidArgumentException("Invalid parameter" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                    }
                }

                $stmt->execute();

                if(!$stmt->error && $returnResult == true) {
                    return ($stmt->get_result())->fetch_all(MYSQLI_NUM);
                } else if($stmt->error) {
                    throw new \mysqli_sql_exception($stmt->error . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                }
            }

            return $stmt;
        } else {
            $result = $conn->query($command);

            if(!$conn->error && $result->num_rows > 0 && $returnResult == true) {
                $result = $result->fetch_all();
            } else if($conn->error) {
                throw new \mysqli_sql_exception($conn->error . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
            }

            return $result;
        }
    }

    /**
     * Get the parameter type for stmt use
     * @param mixed $param
     * @return \boolean|string
     */
    private static function GetParamType($param) {
        if(is_string($param)) {
            return 's';
        }
        else if (is_float($param) || is_double($param) || is_real($param)) {
            return 'd';
        }
        else if(is_int($param) || is_integer($param) || is_long($param)) {
            return 'i';
        }
        else {
            return false;
        }
    }
}