<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistByCustomerReferenceRelationshipExpander;
use Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistByCustomerReferenceRelationshipExpanderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapper;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapper;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilder;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemAdder;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemAdderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemDeleter;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemDeleterInterface;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemUpdater;
use Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemUpdaterInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistCreator;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistCreatorInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistDeleter;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistDeleterInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReader;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistUpdater;
use Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistUpdaterInterface;

/**
 * @method \Spryker\Client\WishlistsRestApi\WishlistsRestApiClientInterface getClient()
 * @method \Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistReaderInterface
     */
    public function createWishlistReader(): WishlistReaderInterface
    {
        return new WishlistReader(
            $this->getWishlistClient(),
            $this->createWishlistRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistCreatorInterface
     */
    public function createWishlistCreator(): WishlistCreatorInterface
    {
        return new WishlistCreator(
            $this->getWishlistClient(),
            $this->createWishlistMapper(),
            $this->createWishlistRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistDeleterInterface
     */
    public function createWishlistDeleter(): WishlistDeleterInterface
    {
        return new WishlistDeleter(
            $this->getClient(),
            $this->createWishlistRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Wishlists\WishlistUpdaterInterface
     */
    public function createWishlistUpdater(): WishlistUpdaterInterface
    {
        return new WishlistUpdater(
            $this->createWishlistMapper(),
            $this->getClient(),
            $this->createWishlistRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemAdderInterface
     */
    public function createWishlistItemAdder(): WishlistItemAdderInterface
    {
        return new WishlistItemAdder(
            $this->getClient(),
            $this->createWishlistRestResponseBuilder(),
            $this->createWishlistItemMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemUpdaterInterface
     */
    public function createWishlistItemUpdater(): WishlistItemUpdaterInterface
    {
        return new WishlistItemUpdater(
            $this->getClient(),
            $this->createWishlistRestResponseBuilder(),
            $this->createWishlistItemMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\WishlistItems\WishlistItemDeleterInterface
     */
    public function createWishlistItemDeleter(): WishlistItemDeleterInterface
    {
        return new WishlistItemDeleter(
            $this->getClient(),
            $this->createWishlistRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    public function createWishlistRestResponseBuilder(): WishlistRestResponseBuilderInterface
    {
        return new WishlistRestResponseBuilder(
            $this->getConfig(),
            $this->getResourceBuilder(),
            $this->createWishlistMapper(),
            $this->createWishlistItemMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    public function createWishlistMapper(): WishlistMapperInterface
    {
        return new WishlistMapper();
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistItemMapperInterface
     */
    public function createWishlistItemMapper(): WishlistItemMapperInterface
    {
        return new WishlistItemMapper(
            $this->getRestWishlistItemsAttributesMapperPlugins(),
            $this->getWishlistItemRequestMapperPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Processor\Expander\WishlistByCustomerReferenceRelationshipExpanderInterface
     */
    public function createWishlistByCustomerReferenceRelationshipExpander(): WishlistByCustomerReferenceRelationshipExpanderInterface
    {
        return new WishlistByCustomerReferenceRelationshipExpander($this->createWishlistReader());
    }

    /**
     * @return \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    public function getWishlistClient(): WishlistsRestApiToWishlistClientInterface
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::CLIENT_WISHLIST);
    }

    /**
     * @return array<\Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface>
     */
    public function getRestWishlistItemsAttributesMapperPlugins(): array
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_MAPPER);
    }

    /**
     * @return array<\Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\WishlistItemRequestMapperPluginInterface>
     */
    public function getWishlistItemRequestMapperPlugins(): array
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::PLUGINS_WISHLIST_ITEM_REQUEST_MAPPER);
    }
}
