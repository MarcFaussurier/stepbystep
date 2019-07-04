<?php

namespace CloudsDotEarth\App\Controller;

class Home extends \CloudsDotEarth\StepByStep\Controller {


    /**
     * @param           $request
     * @param           $response
     * @param           $matches  array
     * @return          bool
     * @this_one        request
     * @packet_type     request
     * @match           /hello[a-zA-Z.]{0,5}
     * @priority        0
     * @handle target methods after this one
     *      ✅ℹ️⛔🤔[handle target methods after this one #2]  🎯 \CloudsDotEarth\StepByStep\Controller::toto
     * @handle target methods before this one
     * @handle this method after target ones
     * @handle this method before target ones
     * @handle target methods after this one by ignoring its stop
     * @handle target methods before this one by ignoring its stop
     * @handle this method by ignoring its stop after target ones
     * @handle this method by ignoring its stop before target ones
     * @handle target methods by ignoring their stop after this one
     * @handle target methods by ignoring their stop before this one
     * @handle this method after target ones by ignoring their stop
     * @handle this method before target ones by ignoring their stop
     * @handle target methods by ignoring their stop after this one by ignoring its stop
     * @handle target methods by ignoring their stop before this one by ignoring its stop
     * @handle this method by ignoring its stop after target ones by ignoring their stop
     * @handle this method by ignoring its stop before target ones by ignoring their stop
     *
     **/
    public function request(&$request, &$response, &$cross_call_memory, &$main_bundle, &$matches = []): bool {
        $response->end($main_bundle->twig->render("marcfsr/stepbystep/home", ['msg' => 'toto']));
        echo "toto1" . PHP_EOL;
        var_dump($matches);
        return false;
    }


    /**
     * @param           $request
     * @param           $response
     * @param           $matches  array
     * @return          bool
     * @this_one        toto
     * @packet_type     request
     * @match           /hello[a-zA-Z.]{0,5}
     * @priority        0
     *
     **/
    public function toto(&$request, &$response, $cross_call_memory, $main_bundle, &$matches = []): bool {
        echo "toto2" . PHP_EOL;
        var_dump($matches);
        return true;
    }

    /**
     * @packet_type     start
     * @priority        0
     * @this_one        start
     *
     * @param           $server
     * @return          bool
     **/
    public function start(&$server, $cross_call_memory, $main_bundle, &$matches = []): bool {
        echo "Swoole http server is started att http://127.0.0.1:8080\n";
        echo "Connecting to database...";
            $pg = new \Swoole\Coroutine\PostgreSql();
            $conn = $pg -> connect ("host=127.0.0.1 port=5432 dbname=stepbystep user=postgres password=");
            if(!$conn){
                var_dump($pg->error);
            } else {
                echo "okok";
            }
        echo "returned";
        return false;
    }

    /**
     * @packet_type     open
     * @priority        0
     * @run_after
     * @run_before
     *
     * @param           $server
     * @param           $req
     * @return          bool
     **/
    public function open(&$server, &$req, $cross_call_memory, $main_bundle, &$matches = []): bool {
        echo "connection open: {$req->fd}\n";
    }

    /**
     * @packet_type     message
     * @match           /set
     * @priority        0
     * @run_after
     * @run_before
     *
     * @param           $server
     * @param           $frame
     * @param           $matches  array
     * @return          bool
     **/
    public function message(&$server, &$frame, $cross_call_memory, $main_bundle, &$matches = []): bool {
        echo "received message: {$frame->data}\n";
        $server->push($frame->fd, json_encode(["hello", "world"]));
    }

    /**
     * @packet_type     close
     * @priority        0
     * @run_after
     * @run_before
     *
     * @param           $server
     * @param           $fd
     * @return          bool
     **/
    public function close(&$server, &$fd, $cross_call_memory, $main_bundle, &$matches = []): bool {
        echo "connection close: {$fd}\n";
    }
}

