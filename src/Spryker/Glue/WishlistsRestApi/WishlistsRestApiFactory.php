<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistRelationshipExpanderByResourceId;
use Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistRelationshipExpanderByResourceIdInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapper;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapper;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilder;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemsWriter;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemsWriterInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsReader;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsReaderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsWriter;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsWriterInterface;

/**
 * @method \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface getClient()
 */
class WishlistsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsReaderInterface
     */
    public function createWishlistsReader(): WishlistsReaderInterface
    {
        return new WishlistsReader(
            $this->getWishlistClient(),
            $this->getResourceBuilder(),
            $this->createWishlistsResourceMapper(),
            $this->createWishlistItemsResourceMapper(),
            $this->getClient(),
            $this->createWishlistRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistsWriterInterface
     */
    public function createWishlistsWriter(): WishlistsWriterInterface
    {
        return new WishlistsWriter(
            $this->getWishlistClient(),
            $this->getResourceBuilder(),
            $this->createWishlistsResourceMapper(),
            $this->createWishlistItemsResourceMapper(),
            $this->createWishlistsReader(),
            $this->getClient()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemsWriterInterface
     */
    public function createWishlistItemsWriter(): WishlistItemsWriterInterface
    {
        return new WishlistItemsWriter(
            $this->getWishlistClient(),
            $this->getResourceBuilder(),
            $this->createWishlistItemsResourceMapper(),
            $this->createWishlistsReader()
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistsRestResponseBuilderInterface
     */
    public function createWishlistRestResponseBuilder(): WishlistsRestResponseBuilderInterface
    {
        return new WishlistsRestResponseBuilder();
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    public function getWishlistClient(): WishlistsRestApiToWishlistClientInterface
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::CLIENT_WISHLIST);
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistsResourceMapperInterface
     */
    public function createWishlistsResourceMapper(): WishlistsResourceMapperInterface
    {
        return new WishlistsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemsResourceMapperInterface
     */
    public function createWishlistItemsResourceMapper(): WishlistItemsResourceMapperInterface
    {
        return new WishlistItemsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistRelationshipExpanderByResourceIdInterface
     */
    public function createWishlistRelationshipExpanderByResourceId(): WishlistRelationshipExpanderByResourceIdInterface
    {
        return new WishlistRelationshipExpanderByResourceId($this->createWishlistsReader());
    }
}
