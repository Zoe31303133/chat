<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require_once('asset/setup/DBconnect.php');




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
                    $clients[$uid]=[];
                }

                array_push($clients[$uid],$sessionID);

                print_r($clients);

                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        // The sender is not the receiver, send to each client connected
                        $client->send("使用者".$uid."已上線");
                    }
                }
                break;

        }


        
    }

    public function onClose(ConnectionInterface $conn) {

        // The connection is closed, remove it, as we can no longer send it messages

        $this->clients->detach($conn);

        $clients = &$GLOBALS['clients'];
        $closed_sessionID = $conn->resourceId;

        foreach($clients as $uid=>&$socket)
        {   
            foreach($socket as $index=>$sessionID)
            {
                if($sessionID==$closed_sessionID)
                {
                    unset($socket[$index]);
                }
            }

            
            if(empty($socket))
            {
                unset($clients[$uid]);
                change_user_status('offline', $uid);

                foreach ($this->clients as $client) {
                    if ($conn !== $client) {
                        // The sender is not the receiver, send to each client connected
                        $client->send("使用者".$uid."已下線");
                    }
                }

                print_r($clients);
                break;
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

    $sql = "update users set status = '$status' where id = 1 ;";
    $conn = connection();
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);

    mysqli_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}

?>
