<?php

namespace App\DTO\User;

use App\DTO\DTORequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTORequest extends DTORequest
{

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 2, max: 50)]
    public string $name;

    #[Assert\Type('string')]
    #[Assert\Length(min: 2, max: 255)]
    public string $description;
}