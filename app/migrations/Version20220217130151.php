<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217130151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, commercant_id INT NOT NULL, montant INT NOT NULL, remise DOUBLE PRECISION NOT NULL, is_published TINYINT(1) NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AF86866F83FA6DD0 (commercant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portefeuille (id INT AUTO_INCREMENT NOT NULL, commercant_id INT NOT NULL, client_id INT NOT NULL, solde DOUBLE PRECISION NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2955FFFE83FA6DD0 (commercant_id), INDEX IDX_2955FFFE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, offre_id INT DEFAULT NULL, portefeuille_id INT NOT NULL, type VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_723705D14CC8505A (offre_id), INDEX IDX_723705D1513EC3CA (portefeuille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, code_postal INT DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F83FA6DD0 FOREIGN KEY (commercant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE portefeuille ADD CONSTRAINT FK_2955FFFE83FA6DD0 FOREIGN KEY (commercant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE portefeuille ADD CONSTRAINT FK_2955FFFE19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D14CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1513EC3CA FOREIGN KEY (portefeuille_id) REFERENCES portefeuille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D14CC8505A');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1513EC3CA');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F83FA6DD0');
        $this->addSql('ALTER TABLE portefeuille DROP FOREIGN KEY FK_2955FFFE83FA6DD0');
        $this->addSql('ALTER TABLE portefeuille DROP FOREIGN KEY FK_2955FFFE19EB6921');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE portefeuille');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE `user`');
    }
}
