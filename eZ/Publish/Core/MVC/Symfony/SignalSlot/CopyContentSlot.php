<?php
/**
 * File containing the Legacy\CopyContentSlot class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace eZ\Publish\Core\MVC\Symfony\SignalSlot;

use eZ\Publish\Core\SignalSlot\Signal;
use eZContentCacheManager;
use eZContentObject;
use eZContentOperationCollection;

/**
 * A slot handling CopyContentSignal.
 */
class CopyContentSlot extends AbstractSlot
{
    /**
     * Receive the given $signal and react on it
     *
     * @param \eZ\Publish\Core\SignalSlot\Signal $signal
     *
     * @return void
     */
    public function receive( Signal $signal )
    {
        if ( !$signal instanceof Signal\ContentService\CopyContentSignal )
            return;

        $this->runLegacyKernelCallback(
            function () use ( $signal )
            {
                eZContentCacheManager::clearContentCacheIfNeeded( $signal->dstContentId );
                eZContentOperationCollection::registerSearchObject( $signal->dstContentId );
                eZContentObject::clearCache();// Clear all object memory cache to free memory
            }
        );
    }
}