<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi\Zed;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface;

class WishlistsRestApiStub implements WishlistsRestApiStubInterface
{
    /**
     * @var \Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface
     */
    protected $zedStubClient;

    /**
     * @param \Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface $zedStubClient
     */
    public function __construct(WishlistsRestApiToZedRequestClientInterface $zedStubClient)
    {
        $this->zedStubClient = $zedStubClient;
    }

    /**
     * @uses \Spryker\Zed\WishlistsRestApi\Communication\Controller\GatewayController::updateWishlistAction()
     *
     * @param \Generated\Shared\Transfer\WishlistRequestTransfer $wishlistRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function updateWishlist(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/update-wishlist',
            $wishlistRequestTransfer,
        );

        return $wishlistResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\WishlistsRestApi\Communication\Controller\GatewayController::deleteWishlistAction()
     *
     * @param \Generated\Shared\Transfer\WishlistFilterTransfer $wishlistFilterTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function deleteWishlist(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistResponseTransfer $wishlistResponseTransfer */
        $wishlistResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/delete-wishlist',
            $wishlistFilterTransfer,
        );

        return $wishlistResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\WishlistsRestApi\Communication\Controller\GatewayController::addWishlistItemAction()
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function addWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistItemResponseTransfer $wishlistItemResponseTransfer */
        $wishlistItemResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/add-wishlist-item',
            $wishlistItemRequestTransfer,
        );

        return $wishlistItemResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\WishlistsRestApi\Communication\Controller\GatewayController::deleteWishlistItemAction()
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function deleteWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistItemResponseTransfer $wishlistItemResponseTransfer */
        $wishlistItemResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/delete-wishlist-item',
            $wishlistItemRequestTransfer,
        );

        return $wishlistItemResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\WishlistsRestApi\Communication\Controller\GatewayController::updateWishlistItemAction()
     *
     * @param \Generated\Shared\Transfer\WishlistItemRequestTransfer $wishlistItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemResponseTransfer
     */
    public function updateWishlistItem(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\WishlistItemResponseTransfer $wishlistItemResponseTransfer */
        $wishlistItemResponseTransfer = $this->zedStubClient->call(
            '/wishlists-rest-api/gateway/update-wishlist-item',
            $wishlistItemRequestTransfer,
        );

        return $wishlistItemResponseTransfer;
    }
}
