<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

class WishlistsRestApiToWishlistClientBridge implements WishlistsRestApiToWishlistClientInterface
{
    /**
     * @var \Spryker\Client\Wishlist\WishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @param \Spryker\Client\Wishlist\WishlistClientInterface $wishlistClient
     */
    public function __construct($wishlistClient)
    {
        $this->wishlistClient = $wishlistClient;
    }

    public function getWishlistCollection(CustomerTransfer $customerTransfer): WishlistCollectionTransfer
    {
        return $this->wishlistClient->getWishlistCollection($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistResponseTransfer
     */
    public function validateAndCreateWishlist(WishlistTransfer $wishlistTransfer)
    {
        return $this->wishlistClient->validateAndCreateWishlist($wishlistTransfer);
    }

    public function getWishlistByFilter(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer
    {
        return $this->wishlistClient->getWishlistByFilter($wishlistFilterTransfer);
    }
}
