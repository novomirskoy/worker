<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Exception;

use RuntimeException;

final class InterruptedException extends RuntimeException implements ExceptionInterface {}
