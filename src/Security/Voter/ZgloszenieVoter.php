<?php
/**
 * Zgloszenie voter.
 */

namespace App\Security\Voter;

use App\Entity\Zgloszenie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ZgloszenieVoter.
 */
class ZgloszenieVoter extends Voter
{
    /**
     * Edit permission.
     *
     * @const string
     */
    public const EDIT = 'EDIT';

    /**
     * Show permission.
     *
     * @const string
     */
    public const SHOW = 'SHOW';

    /**
     * Delete permission.
     *
     * @const string
     */
    public const DELETE = 'DELETE';

    /**
     * Create permission.
     *
     * @const string
     */
    public const CREATE = 'CREATE';

    /**
     * Security helper.
     *
     * @var Security
     */
    private $security;

    /**
     * OrderVoter constructor.
     *
     * @param Security $security Security helper
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['CREATE', 'EDIT', 'DELETE', 'SHOW'])
            && $subject instanceof Zgloszenie;
    }
    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute Attribute
     * @param mixed          $subject   Subject
     * @param TokenInterface $token     Security token
     *
     * @return bool Result
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        foreach ($token->getRoleNames() as $role) {
            if (in_array($role, ['ROLE_ADMIN'])) {
                return true;
            }
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                return $this->canEdit($subject, $user);
            case 'SHOW':
                return $this->canShow($subject, $user);
            case 'DELETE':
                return $this->canDelete($subject, $user);
            case 'CREATE':
                return $this->canCreate($subject, $user);
            default:
                return false;
                break;
        }

        return false;
    }
    /**
     * Checks if user can edit zgloszenie.
     *
     * @param Zgloszenie $zgloszenie Zgloszenie entity
     * @param User $user User
     *
     * @return bool Result
     */
    private function canEdit(Zgloszenie $zgloszenie, User $user): bool
    {
        return $zgloszenie->getAuthor() === $user;
    }

    /**
     * Checks if user can view zgloszenie.
     *
     * @param Zgloszenie $zgloszenie Zgloszenie entity
     * @param User $user User
     *
     * @return bool Result
     */
    private function canShow(Zgloszenie $zgloszenie, User $user): bool
    {
        return $zgloszenie->getAuthor() === $user;
    }

    /**
     * Checks if user can delete zgloszenie.
     *
     * @param Zgloszenie $zgloszenie Zgloszenie entity
     * @param User $user User
     *
     * @return bool Result
     */
    private function canDelete(Zgloszenie $zgloszenie, User $user): bool
    {
        return $zgloszenie->getAuthor() === $user;
    }

    /**
     * Checks if user can create zgloszenie.
     *
     * @param Zgloszenie $zgloszenie Zgloszenie entity
     * @param User $user User
     *
     * @return bool Result
     */
    private function canCreate(Zgloszenie $zgloszenie, User $user): bool
    {
        return $zgloszenie->getAuthor() === $user;
    }
}