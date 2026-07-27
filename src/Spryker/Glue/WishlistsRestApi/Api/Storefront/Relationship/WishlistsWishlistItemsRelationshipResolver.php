<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\WishlistItemsStorefrontResource;
use Generated\Api\Storefront\WishlistsStorefrontResource;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;
use Spryker\Service\Serializer\SerializerServiceInterface;

/**
 * Builds the `wishlist-items` relationship target from rows pre-computed on
 * `WishlistsStorefrontResource::$wishlistItemsRelationshipData` by `WishlistsResourceMapper`.
 * The mapper has already run the `RestWishlistItemsAttributesMapperPluginInterface` plugin chain
 * so each row carries the final `id` (sku, or sku_hash for configured products).
 */
class WishlistsWishlistItemsRelationshipResolver extends AbstractRelationshipResolver
{
    public function __construct(
        protected SerializerServiceInterface $serializer,
    ) {
    }

    /**
     * @return array<\Generated\Api\Storefront\WishlistItemsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        $resources = [];

        foreach ($this->getParentResources() as $parent) {
            if (!$parent instanceof WishlistsStorefrontResource) {
                continue;
            }

            foreach ($parent->wishlistItemsRelationshipData as $row) {
                $resource = $this->serializer->denormalize($row, WishlistItemsStorefrontResource::class);
                $resource->uuid = (string)($row['uuid'] ?? '');
                $resource->wishlist = $parent;
                $resources[] = $resource;
            }
        }

        return $resources;
    }
}
