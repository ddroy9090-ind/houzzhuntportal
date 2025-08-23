<?php
// Simple WebSocket chat server without external dependencies
$host = '0.0.0.0';
$port = 8080;
$null = NULL;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, $host, $port);
socket_listen($socket);

$clients = [];

while (true) {
    $changed = $clients;
    $changed[] = $socket;
    socket_select($changed, $null, $null, 0, 10);

    if (in_array($socket, $changed)) {
        $client = socket_accept($socket);
        $clients[] = $client;
        $header = socket_read($client, 1024);
        perform_handshake($header, $client, $host, $port);
        $index = array_search($socket, $changed);
        unset($changed[$index]);
    }

    foreach ($changed as $client) {
        while (@socket_recv($client, $buf, 2048, 0) >= 1) {
            $data = unmask($buf);
            foreach ($clients as $send_client) {
                @socket_write($send_client, mask($data), strlen(mask($data)));
            }
            break 2;
        }
        $buf = @socket_read($client, 1024, PHP_BINARY_READ);
        if ($buf === false) {
            $index = array_search($client, $clients);
            unset($clients[$index]);
        }
    }
}

function perform_handshake($receved_header, $client_conn, $host, $port) {
    $headers = [];
    $lines = preg_split("/\r\n/", $receved_header);
    foreach ($lines as $line) {
        $line = chop($line);
        if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
            $headers[$matches[1]] = $matches[2];
        }
    }
    $secKey = $headers['Sec-WebSocket-Key'] ?? '';
    $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
    $upgrade  = "HTTP/1.1 101 Switching Protocols\r\n" .
                "Upgrade: websocket\r\n" .
                "Connection: Upgrade\r\n" .
                "WebSocket-Origin: $host\r\n" .
                "WebSocket-Location: ws://$host:$port/\r\n" .
                "Sec-WebSocket-Accept: $secAccept\r\n\r\n";
    socket_write($client_conn, $upgrade, strlen($upgrade));
}

function mask($text)
{
    $b1 = 0x81;
    $length = strlen($text);
    if ($length <= 125) {
        $header = pack('CC', $b1, $length);
    } elseif ($length > 125 && $length < 65536) {
        $header = pack('CCn', $b1, 126, $length);
    } else {
        $header = pack('CCNN', $b1, 127, $length);
    }
    return $header . $text;
}

function unmask($text)
{
    $length = ord($text[1]) & 127;
    if ($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    } elseif ($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    } else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }
    $decoded = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $decoded .= $data[$i] ^ $masks[$i % 4];
    }
    return $decoded;
}
?>
