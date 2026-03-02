<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistDeleter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistDeleterInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUpdater;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUpdaterInterface;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriter;
use Spryker\Zed\WishlistsRestApi\Business\Wishlist\WishlistUuidWriterInterface;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemAdder;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemAdderInterface;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemDeleter;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemDeleterInterface;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemUpdater;
use Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemUpdaterInterface;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;
use Spryker\Zed\WishlistsRestApi\WishlistsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\WishlistsRestApi\WishlistsRestApiConfig getConfig()
 */
class WishlistsRestApiBusinessFactory extends AbstractBusinessFactory
{
    public function createWishlistUuidWriter(): WishlistUuidWriterInterface
    {
        return new WishlistUuidWriter($this->getEntityManager());
    }

    public function createWishlistUpdater(): WishlistUpdaterInterface
    {
        return new WishlistUpdater(
            $this->getWishlistFacade(),
        );
    }

    public function createWishlistDeleter(): WishlistDeleterInterface
    {
        return new WishlistDeleter(
            $this->getWishlistFacade(),
        );
    }

    public function createWishlistItemAdder(): WishlistItemAdderInterface
    {
        return new WishlistItemAdder(
            $this->getWishlistFacade(),
        );
    }

    public function createWishlistItemDeleter(): WishlistItemDeleterInterface
    {
        return new WishlistItemDeleter(
            $this->getWishlistFacade(),
            $this->getRestWishlistItemsAttributesDeleteStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\WishlistsRestApi\Business\WishlistItem\WishlistItemUpdater
     */
    public function createWishlistItemUpdater(): WishlistItemUpdaterInterface
    {
        return new WishlistItemUpdater(
            $this->getWishlistFacade(),
            $this->getRestWishlistItemsAttributesUpdateStrategyPlugins(),
        );
    }

    public function getWishlistFacade(): WishlistsRestApiToWishlistFacadeInterface
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::FACADE_WISHLIST);
    }

    /**
     * @return array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface>
     */
    public function getRestWishlistItemsAttributesDeleteStrategyPlugins(): array
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_DELETE_STRATEGY);
    }

    /**
     * @return array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesUpdateStrategyPluginInterface>
     */
    public function getRestWishlistItemsAttributesUpdateStrategyPlugins(): array
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::PLUGINS_REST_WISHLIST_ITEMS_ATTRIBUTES_UPDATE_STRATEGY);
    }
}
