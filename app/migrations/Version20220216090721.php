<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216090721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE portefeuille (id INT AUTO_INCREMENT NOT NULL, commercant_id INT NOT NULL, client_id INT NOT NULL, solde INT NOT NULL, INDEX IDX_2955FFFE83FA6DD0 (commercant_id), INDEX IDX_2955FFFE19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, commercant_id INT NOT NULL, offre_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_723705D119EB6921 (client_id), INDEX IDX_723705D183FA6DD0 (commercant_id), INDEX IDX_723705D14CC8505A (offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE portefeuille ADD CONSTRAINT FK_2955FFFE83FA6DD0 FOREIGN KEY (commercant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE portefeuille ADD CONSTRAINT FK_2955FFFE19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D183FA6DD0 FOREIGN KEY (commercant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D14CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE portefeuille');
        $this->addSql('DROP TABLE transaction');
    }
}
