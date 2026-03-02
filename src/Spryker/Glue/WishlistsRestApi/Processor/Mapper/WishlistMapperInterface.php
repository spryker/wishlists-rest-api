<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

interface WishlistMapperInterface
{
    public function mapWishlistTransferToRestWishlistsAttributes(WishlistTransfer $wishlistTransfer): RestWishlistsAttributesTransfer;

    public function mapWishlistAttributesToWishlistTransfer(
        RestWishlistsAttributesTransfer $attributesTransfer,
        WishlistTransfer $wishlistTransfer
    ): WishlistTransfer;
}
