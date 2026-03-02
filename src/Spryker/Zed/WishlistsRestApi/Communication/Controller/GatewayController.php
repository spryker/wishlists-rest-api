<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Communication\Controller;

use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    public function updateWishlistAction(WishlistRequestTransfer $wishlistRequestTransfer): WishlistResponseTransfer
    {
        return $this->getFacade()->updateWishlist($wishlistRequestTransfer);
    }

    public function deleteWishlistAction(WishlistFilterTransfer $wishlistFilterTransfer): WishlistResponseTransfer
    {
        return $this->getFacade()->deleteWishlist($wishlistFilterTransfer);
    }

    public function addWishlistItemAction(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFacade()->addWishlistItem($wishlistItemRequestTransfer);
    }

    public function deleteWishlistItemAction(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFacade()->deleteWishlistItem($wishlistItemRequestTransfer);
    }

    public function updateWishlistItemAction(WishlistItemRequestTransfer $wishlistItemRequestTransfer): WishlistItemResponseTransfer
    {
        return $this->getFacade()->updateWishlistItem($wishlistItemRequestTransfer);
    }
}
