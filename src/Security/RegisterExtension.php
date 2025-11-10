<?php

namespace App\Security;

use Slim\Http\ServerRequest as Request;
use WebFramework\Entity\User;
use WebFramework\Security\Extension\RegisterExtensionInterface;

/**
 * RegisterExtension provides custom parameters and behavior for the registration flow.
 */
class RegisterExtension implements RegisterExtensionInterface
{
    /**
     * Get custom parameters for the template.
     *
     * Provides the 'name' parameter from the request for the registration form.
     *
     * @param Request $request The current request
     *
     * @return array<string, mixed> Custom parameters
     */
    public function getCustomParams(Request $request): array
    {
        return [
            'name' => $request->getParam('name', ''),
        ];
    }

    /**
     * Perform custom value checks.
     *
     * @param Request $request The current request
     *
     * @return bool True if the checks pass, false otherwise
     */
    public function customValueCheck(Request $request): bool
    {
        // No additional validation needed for name field
        return true;
    }

    /**
     * Get additional data to be passed after verification.
     *
     * @param Request $request The current request
     *
     * @return array<mixed> Additional data
     */
    public function getAfterVerifyData(Request $request): array
    {
        return [];
    }

    /**
     * Called after user creation.
     *
     * @param Request $request The current request
     * @param User    $user    The user that was created
     */
    public function postCreate(Request $request, User $user): void
    {
        // No additional actions needed after user creation
    }

    /**
     * Called after email verification.
     *
     * @param User         $user              The user that was verified
     * @param array<mixed> $afterVerifyParams Additional parameters from getAfterVerifyData()
     */
    public function postVerify(User $user, array $afterVerifyParams): void
    {
        // No additional actions needed after verification
    }
}
