<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require dirname(__DIR__) . '/vendor/autoload.php';

class CallManager implements MessageComponentInterface {
    protected $clients;
    protected $callQueue;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->callQueue = [];
        echo "Servidor WebSocket iniciado!\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nova conexão! ({$conn->resourceId})\n";

        $conn->send(json_encode([
            'type' => 'queue_update',
            'queue' => $this->callQueue
        ]));
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if (isset($data['type'])) {
            if ($data['type'] === 'new_call') {
                $call = [
                    'id' => uniqid(),
                    'number' => $data['number'],
                    'tipo' => $data['tipo'] ?? 'senha',
                    'guiche' => $data['guiche'] ?? null,
                    'sala' => $data['sala'] ?? null,
                    'mensagem' => $data['mensagem'] ?? "Senha {$data['number']}",
                    'timestamp' => date('Y-m-d H:i:s'),
                    'fila_id' => $data['fila_id'] ?? null,
                    'status' => $data['status'] ?? 'pendente'
                ];
                $this->callQueue[] = $call;

                $this->broadcast([
                    'type' => 'new_call',
                    'call' => $call
                ]);
            } elseif ($data['type'] === 'remove_call') {
                $this->callQueue = array_filter($this->callQueue, function ($call) use ($data) {
                    return $call['id'] !== $data['call_id'];
                });
                $this->callQueue = array_values($this->callQueue);

                $this->broadcast([
                    'type' => 'queue_update',
                    'queue' => $this->callQueue
                ]);
            } elseif ($data['type'] === 'finalize_call') {
                $this->callQueue = array_map(function ($call) use ($data) {
                    if ($call['id'] === $data['call_id']) {
                        $call['status'] = 'finalizada';
                    }
                    return $call;
                }, $this->callQueue);

                $this->callQueue = array_filter($this->callQueue, function ($call) {
                    return $call['status'] !== 'finalizada';
                });
                $this->callQueue = array_values($this->callQueue);

                $this->broadcast([
                    'type' => 'queue_update',
                    'queue' => $this->callQueue
                ]);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Conexão fechada! ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function broadcast($message) {
        $message = json_encode($message);
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new CallManager()
        )
    ),
    8080
);

$server->run();