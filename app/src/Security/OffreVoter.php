<?php
// src/Security/ArticleVoter.php

namespace App\Security;

use App\Entity\User;
use App\Entity\Offre;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;




class OffreVoter extends Voter
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

        if (!$subject instanceof Offre) {
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
         * @var Offre
         */
        $offre= $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView( $offre, $user);
            case self::EDIT:
                return $this->canEdit( $offre, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }


    private function canView(Offre $offre, User $user): bool
    {
        return  true;
    }
    private function canEdit(Offre $offre, User $user): bool
    {
        
        return $user->hasRoles('ROLE_ADMIN')|| $user === $offre->getCommercant(); 
    }
}