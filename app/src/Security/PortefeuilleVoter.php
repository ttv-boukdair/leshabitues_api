<?php
// src/Security/ArticleVoter.php

namespace App\Security;

use App\Entity\User;
use App\Entity\Portefeuille;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;




class PortefeuilleVoter extends Voter
{
    const EDIT = 'edit';
    const VIEW= 'view';
    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports(string $attribute, $subject):bool
    {
        if (!in_array($attribute, [self::EDIT, self::VIEW])) {
            return false;
        }

        if (!$subject instanceof Portefeuille) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param mixed $subject
     *
     * @return bool
     */
    protected function voteOnAttribute(
        string $attribute,
        $subject,
        TokenInterface $token
    ): bool {

        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

      /**
         * @var Portefeuille
         */
        $portefeuille= $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView( $portefeuille, $user);
            case self::EDIT:
                return $this->canEdit( $portefeuille, $user);
        }

        throw new \LogicException('This code should not be reached!');
    
       
        return  $user->hasRoles('ROLE_ADMIN') || $user === $portefeuille->getClient();
    }


    private function canView(Portefeuille $portefeuille, User $user): bool
    {
        return  $user->hasRoles('ROLE_ADMIN') || $user === $portefeuille->getClient();
    }
    private function canEdit(Post $post, User $user): bool
    {
        
        return $user->hasRoles('ROLE_ADMIN') ;
    }
}