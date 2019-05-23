<?php
namespace Harps\Database;

class Connection
{
    public $current_connection_name;
    public $connection;

    /**
     * Initializing optionals Database parameters
     * @param string $connexion_name Optional database connexion name, configure the connections in the parameters configuration
     */
    public function __construct($connection_name = DB_CONNEXION_DEFAULT)
    {
        return $this->open($connection_name);
    }

    /**
     * Undocumented function
     *
     * @param string $connection_name Optional database connexion name, configure the connections in the parameters configuration
     */
    public function open($connection_name = DB_CONNEXION_DEFAULT)
    {
        $db = DB_CONNEXIONS[$connection_name];

        // Generate the PDO connexion's string
        $this->connection = new \PDO($db["driver"] . ":host=" . $db["host"] . (isset($db["port"]) && $db["port"] != "" ? ":" . $db["port"] : "") . ";dbname=" . $db["name"], $db["user"], $db["pass"]);
        $this->current_connection_name = $connection_name;

        return $this;
    }
}
