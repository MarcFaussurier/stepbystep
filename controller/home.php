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
     *
     * ❌ℹ️⛔ we don't verify the match and do nothing at all AND set match using given array
     * ❌ℹ️💪 we don't verify the match and handle only events AND set match using given array
     * ❌ℹ️⛓ : we don't verify the match and handle attached events AND set match using given array
     * ❌ℹ️䷼ : we don't verify the match and handle inner events AND set match using given array
     * ❌ℹ️⛔⛓ : we don't verify the match and dont handle attached events AND set match using given array
     * ❌ℹ️⛔⬅️䷼ : we don't verify the match and dont handle inner events AND set match using given array
     * ❌ℹ️⬅️⛓ : we don't verify the match and handle attached before events AND set match using given array
     * ❌ℹ️⬅️䷼ : we don't verify the match and handle inner before events AND set match using given array
     * ❌ℹ️⛔⬅️⛓ : we don't verify the match and dont handle attached before events AND set match using given array
     * ❌ℹ️⛔⬅️䷼ : we don't verify the match and dont handle inner before events AND set match using given array
     * ❌ℹ️➡️⛓ : we don't verify the match and handle attached after events AND set match using given array
     * ❌ℹ️➡️䷼ : we don't verify the match and handle inner after events AND set match using given array
     * ❌ℹ️⛔➡️⛓ : we don't verify the match and don't handle attached after events AND set match using given array
     * ❌ℹ️⛔➡️䷼ : we don't verify the match and don't handle inner after events AND set match using given array
     * ❌ℹ️🤔 : we don't verify the match and handle given ev array AND set match using given array
     * ❌ℹ️⛔🤔 : we don't verify the match and don't handle given ev array AND set match using given array
     *
     *
     * ❌⛔ we don't verify the match and do nothing at all
     * ❌💪 we don't verify the match and handle only events
     * ❌⛔💪 we don't verify the match and handle only events
     * ❌⛓ : we don't verify the match and handle attached events
     * ❌䷼ : we don't verify the match and handle inner events
     * ❌⛔⛓ : we don't verify the match and dont handle attached events
     * ❌⛔⬅️䷼ : we don't verify the match and dont handle inner events
     * ❌⬅️⛓ : we don't verify the match and handle attached before events
     * ❌⬅️䷼ : we don't verify the match and handle inner before events
     * ❌⛔⬅️⛓ : we don't verify the match and dont handle attached before events
     * ❌⛔⬅️䷼ : we don't verify the match and dont handle inner before events
     * ❌➡️⛓ : we don't verify the match and handle attached after events
     * ❌➡️䷼ : we don't verify the match and handle inner after events
     * ❌⛔➡️⛓ : we don't verify the match and don't handle attached after events
     * ❌⛔➡️䷼ : we don't verify the match and don't handle inner after events
     * ❌🤔 : we don't verify the match and handle given ev array
     * ❌⛔🤔 : we don't verify the match and don't handle given ev array
     * ❌   : we don't  verify the match and  handle all events
     * we verify the match and don't handle page only
     * ✅⛔ we verify the match and do nothing at all
     * ✅💪 we verify the match and force the handle
     * ✅⛓ : we verify the match and handle attached events
     * ✅䷼ : we verify the match and handle inner events
     * ✅⛔⛓ : we verify the match and dont handle attached events
     * ✅⛔䷼ : we verify the match and dont handle inner events
     * ✅⬅️⛓ : we verify the match and handle attached before events
     * ✅⬅️️䷼ : we verify the match and handle inner before events
     * ✅⛔⬅️⛓ : we verify the match and dont handle attached before events
     * ✅⛔⬅️䷼ : we verify the match and dont handle inner before events
     * ✅➡️⛓ : we verify the match and handle attached after events
     * ✅➡️䷼ : we verify the match and handle inner after events
     * ✅⛔➡️⛓ : we verify the match and don't handle attached after events
     * ✅⛔➡️䷼ : we verify the match and don't handle inner after events
     * ✅🤔 : we verify the match and handle given ev array
     * ✅⛔🤔 : we verify the match and don't handle given ev array
     * ✅   : we verify the match and  handle all events
     *
     *
     * ✅💾⛔ we verify the match and do nothing at all AND inerhit match
     * ✅💾💪 we verify the match and force the handle AND inerhit match
     * ✅💾⛓ : we verify the match and handle attached events AND inerhit match
     * ✅💾䷼ : we verify the match and handle inner events AND inerhit match
     * ✅💾⛔⛓ : we verify the match and dont handle attached events AND inerhit match
     * ✅💾⛔䷼ : we verify the match and dont handle inner events AND inerhit match
     * ✅💾⬅️⛓ : we verify the match and handle attached before events AND inerhit match
     * ✅💾⬅️️䷼ : we verify the match and handle inner before events AND inerhit match
     * ✅💾⛔⬅️⛓ : we verify the match and dont handle attached before events AND inerhit match
     * ✅💾⛔⬅️䷼ : we verify the match and dont handle inner before events AND inerhit match
     * ✅💾➡️⛓ : we verify the match and handle attached after events AND inerhit match
     * ✅💾➡️䷼ : we verify the match and handle inner after events AND inerhit match
     * ✅💾⛔➡️⛓ : we verify the match and don't handle attached after events AND inerhit match
     * ✅💾⛔➡️䷼ : we verify the match and don't handle inner after events AND inerhit match
     * ✅💾🤔 : we verify the match and handle given ev array AND inerhit match
     * ✅💾⛔🤔 : we verify the match and don't handle given ev array AND inerhit match
     * ✅💾  : we verify the match and  handle all events AND inerhit match
     *
     *
     * ✅ℹ️⛔ we verify the match and do nothing at all AND set match using given array
     * ✅ℹ️💪 we verify the match and force the handle AND set match using given array
     * ✅ℹ️⛓ : we verify the match and handle attached events AND set match using given array
     * ✅ℹ️䷼ : we verify the match and handle inner events AND set match using given array
     * ✅ℹ️⛔⛓ : we verify the match and dont handle attached events AND set match using given array
     * ✅ℹ️⛔䷼ : we verify the match and dont handle inner events AND set match using given array
     * ✅ℹ️⬅️⛓ : we verify the match and handle attached before events AND set match using given array
     * ✅ℹ️⬅️️䷼ : we verify the match and handle inner before events AND set match using given array
     * ✅ℹ️⛔⬅️⛓ : we verify the match and dont handle attached before events AND set match using given array
     * ✅ℹ️⛔⬅️䷼ : we verify the match and dont handle inner before events AND set match using given array
     * ✅ℹ️➡️⛓ : we verify the match and handle attached after events AND set match using given array
     * ✅ℹ️➡️䷼ : we verify the match and handle inner after events AND set match using given array
     * ✅ℹ️⛔➡️⛓ : we verify the match and don't handle attached after events AND set match using given array
     * ✅ℹ️⛔➡️䷼ : we verify the match and don't handle inner after events AND set match using given array
     * ✅ℹ️🤔 : we verify the match and handle given ev array AND set match using given array
     * ✅ℹ️⛔🤔 : we verify the match and don't handle given ev array AND set match using given array
     * ✅ℹ️  : we verify the match and  handle all events AND set match using given array
     *
     *
     *
     *
     *
     * @handle target methods after this one
     *      🎯🔓📋 \CloudsDotEarth\StepByStep\Controller::toto
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
    public function request(&$request, &$response, $matches = []): bool {
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
    public function toto(&$request, &$response, $matches = []): bool {
        echo "toto2" . PHP_EOL;
        var_dump($matches);
        return true;
    }

    /**
     * @packet_type     start
     * @priority        0
     * @run_after
     * @run_before
     *
     * @param           $server
     * @return          bool
     **/
    public function start($server): bool {
        echo "Swoole http server is started at http://127.0.0.1:8080\n";
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
    public function open($server, $req): bool {
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
    public function message($server, $frame, $matches = []): bool {
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
    public function close($server, $fd): bool {
        echo "connection close: {$fd}\n";
    }
}

