<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsRestApi\Processor\Wishlists;

use Generated\Shared\Transfer\RestWishlistsAttributesTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface;
use Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface;

class WishlistCreator implements WishlistCreatorInterface
{
    /**
     * @var \Spryker\Glue\WishlistsRestApi\Dependency\Client\WishlistsRestApiToWishlistClientInterface
     */
    protected $wishlistClient;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\Mapper\WishlistMapperInterface
     */
    protected $wishlistMapper;

    /**
     * @var \Spryker\Glue\WishlistsRestApi\Processor\RestResponseBuilder\WishlistRestResponseBuilderInterface
     */
    protected $wishlistRestResponseBuilder;

    public function __construct(
        WishlistsRestApiToWishlistClientInterface $wishlistClient,
        WishlistMapperInterface $wishlistMapper,
        WishlistRestResponseBuilderInterface $wishlistRestResponseBuilder
    ) {
        $this->wishlistClient = $wishlistClient;
        $this->wishlistMapper = $wishlistMapper;
        $this->wishlistRestResponseBuilder = $wishlistRestResponseBuilder;
    }

    public function create(RestWishlistsAttributesTransfer $attributesTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        $wishlistTransfer = $this->createWishlistTransfer($attributesTransfer, $restRequest);

        $wishlistResponseTransfer = $this->wishlistClient->validateAndCreateWishlist($wishlistTransfer);
        if (!$wishlistResponseTransfer->getIsSuccess()) {
            return $this->wishlistRestResponseBuilder->createRestErrorResponse($wishlistResponseTransfer->getErrors());
        }

        return $this->wishlistRestResponseBuilder
            ->createWishlistsRestResponse($wishlistResponseTransfer->getWishlist());
    }

    protected function createWishlistTransfer(
        RestWishlistsAttributesTransfer $restWishlistsAttributesTransfer,
        RestRequestInterface $restRequest
    ): WishlistTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUser */
        $restUser = $restRequest->getRestUser();
        /** @var int $surrogateIdentifier */
        $surrogateIdentifier = $restUser->getSurrogateIdentifier();

        return $this->wishlistMapper
            ->mapWishlistAttributesToWishlistTransfer($restWishlistsAttributesTransfer, new WishlistTransfer())
            ->setFkCustomer($surrogateIdentifier);
    }
}
