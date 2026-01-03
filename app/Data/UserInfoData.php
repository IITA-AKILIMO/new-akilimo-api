<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class UserInfoData extends Data
{
    public string $deviceToken;

    public string $farmName;
    public string $firstName;
    public string $lastName;

    public ?string $emailAddress = 'akilimo@cgiar.org';
    public ?string $phoneNumber = '0000000000';
    public string $gender;
    public bool $sendEmail;
    public bool $sendSms;
    public string $userName;

    public int $riskAttitude;
}
