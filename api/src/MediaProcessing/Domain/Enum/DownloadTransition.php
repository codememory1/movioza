<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

/**
 * Defines lifecycle transitions of a download.
 */
enum DownloadTransition: string
{
    /**
     * Begins the download operation.
     */
    case START = 'start';

    /**
     * Records that downloading is active.
     */
    case START_DOWNLOADING = 'start_downloading';

    /**
     * Records that the download is paused.
     */
    case PAUSE = 'pause';

    /**
     * Resumes the operation before active downloading is observed.
     */
    case RESUME = 'resume';

    /**
     * Records successful completion.
     */
    case COMPLETE = 'complete';

    /**
     * Records a definitive failure.
     */
    case FAIL = 'fail';

    /**
     * Records cancellation.
     */
    case CANCEL = 'cancel';

    /**
     * Returns the download to the pending queue.
     */
    case REQUEUE = 'requeue';
}
