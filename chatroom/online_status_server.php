<?php

// 设置错误报告
error_reporting(E_ALL);

// 允许脚本一直运行
set_time_limit(0);

// 缓冲输出
ob_implicit_flush();

if(isset($_SESSION['uid']))
{
    return false;
}
else
{
    $uid=$_SESSION['uid'];
}


$host = "localhost";
$port = 12345;

// 创建 TCP/IP Socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

// 绑定地址和端口
socket_bind($socket, $host, $port) or die("Could not bind to socket\n");

// 开始监听连接
socket_listen($socket) or die("Could not set up socket listener\n");

echo "Chat server listening on $host:$port\n";

$clients = array();

$sockets = array($socket);


while (true) {  
   

    $readSockets = $sockets;
    $writeSockets = $except = null;


    // 选择 socket，阻塞直到有活动连接
        socket_select($readSockets, $writeSockets, $except, null);

        foreach ($readSockets as $readSocket) {
            if ($readSocket == $socket) {
                // 有新连接
                $newSocket = socket_accept($socket);
                $sockets[$uid] =$newSocket;
                $clients[] = $newSocket;
                echo "new connection...";

            } else {
                // 读取客户端发送的数据
                $data = socket_read($readSocket, 1024);

                // 检查连接是否断开
                if ($data == false) {
                    $readSocket_index = array_search($readSocket, $clients);
                    $clients_index = array_search($readSocket, $sockets);
                    unset($clients[$readSocket_index]);
                    unset($sockets[$clients_index]);
                    socket_close($readSocket);
                    echo "Client disconnected\n";
                }
            }
        }
    }


// 关闭监听的 socket
socket_close($socket);
?>