<?php

namespace App\Repository;

use App\Entity\Post;
use WebFramework\Entity\EntityCollection;
use WebFramework\Repository\RepositoryCore;

/**
 * Repository class for Post entities.
 *
 * @extends RepositoryCore<Post>
 */
class PostRepository extends RepositoryCore
{
    /** @var class-string<Post> The entity class associated with this repository */
    protected static string $entityClass = Post::class;

    /**
     * Get all posts ordered by creation date (newest first).
     *
     * @param int $offset The offset
     * @param int $limit  The limit (-1 for all)
     *
     * @return EntityCollection<Post> A collection of Post entities
     */
    public function getAllPosts(int $offset = 0, int $limit = -1): EntityCollection
    {
        return $this->getObjects($offset, $limit, [], 'created_at DESC');
    }

    /**
     * Get posts by user ID.
     *
     * @param int $userId The user ID
     * @param int $offset The offset
     * @param int $limit  The limit (-1 for all)
     *
     * @return EntityCollection<Post> A collection of Post entities
     */
    public function getPostsByUserId(int $userId, int $offset = 0, int $limit = -1): EntityCollection
    {
        return $this->getObjects($offset, $limit, ['user_id' => $userId], 'created_at DESC');
    }
}
