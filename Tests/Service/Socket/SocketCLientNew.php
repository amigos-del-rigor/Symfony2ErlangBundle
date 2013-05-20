<?php

define("CONNECTED", true);
define("DISCONNECTED", false);

/**
 * Socket class
 *
 * This class can be used to connect to external sockets and communicate with the server
 *
 * @author Tom Reitsma <treitsma@rse.nl>
 * @version 0.6
 */
Class SocketCLientNew
{
    /**
     * @var singleton $instance
     * @desc Singleton var
     */
    private static $instance;

    /**
     * @var resource $connection
     * @desc Connection resource
     */
    private $connection = null;

    /**
     * @var string $connectionState
     * @desc
     */
    private $connectionState = DISCONNECTED;

    /**
     * @var int $defaultHost
     * @desc Default ip address to connect to
     */
    private $defaultHost = "127.0.0.1";

    /**
     * @var int $defaultPort
     * @desc Default port to connect to
     */
    private $defaultPort = 10034;

    /**
     * @var float $defaultTimeout
     * @desc Default timeout for connection to a server
     */
    private $defaultTimeout = 10;

    /**
     * @var bool $persistentConnection
     * @desc Determines wether to use a persistent socket connection or not
     */
    private $persistentConnection = true;

    // /**
    //  * Class constructor
    //  *
    //  * @return void
    //  * @access private
    //  */
    // private function __construct()
    // {

    // }

    /**
     * Singleton pattern. Returns the same instance to all callers
     *
     * @return Socket
     */
    public static function singleton()
    {
        if (self::$instance == null || ! self::$instance instanceof Socket)
        {
            self::$instance = new Socket();
        }
        return self::$instance;
    }

    /**
     * Connects to the socket with the given address and port
     *
     * @return void
     */
    public function connect($serverHost=false, $serverPort=false, $timeOut=false)
    {
        $socketFunction = $this->persistentConnection ? "pfsockopen" : "fsockopen";

        // Check if the function parameters are set.
        // If not, use the class defaults
        if($serverHost == false)
        {
            $serverHost = $this->defaultHost;
        }

        if($serverPort == false)
        {
            $serverPort = $this->defaultPort;
        }

        if($timeOut == false)
        {
            $timeOut = $this->defaultTimeout;
        }

        $connection = $socketFunction($serverHost, $serverPort, $errorNumber, $errorString, $timeOut);
        $this->connection = $connection;

        stream_set_blocking($this->connection, 0);

        if($connection == false)
        {
            $this->_throwError("Connecting to {$serverHost}:{$serverPort} failed.<br>Reason: {$errorString}");
        }

        $this->connectionState = CONNECTED;
    }

    /**
     * Disconnects from the server
     *
     * @return True on succes, false if the connection was already closed
     */
    public function disconnect()
    {
        if($this->validateConnection())
        {
            fclose($this->connection);
            $this->connectionState = DISCONNECTED;

            return true;
        }

        return false;
    }

    /**
     * Sends a command to the server
     *
     * @return string Server response
     */
    public function sendCmd($command)
    {
        if($this->validateConnection())
        {
            $command .= "\r\n";

            $result = fwrite($this->connection, $command, strlen($command));

            return $result;
        }

        $this->_throwError("Sending command \"{$command}\" failed.<br>Reason: Not connected");
    }

    /**
     * Gets the server response (not multilined)
     *
     * @return string Server response
     */
    public function getResponse()
    {
        if($this->validateConnection())
        {
            return fread($this->connection, 2048);
        }

        $this->_throwError("Receiving response from server failed.<br>Reason: Not connected");
    }

    /**
     * Gets a multilined response
     *
     * @return string Server response
     */
    public function getMultilinedResponse()
    {
        $data = '';
        while(($tmp = $this->readLine()) != '.')
        {
            if(substr($tmp, 0, 2) == '..')
            {
                $tmp = substr($tmp, 1);
            }
            $data .= $tmp."\r\n";
        }

        return substr($data, 0, -2);
    }

    /**
     * Reads an entire line
     *
     * @return string Server response
     */
    public function readLine()
    {
        $line = '';
        while (!feof($this->connection))
        {
            $line .= fgets($this->connection, 1024);
            if (strlen($line) >= 2 && (substr($line, -2) == "\r\n" || substr($line, -1) == "\n"))
            {
                return rtrim($line);
            }
        }
        return $line;
    }

    /**
     * Validates the connection state
     *
     * @return bool
     */
    private function validateConnection()
    {
        return (is_resource($this->connection) && ($this->connectionState != DISCONNECTED));
    }

    /**
     * Throws an error
     *
     * @return void
     */
    private function _throwError($errorMessage)
    {
        throw new Exception("Socket error: " . $errorMessage);
    }

    /**
     * If there still was a connection alive, disconnect it
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}


$SocketCLientNew = new SocketCLientNew();
$SocketCLientNew->connect();
$result = $SocketCLientNew->sendCmd('1 testComd');
var_dump($result);
$result = $SocketCLientNew->sendCmd('2 testComd');
var_dump($result);
$result = $SocketCLientNew->sendCmd('testComd');
$SocketCLientNew->disconnect();