<?php

namespace App\Event;

use App\Entity\Post;
use Slim\Http\ServerRequest as Request;
use WebFramework\Event\Event;

class PostCreated implements Event
{
    /**
     * @param Request $request The request that triggered the event
     * @param Post    $post    The post that was created
     */
    public function __construct(public Request $request, public Post $post) {}
}
