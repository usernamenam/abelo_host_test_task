<?php

declare(strict_types=1);

namespace App\Entity;

use Webmozart\Assert\Assert;

final class Category
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description = null,
    ) {
        Assert::positiveInteger($id);
        Assert::stringNotEmpty($name);
        Assert::stringNotEmpty($slug);
    }
}
