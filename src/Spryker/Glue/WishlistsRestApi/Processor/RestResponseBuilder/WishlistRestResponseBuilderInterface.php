<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\WishlistCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface WishlistRestResponseBuilderInterface
{
    public function createErrorResponseFromErrorIdentifier(string $errorIdentifier): RestResponseInterface;

    public function createWishlistsRestResponse(?WishlistTransfer $wishlistTransfer = null): RestResponseInterface;

    public function createWishlistItemsRestResponse(string $idWishlist, ?WishlistItemTransfer $wishlistItemTransfer = null): RestResponseInterface;

    public function createWishlistsResource(WishlistTransfer $wishlistTransfer): RestResourceInterface;

    public function createWishlistCollectionResponse(WishlistCollectionTransfer $wishlistCollectionTransfer): RestResponseInterface;

    public function createEmptyResponse(): RestResponseInterface;

    public function createErrorResponseFromErrorMessage(RestErrorMessageTransfer $errorMessage): RestResponseInterface;

    /**
     * @param array<string> $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestErrorResponse(array $errors): RestResponseInterface;

    public function createUnknownErrorResponse(): RestResponseInterface;

    public function createCantAddWishlistItemErrorResponse(): RestResponseInterface;

    public function createWishlistNotFoundErrorResponse(): RestResponseInterface;

    public function createItemSkuMissingErrorToResponse(): RestResponseInterface;

    public function createMissingAccessTokenErrorResponse(): RestResponseInterface;
}
