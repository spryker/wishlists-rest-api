<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\CustomersStorefrontResource;
use Generated\Api\Storefront\WishlistsStorefrontResource;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;
use Spryker\Client\Wishlist\WishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Mapper\WishlistsResourceMapper;

/**
 * Replaces the legacy `WishlistRelationshipByResourceIdPlugin` for `/customers/{customerReference}?include=wishlists`.
 * Fetches the wishlist collection for each parent customer and emits one
 * `WishlistsStorefrontResource` per wishlist.
 */
class CustomersWishlistsRelationshipResolver extends AbstractRelationshipResolver
{
    public function __construct(
        protected WishlistClientInterface $wishlistClient,
        protected WishlistsResourceMapper $wishlistsResourceMapper,
    ) {
    }

    /**
     * @return array<\Generated\Api\Storefront\WishlistsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        $resources = [];

        foreach ($this->getParentResources() as $parent) {
            if (!$parent instanceof CustomersStorefrontResource || $parent->customerReference === null) {
                continue;
            }

            $wishlistCollectionTransfer = $this->wishlistClient->getWishlistCollection(
                (new CustomerTransfer())->setCustomerReference($parent->customerReference),
            );

            foreach ($wishlistCollectionTransfer->getWishlists() as $wishlistTransfer) {
                $resources[] = $this->wishlistsResourceMapper->mapWishlistTransferToResource(
                    $wishlistTransfer,
                    new WishlistsStorefrontResource(),
                );
            }
        }

        return $resources;
    }
}
