<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\WishlistItemsStorefrontResource;
use Generated\Api\Storefront\WishlistsStorefrontResource;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;
use Spryker\ApiPlatform\Relationship\PerItemRelationshipResolverInterface;
use Spryker\Client\Wishlist\WishlistClientInterface;
use Spryker\Glue\WishlistsRestApi\Api\Storefront\Mapper\WishlistsResourceMapper;
use Spryker\Service\Serializer\SerializerServiceInterface;

/**
 * Builds the `wishlist-items` relationship target from rows pre-computed on
 * `WishlistsStorefrontResource::$wishlistItemsRelationshipData` by `WishlistsResourceMapper`.
 * The mapper has already run the `RestWishlistItemsAttributesMapperPluginInterface` plugin chain
 * so each row carries the final `id` (sku, or sku_hash for configured products).
 */
class WishlistsWishlistItemsRelationshipResolver extends AbstractRelationshipResolver implements PerItemRelationshipResolverInterface
{
    public function __construct(
        protected SerializerServiceInterface $serializer,
        protected WishlistClientInterface $wishlistClient,
        protected WishlistsResourceMapper $wishlistsResourceMapper,
    ) {
    }

    /**
     * @param array<object> $parentResources
     * @param array<string, mixed> $context
     *
     * @return array<string, array<\Generated\Api\Storefront\WishlistItemsStorefrontResource>>
     */
    public function resolvePerItem(array $parentResources, array $context): array
    {
        $this->parentResources = $parentResources;
        $this->context = $context;

        $wishlistItemResourcesIndexedByWishlistUuid = [];

        foreach ($parentResources as $parent) {
            if (!$parent instanceof WishlistsStorefrontResource || $parent->uuid === null) {
                continue;
            }

            $wishlistItemResourcesIndexedByWishlistUuid[$parent->uuid] = $this->buildWishlistItemResources($parent);
        }

        return $wishlistItemResourcesIndexedByWishlistUuid;
    }

    /**
     * @return array<\Generated\Api\Storefront\WishlistItemsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        return array_merge([], ...array_values($this->resolvePerItem($this->getParentResources(), $this->context)));
    }

    /**
     * @return array<\Generated\Api\Storefront\WishlistItemsStorefrontResource>
     */
    protected function buildWishlistItemResources(WishlistsStorefrontResource $parent): array
    {
        $resources = [];

        foreach ($this->getWishlistItemsRelationshipData($parent) as $row) {
            $resource = $this->serializer->denormalize($row, WishlistItemsStorefrontResource::class);
            $resource->uuid = (string)($row['uuid'] ?? '');
            $resource->wishlist = $parent;
            $resources[] = $resource;
        }

        return $resources;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function getWishlistItemsRelationshipData(WishlistsStorefrontResource $parent): array
    {
        if ($parent->wishlistItemsRelationshipData !== [] || !$parent->numberOfItems || !$this->hasRequest() || !$this->hasCustomer()) {
            return $parent->wishlistItemsRelationshipData;
        }

        $wishlistResponseTransfer = $this->wishlistClient->getWishlistByFilter(
            (new WishlistFilterTransfer())
                ->setIdCustomer($this->getCustomer()->getIdCustomerOrFail())
                ->setUuid((string)$parent->uuid),
        );

        $wishlistTransfer = $wishlistResponseTransfer->getWishlist();

        if (!$wishlistResponseTransfer->getIsSuccess() || $wishlistTransfer === null) {
            return [];
        }

        return $this->wishlistsResourceMapper->mapWishlistItemsRelationshipData($wishlistTransfer);
    }
}
