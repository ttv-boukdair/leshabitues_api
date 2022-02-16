<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
/**
 *
 */
class OffreDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $_request;
    private $_security;
    private $validator;

  
    public function __construct(
        EntityManagerInterface $entityManager,RequestStack $request,  Security $security,ValidatorInterface $validator
    
    ) {
        $this->_entityManager = $entityManager;
        $this->_request = $request->getCurrentRequest();
        $this->_security = $security;
        $this->validator=$validator;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Offre;
    }

    /**
     * @param Offre$data
     */
    public function persist($data, array $context = []):void
    {
       
      
       // add commercant to offer
        $data->setCommercant($this->_security->getUser());
        
        // check for UniqueEntity
        $errors = $this->validator->validate($data);

        if(count($errors) > 0){
            throw new ValidationException($errors);
        }

            // new offre : add publish and  created&updated date 
            if ($this->_request->getMethod() === 'POST') {
                $data->setIsPublished(true);
                $data->setPublishedAt(new \DateTimeImmutable());
                 $data->setUpdatedAt(new \DateTimeImmutable());
               
            }

          // update offer: add updated date
            if ($this->_request->getMethod() !== 'POST') {
                $data->setUpdatedAt(new \DateTimeImmutable());
            }


        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}