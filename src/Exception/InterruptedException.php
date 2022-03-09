<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Exception;

use RuntimeException;

/**
 * Class InterruptedException
 *
 * @package Novomirskoy\Worker
 */
final class InterruptedException extends RuntimeException implements ExceptionInterface
{
}
