<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\WishlistItem;

use ArrayObject;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface;

class WishlistItemDeleter implements WishlistItemDeleterInterface
{
    /**
     * @var \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @var array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface>
     */
    protected $restWishlistItemsAttributesDeleteStrategyPlugins;

    /**
     * @param \Spryker\Zed\WishlistsRestApi\Dependency\Facade\WishlistsRestApiToWishlistFacadeInterface $wishlistFacade
     * @param array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface> $restWishlistItemsAttributesDeleteStrategyPlugins
     */
    public function __construct(
        WishlistsRestApiToWishlistFacadeInterface $wishlistFacade,
        array $restWishlistItemsAttributesDeleteStrategyPlugins
    ) {
        $this->wishlistFacade = $wishlistFacade;
        $this->restWishlistItemsAttributesDeleteStrategyPlugins = $restWishlistItemsAttributesDeleteStrategyPlugins;
    }

    public function delete(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        $wishlistItemRequestTransfer->requireIdCustomer()
            ->requireUuidWishlist()
            ->requireUuid();

        $wishlistResponseTransfer = $this->wishlistFacade
            ->getWishlistByFilter($this->createWishlistFilterTransfer($wishlistItemRequestTransfer));

        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->createWishlistNotFoundErrorResponse($wishlistResponseTransfer);
        }

        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer */
        $wishlistTransfer = $wishlistResponseTransfer->getWishlist();
        $wishlistItemTransfers = $wishlistTransfer->getWishlistItems();

        foreach ($this->restWishlistItemsAttributesDeleteStrategyPlugins as $restWishlistItemsAttributesDeleteStrategyPlugin) {
            if ($restWishlistItemsAttributesDeleteStrategyPlugin->isApplicable($wishlistItemRequestTransfer, $wishlistItemTransfers)) {
                $restWishlistItemsAttributesDeleteStrategyPlugin->delete($wishlistItemRequestTransfer, $wishlistItemTransfers);

                return $this->createSuccessResponse();
            }
        }

        if (!$this->isItemInWishlist($wishlistTransfer, $wishlistItemRequestTransfer)) {
            return $this->createWishlistItemNotFoundErrorResponse();
        }

        $this->deleteWishlistItems($wishlistItemRequestTransfer, $wishlistItemTransfers);

        return $this->createSuccessResponse();
    }

    protected function createWishlistNotFoundErrorResponse(WishlistResponseTransfer $wishlistResponseTransfer): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->setErrors($wishlistResponseTransfer->getErrors())
            ->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND);
    }

    protected function createWishlistItemTransfer(
        WishlistTransfer $wishlistTransfer,
        WishlistItemRequestTransfer $wishlistItemRequestTransfer
    ): WishlistItemTransfer {
        return (new WishlistItemTransfer())
            ->setSku($wishlistItemRequestTransfer->getUuid())
            ->setFkCustomer($wishlistItemRequestTransfer->getIdCustomer())
            ->setFkWishlist($wishlistTransfer->getIdWishlist())
            ->setWishlistName($wishlistTransfer->getName());
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    protected function createSuccessResponse()
    {
        return (new WishlistItemResponseTransfer())->setIsSuccess(true);
    }

    protected function createWishlistItemNotFoundErrorResponse(): WishlistItemResponseTransfer
    {
        return (new WishlistItemResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorIdentifier(WishlistsRestApiConfig::ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST);
    }

    protected function isItemInWishlist(WishlistTransfer $wishlistTransfer, WishlistItemRequestTransfer $wishlistItemRequestTransfer): bool
    {
        if (!$wishlistTransfer->getWishlistItems()->count()) {
            return false;
        }

        $wishlistItemTransfers = $wishlistTransfer->getWishlistItems();

        foreach ($wishlistItemTransfers as $wishlistItemTransfer) {
            if ($wishlistItemTransfer->getSku() === $wishlistItemRequestTransfer->getUuid()) {
                return true;
            }
        }

        return false;
    }

    protected function createWishlistFilterTransfer(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistFilterTransfer
    {
        return (new WishlistFilterTransfer())
            ->fromArray($wishlistItemRequestTransfer->toArray(), true)
            ->setUuid($wishlistItemRequestTransfer->getUuidWishlist());
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WishlistItemTransfer> $wishlistItemTransfers
     *
     * @return void
     */
    protected function deleteWishlistItems(WishlistItemRequestTransfer $wishlistItemRequestTransfer, ArrayObject $wishlistItemTransfers): void
    {
        foreach ($wishlistItemTransfers as $wishlistItemTransfer) {
            if ($wishlistItemTransfer->getSku() === $wishlistItemRequestTransfer->getUuid()) {
                $this->wishlistFacade->removeItem($wishlistItemTransfer);
            }
        }
    }
}
