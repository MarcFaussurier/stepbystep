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
    public $given_matches               = [];       //  ℹ️
    public $proceed_events              = [];       //  🤔
    public $ignore_events               = [];       //  ⛔🤔
    public $verify_the_match            = true;     //  ✅ or ❌
    public $inherit_match               = false;    //  💾
    public $proceed_after               = true;     // ️➡️
    public $proceed_before              = true;     // ️⬅️
    public $proceed_attached_events     = true;     //  ⛓
    public $proceed_inner_events        = true;     // ️䷼
    public $invert_next                 = false;    //  ⛔

    // todo :: add arguments support
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
     */
}