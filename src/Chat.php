<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require_once('asset/setup/DBconnect.php');
require_once('chatroom/change_user_status.php');


#region main code

//TODO: 目前只有一個clients list，之後可以根據用途創建不同list。
$GLOBALS['clients']= array();


/* TEST */ 
var_dump($GLOBALS['clients']);

#endregion


#region functions

class Chat implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $client, $message) {
        
        // message format : '{"action":"", "uid":""}'

        $clients = &$GLOBALS['clients'];
        $message = json_decode($message);
        $action = $message->action;
        $uid = $message->uid;
                        
        switch($message->action)
        {
            /*
                action type : connect
            */
            case "connect":
                
                if(!isset($clients[$uid]))
                {
                    $clients[$uid]=[];
                }

                array_push($clients[$uid],$client->resourceId);
                change_user_status('online', $uid);

                /* TEST */ 
                print_r($clients);

                foreach ($this->clients as $client) {
                    
                        // The sender is not the receiver, send to each client connected
                        $data = '{"action":"status", "user":"'.$uid.'"}';
                        $client->send($data);                
                    
                }
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
                       
                        $data = '{"action":"status", "user":"'.$uid.'"}';
                        $client->send($data);                             }
                }

                /* TEST */ 
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

#endregion

?>
