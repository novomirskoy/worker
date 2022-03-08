<?php

declare(strict_types=1);

namespace Tests\Novomirskoy\Worker;

use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;

final class EmptyExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;
}