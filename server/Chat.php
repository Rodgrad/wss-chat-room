<?php
namespace MyChat;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;


    public function __construct() {
        //create storage for objects
        $this->clients = new \SplObjectStorage; 
    }


    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection 
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }


    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        switch($data->command){
            case "client": $this->process_client($from, $data);
            case "message": $this->process_message($data, $msg);

        }
    }


    protected function process_client($from, $data){
        //Create group for clients
        if(isset($data->user_id) and isset($data->group)){
            foreach($this->clients as $client){
                if($client->resourceId == $from->resourceId){
                    $client->group = $data->group;
                }
            } 
        }
    }


    protected function process_message($data, $msg){
        //Send message only to client that are in specific group of client
        if(isset($data->user_id) and isset($data->group)){
            foreach ($this->clients as $client) {
                if ($client->group == $data->group) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }
            }
        }

    }


    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId}S   
        Las disconnected\n";
    }


    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}