<?php

namespace App\Entity;

use WebFramework\Entity\EntityCore;

/**
 * Represents a blog post in the system.
 */
class Post extends EntityCore
{
    protected static string $tableName = 'posts';
    protected static array $baseFields = ['title', 'content', 'user_id', 'created_at', 'updated_at'];
    protected static array $privateFields = [];

    protected int $id;
    protected string $title = '';
    protected string $content = '';
    protected int $userId = 0;
    protected int $createdAt = 0;
    protected int $updatedAt = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
