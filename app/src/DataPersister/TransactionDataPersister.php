<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Portefeuille;
use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\Exception\InvalidArgumentException;
/**
 *
 */
class TransactionDataPersister implements ContextAwareDataPersisterInterface
{
    const CREDIT= 'credit';
    const DEBIT= 'debit';
    const ERROR_TYPE_MSG= "Il faut choisir un type de transaction ['credit','debit']";
    const ERROR_LOGIC= 'This code should not be reached!';
    const ERROR_OFFER_MSG_EMPTY= 'Il faut choisir une Offre';
    const ERROR_OFFER_MSG_NOT_PUBLISHED="L'offre n'est plus active";
    const ERROR_PORTEFEUILLE_MSG_EMPTY="Il faut choisir un Portefeuille";
    const ERROR_PORTEFEUILLE_MSG_ACCESS_DENIED="Accès refusé à ce Portefeuille";
    const ERROR_DB_MSG_TRANSACTION="Erreur de sauvegarde de la transaction";
    const ERROR_MONTANT_MSG_INCORRECT="Montant incorrect";
    const ERROR_SOLDE_MSG_INSUFFICIENT="Solde insuffisant";
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
        return $data instanceof Transaction;
    }

    /**
     * @param Transaction $data
     */
    public function persist($data, array $context = []):void
    {
        $user=$this->_security->getUser();

    
       // return 404 in type of trasanction is null
       $type=$data->getType();
      if(null===$type) throw new InvalidArgumentException(self::ERROR_TYPE_MSG);

       // return 404 if portefeuille is null
      if(null===$data->getPortefeuille()) throw new InvalidArgumentException(self::ERROR_PORTEFEUILLE_MSG_EMPTY);

       // return 404 if portefeuille is not owned by the currente user or user with role Admin
       if($user!==$data->getPortefeuille()->getClient() )  throw new InvalidArgumentException(self::ERROR_PORTEFEUILLE_MSG_ACCESS_DENIED);
    
       // tracking dates
       $data->setPublishedAt(new \DateTimeImmutable());
       $data->setUpdatedAt(new \DateTimeImmutable());

       switch ($type) {
        case self::CREDIT:
            $this->Credit( $data, $user);
            break;
        case self::DEBIT:
             $this->Debit( $data, $user);
             break;
        default:
          // return 404 if type of trasanction is not credit or debit
        throw new InvalidArgumentException(self::ERROR_TYPE_MSG);
       }
    
     

    
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }

    private function Credit(Transaction $transaction, User $user): void
    {
        //check if offer exist in transaction
        if(null===$transaction->getOffre())
        throw new InvalidArgumentException(self::ERROR_OFFER_MSG_EMPTY);
        
        //check if offer is enabled
        $offre=$this->_entityManager->getRepository("App:Offre")->find($transaction->getOffre());
        if( $offre &&  !$offre->getIsPublished())
        throw new InvalidArgumentException(self::ERROR_OFFER_MSG_NOT_PUBLISHED);

        // update transaction object 
        $montant=$offre->getMontantAvecRemise();
        $transaction->setMontant($montant);

        //update portefeuille object
        $portefeuille=$transaction->getPortefeuille();
        $portefeuille->setSolde($portefeuille->getSolde()+ $montant);


        // suspend auto-commit
        $this->_entityManager->getConnection()->beginTransaction();
        try {
        $this->_entityManager->persist($transaction);
         $this->_entityManager->persist($portefeuille);
         $this->_entityManager->flush();
         $this->_entityManager->getConnection()->commit();
        } catch (Exception $e) {
            // rollback 
            $this->_entityManager->getConnection()->rollBack();
            throw new ServiceException(ERROR_DB_MSG_TRANSACTION);
        }

        
    }
    private function Debit(Transaction $transaction, User $user):void
    {
        $portefeuille=$transaction->getPortefeuille();
        $solde=$transaction->getPortefeuille()->getSolde();
        $montant=$transaction->getMontant();

        if($montant > $solde  ) throw new InvalidArgumentException(self::ERROR_SOLDE_MSG_INSUFFICIENT);



        // suspend auto-commit
        $this->_entityManager->getConnection()->beginTransaction();
        try {
            // update solde in portefeuille
            $portefeuille->setSolde( $solde-$montant);
            $this->_entityManager->persist($transaction);
            $this->_entityManager->persist($portefeuille);
            $this->_entityManager->flush();
            $this->_entityManager->getConnection()->commit();
        } catch (Exception $e) {
            // rollback 
            $this->_entityManager->getConnection()->rollBack();
            throw new ServiceException(ERROR_DB_MSG_TRANSACTION);
        }

    }
}