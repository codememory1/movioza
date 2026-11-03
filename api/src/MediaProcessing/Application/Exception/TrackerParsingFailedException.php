<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Exception;

use RuntimeException;

/**
 * Indicates an expected failure to resolve a tracker page into a download source.
 */
final class TrackerParsingFailedException extends RuntimeException
{
}
