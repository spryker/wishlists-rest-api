<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\WishlistsRestApi\Api\Storefront\Mapper;

use Generated\Api\Storefront\WishlistItemsStorefrontResource;
use Generated\Api\Storefront\WishlistsStorefrontResource;
use Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Service\Container\Attributes\Plugins;
use Spryker\Service\Serializer\SerializerServiceInterface;

/**
 * Maps Zed transfers to API Platform resources and back
 * `RestWishlistItemsAttributesMapperPluginInterface` and `WishlistItemRequestMapperPluginInterface`
 * plugin chains so project-level extensions (e.g.
 * `MerchantProductOfferWishlistRestApi.ProductOfferRestWishlistItemsAttributesMapperPlugin`,
 * `ProductPricesRestApi.ProductPriceRestWishlistItemsAttributesMapperPlugin`) keep working
 * on the API Platform stack.
 */
class WishlistsResourceMapper
{
    protected const string KEY_CREATED_AT = 'createdAt';

    protected const string KEY_UPDATED_AT = 'updatedAt';

    protected const string KEY_UUID = 'uuid';

    protected const string KEY_ID = 'id';

    /**
     * @param array<\Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesMapperPluginInterface> $restWishlistItemsAttributesMapperPlugins
     * @param array<\Spryker\Glue\WishlistsRestApiExtension\Dependency\Plugin\WishlistItemRequestMapperPluginInterface> $wishlistItemRequestMapperPlugins
     */
    public function __construct(
        protected SerializerServiceInterface $serializer,
        #[Plugins(dependencyProviderMethod: 'getRestWishlistItemsAttributesMapperPlugins')]
        protected array $restWishlistItemsAttributesMapperPlugins = [],
        #[Plugins(dependencyProviderMethod: 'getWishlistItemRequestMapperPlugins')]
        protected array $wishlistItemRequestMapperPlugins = [],
    ) {
    }

    public function mapWishlistTransferToResource(
        WishlistTransfer $wishlistTransfer,
        WishlistsStorefrontResource $resource,
    ): WishlistsStorefrontResource {
        $resource->uuid = $wishlistTransfer->getUuid();
        $resource->name = (string)$wishlistTransfer->getName();
        $resource->numberOfItems = $wishlistTransfer->getNumberOfItems() ?? 0;
        $resource->createdAt = $wishlistTransfer->offsetExists(static::KEY_CREATED_AT) ? (string)$wishlistTransfer->offsetGet(static::KEY_CREATED_AT) : null;
        $resource->updatedAt = $wishlistTransfer->offsetExists(static::KEY_UPDATED_AT) ? (string)$wishlistTransfer->offsetGet(static::KEY_UPDATED_AT) : null;

        $resource->wishlistItemsRelationshipData = $this->mapWishlistItemsRelationshipData($wishlistTransfer);

        return $resource;
    }

    /**
     * Returns pre-computed wishlist items; consumed by `WishlistsWishlistItemsRelationshipResolver`.
     *
     * @return array<int, array<string, mixed>>
     */
    public function mapWishlistItemsRelationshipData(WishlistTransfer $wishlistTransfer): array
    {
        $rows = [];

        foreach ($wishlistTransfer->getWishlistItems() as $wishlistItemTransfer) {
            $restWishlistItemsAttributesTransfer = (new RestWishlistItemsAttributesTransfer())
                ->fromArray($wishlistItemTransfer->toArray(), true);
            $restWishlistItemsAttributesTransfer->setId($wishlistItemTransfer->getSku());

            foreach ($this->restWishlistItemsAttributesMapperPlugins as $plugin) {
                $restWishlistItemsAttributesTransfer = $plugin->map(
                    $wishlistItemTransfer,
                    $restWishlistItemsAttributesTransfer,
                );
            }

            $row = $restWishlistItemsAttributesTransfer->toArray(true, true);
            $row[static::KEY_UUID] = $row[static::KEY_ID] ?? null;
            $rows[] = $row;
        }

        return $rows;
    }

    public function mapWishlistItemTransferToResource(
        WishlistItemTransfer $wishlistItemTransfer,
        WishlistItemsStorefrontResource $resource,
        ?string $wishlistUuid = null,
    ): WishlistItemsStorefrontResource {
        $restWishlistItemsAttributesTransfer = new RestWishlistItemsAttributesTransfer();
        $restWishlistItemsAttributesTransfer->fromArray($wishlistItemTransfer->toArray(), true);
        $restWishlistItemsAttributesTransfer->setId($wishlistItemTransfer->getSku());

        foreach ($this->restWishlistItemsAttributesMapperPlugins as $plugin) {
            $restWishlistItemsAttributesTransfer = $plugin->map($wishlistItemTransfer, $restWishlistItemsAttributesTransfer);
        }

        $arrayData = $restWishlistItemsAttributesTransfer->toArray(true, true);

        $this->serializer->denormalize($arrayData, WishlistItemsStorefrontResource::class, null, null, $resource);

        $resource->uuid = (string)$restWishlistItemsAttributesTransfer->getId();
        $resource->sku = (string)$wishlistItemTransfer->getSku();

        // IriConverter needs `$resource->wishlist->uuid` to build self-link
        // `/wishlists/{wishlistUuid}/wishlist-items/{uuid}` (Get operation uses `toProperty: wishlist`).
        if ($wishlistUuid !== null && $wishlistUuid !== '') {
            $parent = new WishlistsStorefrontResource();
            $parent->uuid = $wishlistUuid;
            $resource->wishlist = $parent;
        }

        return $resource;
    }

    public function mapResourceToWishlistTransfer(
        WishlistsStorefrontResource $resource,
        WishlistTransfer $wishlistTransfer,
    ): WishlistTransfer {
        if ($resource->name !== null) {
            $wishlistTransfer->setName($resource->name);
        }

        return $wishlistTransfer;
    }

    /**
     * Builds the legacy `WishlistItemRequest` transfer from the raw request attributes (not from
     * the typed resource), so composed properties contributed by other modules
     * (e.g. `ProductConfigurationWishlistsRestApi.configuration`,
     * `MerchantProductOfferWishlistRestApi.productOfferReference`) reach the mapper plugin chain
     * — they live on the composed `RestWishlistItemsAttributesTransfer`, not on our YAML resource.
     *
     * @param array<string, mixed> $rawAttributes
     */
    public function mapAttributesArrayToWishlistItemRequestTransfer(
        array $rawAttributes,
        WishlistItemRequestTransfer $wishlistItemRequestTransfer,
    ): WishlistItemRequestTransfer {
        $restWishlistItemsAttributesTransfer = (new RestWishlistItemsAttributesTransfer())
            ->fromArray($rawAttributes, true);

        if (
            isset($rawAttributes['productConfigurationInstance'])
            && is_array($rawAttributes['productConfigurationInstance'])
        ) {
            $restWishlistItemsAttributesTransfer->setProductConfigurationInstance(
                (new RestWishlistItemProductConfigurationInstanceAttributesTransfer())
                    ->fromArray($rawAttributes['productConfigurationInstance'], true),
            );
        }

        $transferData = $restWishlistItemsAttributesTransfer->toArray(true, true);
        unset($transferData['productConfigurationInstance']);
        $this->serializer->denormalize($transferData, WishlistItemRequestTransfer::class, null, null, $wishlistItemRequestTransfer);

        foreach ($this->wishlistItemRequestMapperPlugins as $plugin) {
            $wishlistItemRequestTransfer = $plugin->map(
                $restWishlistItemsAttributesTransfer,
                $wishlistItemRequestTransfer,
            );
        }

        return $wishlistItemRequestTransfer;
    }
}
