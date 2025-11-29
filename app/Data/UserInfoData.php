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

    public ?string $emailAddress;
    public ?string $phoneNumber;
    public string $gender;
    public bool $sendEmail;
    public bool $sendSms;
    public string $userName;

    public int $riskAttitude;
}
