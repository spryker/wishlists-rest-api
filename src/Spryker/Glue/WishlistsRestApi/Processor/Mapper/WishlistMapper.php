<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

class WishlistMapper implements WishlistMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistsAttributesTransfer
     */
    public function mapWishlistTransferToRestWishlistsAttributes(WishlistTransfer $wishlistTransfer): RestWishlistsAttributesTransfer
    {
        $restWishlistsAttributesTransfer = (new RestWishlistsAttributesTransfer())
            ->fromArray($wishlistTransfer->toArray(), true);
        $restWishlistsAttributesTransfer->setNumberOfItems($wishlistTransfer->getNumberOfItems() ?? 0);

        return $restWishlistsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistsAttributesTransfer $attributesTransfer
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function mapWishlistAttributesToWishlistTransfer(
        RestWishlistsAttributesTransfer $attributesTransfer,
        WishlistTransfer $wishlistTransfer
    ): WishlistTransfer {
        return $wishlistTransfer->fromArray($attributesTransfer->modifiedToArray(), true);
    }
}
