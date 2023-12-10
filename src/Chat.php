<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

$GLOBALS['clients']= array();
var_dump($GLOBALS['clients']);

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $clients = &$GLOBALS['clients'];
        $msg = json_decode($msg);
        $action = $msg->action;
        $uid = $msg->uid;
        $sessionID = $from->resourceId;
                
        switch($msg->action)
        {

            case "connect":
                
                if(!isset($clients[$uid]))
                {
                    echo "set";
                    $clients[$uid]=[];
                }

                array_push($clients[$uid],$sessionID);

                print_r($clients);
                break;

        }


        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send("");
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {

        // The connection is closed, remove it, as we can no longer send it messages

        $this->clients->detach($conn);

        $clients = &$GLOBALS['clients'];
        $sessionID = $conn->resourceId;

        foreach($clients as $uid=>&$socket)
        {   
            foreach($socket as &$socket_session)
            {
                $index = array_search($sessionID,$socket);
                echo ("index=".$index).".";
                if($index>=0)
                {
                    unset($socket[$index]); 
                    
                    if(empty($socket))
                    {unset($clients[$uid]);}

                    
                }
            }

        }
        
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}


function change_user_status($status, $uid){

    $sql = "update users set status = '$status' where id = $uid ;";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);

    mysqli_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}

?>
