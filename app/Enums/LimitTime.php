<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LimitTime extends Enum
{
    const HalfHour = 30;
    const Hour = 60;
    const TwoHour = 120;
    const ThreeHour = 180;
    const FourHour = 240;
}
