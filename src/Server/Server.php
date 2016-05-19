<?php

namespace Phlux\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Phlux\Contracts\PhluxInterface;
use Phlux\Exceptions\InvalidMessageException;
use Phlux\Contracts\ParserInterface;
use SplObjectStorage;
use Exception;

class Server implements MessageComponentInterface
{
    /**
     * The ratchet connection
     *
     * @var Ratchet\Server\IoServer
     */
    protected $connection;

    /**
     * The phlux instance
     *
     * @var Phlux\Contracts\PhluxInterface
     */
    protected $phlux;

    /**
     * Specify the port that the server should be started on
     *
     * @var int
     */
    protected $port;

    /**
     * A collection of clients
     *
     * @var SplObjectStorage
     */
    protected $clients;

    /**
     * The parser to be used for all connections
     *
     * @var Phlux\Contracts\ParserInterface
     */
    protected $parser;

    /**
     * Create a new phlux server
     *
     * @param Phlux\Contracts\PhluxInterface $phlux
     * @param Phlux\Contracts\ParserInterface $parser
     * @return void
     */
    public function __construct(PhluxInterface $phlux, ParserInterface $parser, $port = 8080)
    {
        $this->phlux = $phlux;
        $this->parser = $parser;
        $this->port = $port;
        $this->clients = new SplObjectStorage;
    }

    /**
     * Start the phlux server
     *
     * @return void
     */
    public function start()
    {
        $this->connection = IoServer::factory(new HttpServer(new WsServer($this)), $this->port)->run();
    }

    /**
     * Fires when a new client joins the server
     *
     * @param Ratchet\ConnectionInterface $connection
     * @return void
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients->attach($conn);
    }

    /**
     * Fires when a client messages the server
     *
     * @param Ratchet\ConnectionInterface $from
     * @param string $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            // Parse the event from the message
            list($event, $payload) = $this->parser->message($msg);

            // Fire the event with Phlux
            $this->phlux->fire($event, $payload)->run();

            // Return the current state to the user
            $from->send($this->phlux->getState());
        } catch (InvalidMessageException $e) {

            // Send the user the error message
            $from->send($e->getMessage());
        }
    }

    /**
     * Fires when a client leaves the server
     *
     * @param Ratchet\ConnectionInterface $connection
     * @param string $msg
     * @return void
     */
    public function onClose(ConnectionInterface $connection)
    {

    }

    /**
     * Fires when a client causes an error
     *
     * @param Ratchet\ConnectionInterface $connection
     * @param Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {

    }
}
