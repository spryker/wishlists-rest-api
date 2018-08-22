<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Business\WishlistItem;

use Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface;
use Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiRepositoryInterface;

class WishlistItemsWriter implements WishlistItemsWriterInterface
{
    /**
     * @var \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface
     */
    protected $wishlistsRestApiEntityManager;

    /**
     * @var \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiRepositoryInterface
     */
    protected $wishlistsRestApiRepository;

    /**
     * @param \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiEntityManagerInterface $wishlistsRestApiEntityManager
     * @param \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiRepositoryInterface $wishlistsRestApiRepository
     */
    public function __construct(
        WishlistsRestApiEntityManagerInterface $wishlistsRestApiEntityManager,
        WishlistsRestApiRepositoryInterface $wishlistsRestApiRepository
    ) {
        $this->wishlistsRestApiEntityManager = $wishlistsRestApiEntityManager;
        $this->wishlistsRestApiRepository = $wishlistsRestApiRepository;
    }

    /**
     * @return void
     */
    public function updateWishlistItemsUuid(): void
    {
        do {
            $wishlistEntities = $this->wishlistsRestApiRepository
                ->getWishlistItemEntitiesWithoutUuid();

            foreach ($wishlistEntities as $wishlistEntity) {
                $this->wishlistsRestApiEntityManager->saveWishlistItemEntity($wishlistEntity);
            }
        } while ($wishlistEntities);
    }
}
