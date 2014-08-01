<?php
/**
 * File containing the Elasticsearch Loader Extractor class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Elasticsearch\Content\Search\Extractor;

use eZ\Publish\Core\Persistence\Elasticsearch\Content\Search\Extractor;
use eZ\Publish\SPI\Persistence\Content\Handler as ContentHandler;
use eZ\Publish\SPI\Persistence\Content\Location\Handler as LocationHandler;
use RuntimeException;

/**
 * The Loader Extractor extracts the value object from the Elasticsearch search hit data
 * by loading it from the database.
 */
class Loader extends Extractor
{
    /**
     * Content handler
     *
     * @var \eZ\Publish\SPI\Persistence\Content\Handler
     */
    protected $contentHandler;

    /**
     * Location handler
     *
     * @var \eZ\Publish\SPI\Persistence\Content\Location\Handler
     */
    protected $locationHandler;

    public function __construct( ContentHandler $contentHandler, LocationHandler $locationHandler )
    {
        $this->contentHandler = $contentHandler;
        $this->locationHandler = $locationHandler;
    }

    /**
     *
     *
     * @throws \RuntimeException If search $hit could not be handled
     *
     * @param mixed $hit
     *
     * @return \eZ\Publish\API\Repository\Values\ValueObject
     */
    public function extractHit( $hit )
    {
        if ( $hit->_type === "content" )
        {
            return $this->contentHandler->load(
                $hit->_id,
                $hit->_source->version_id
            );
        }

        if ( $hit->_type === "location" )
        {
            return $this->locationHandler->load( $hit->_id );
        }

        throw new RuntimeException( "Could not extract: document of type '{$hit->_type}' is not handled." );
    }
}
