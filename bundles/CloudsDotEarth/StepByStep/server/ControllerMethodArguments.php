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

}