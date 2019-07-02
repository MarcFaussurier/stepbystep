<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 30/06/2019
 * Time: 23:43
 */

namespace CloudsDotEarth\StepByStep;


class ControllerMethodArguments
{
    public const SUPPORTED_OPS = [
        0 => "ℹ️",   // begin given matches declaration ... eval the given statement ???
        1 => "🤔",    // begin even events declaration ... eval the given statement ???
        2 => "✅",    // check the match
        3 => "❌",    // don't check the match
        4 => "💾",    // inherit_match
        5 => "⛔",    // negate 1 4 (notice :: it is already the case) 6 7 8 9
        6 => "➡️",
        7 => "⬅️",
        8 => "⛓",
        9 => "䷼",
    ];

    public $given_matches               = [];       //  ℹ️ or nothing // expect controller::method !!!
    public $events                      = [];       //  🤔 or nothing
    public $ignore_events               = [];       //  ⛔🤔 or nothing
    public $verify_the_match            = true;     //  ✅ or ❌
    public $inherit_match               = false;    //  💾 or nothing ️
    public $proceed_after               = true;     // ️➡️ or   ⛔➡️
    public $proceed_before              = true;     // ️⬅️ or  ️ ⛔️⬅️
    public $proceed_hooks     = true;     //  ⛓ or   ⛔⛓
    public $proceed_inner_events        = true;     // ️䷼ or   ⛔䷼

    public function __construct(string $target_prefix = "") {
        if ( $target_prefix !== "") {
            $chars = str_split($target_prefix);
            $last_char = $chars[0];
            $current_buffer = "";
            $waiting_buffer = false;
            $callback = function($buffer) { };
            foreach ($chars as $position => $char) {
                switch ($char) {
                    case "ℹ️": // ℹ️
                        $waiting_buffer = true;
                        $callback = function ($buffer) {
                            $this->given_matches = eval($buffer);
                        };
                        break;
                    case "🤔": // 🤔
                        $waiting_buffer = true;
                        $callback = function ($buffer) {
                            // todo :: handle that
                            $this->ignore_events = explode(",", $buffer);
                        };
                        break;
                    case "💾":
                        $this->inherit_match = true;
                        break;
                    case "➡️": // ➡️
                        if ($last_char === "⛔") {
                            $this->proceed_after = false;
                        } else {
                            $this->proceed_after = true;
                        }
                        break;
                    case "⬅️": // ⬅️
                        if ($last_char === "⛔") {
                            $this->proceed_before = false;
                        } else {
                            $this->proceed_before = true;
                        }
                        break;
                    case "⛓":
                        if ($last_char === "⛔") {
                            $this->proceed_hooks = false;
                        } else {
                            $this->proceed_hooks = true;
                        }
                        break;
                    case "䷼":
                        if ($last_char === "⛔") {
                            $this->proceed_inner_events = false;
                        } else {
                            $this->proceed_inner_events = true;
                        }
                        break;
                    default:
                        if ($waiting_buffer && (!isset($chars[$position + 1]) || (isset($chars[$position + 1]) && in_array($chars[$position + 1], self::SUPPORTED_OPS)))) {
                            $callback($current_buffer);
                            $current_buffer = "";
                        } else {
                            $current_buffer .= $char;
                        }
                        break;
                }
                $last_char = $char;
            }
        }
    }

    // todo :: add arguments support ❌ℹ️
    /**
     *      *
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
     *  // [handle target methods after this one #2]
     *
     *  // [handle target methods after this one #1-5]
     *
     *  // [handle target methods after this one]
     *
     * // php[
     *      // php format array here for the matches arguments
     * ]
     *
     */
}