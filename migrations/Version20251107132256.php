<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107132256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecurie (id INT AUTO_INCREMENT NOT NULL, moteur_id INT NOT NULL, nom VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_B51A9B7E6C6E55B5 (nom), UNIQUE INDEX UNIQ_B51A9B7E6BF4B111 (moteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE infraction (id INT AUTO_INCREMENT NOT NULL, pilote_id INT DEFAULT NULL, ecurie_id INT DEFAULT NULL, nom_course VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, penalite_points INT DEFAULT NULL, amende_euros DOUBLE PRECISION DEFAULT NULL, INDEX IDX_C1A458F5F510AAE9 (pilote_id), INDEX IDX_C1A458F557F92A74 (ecurie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moteur (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pilote (id INT AUTO_INCREMENT NOT NULL, ecurie_id INT NOT NULL, prenom VARCHAR(100) NOT NULL, nom VARCHAR(100) NOT NULL, points INT NOT NULL, date_debut DATE NOT NULL, statut VARCHAR(20) NOT NULL, INDEX IDX_6A3254DD57F92A74 (ecurie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(50) NOT NULL, unit_price NUMERIC(5, 2) NOT NULL, created_at DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, storage SMALLINT NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ecurie ADD CONSTRAINT FK_B51A9B7E6BF4B111 FOREIGN KEY (moteur_id) REFERENCES moteur (id)');
        $this->addSql('ALTER TABLE infraction ADD CONSTRAINT FK_C1A458F5F510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilote (id)');
        $this->addSql('ALTER TABLE infraction ADD CONSTRAINT FK_C1A458F557F92A74 FOREIGN KEY (ecurie_id) REFERENCES ecurie (id)');
        $this->addSql('ALTER TABLE pilote ADD CONSTRAINT FK_6A3254DD57F92A74 FOREIGN KEY (ecurie_id) REFERENCES ecurie (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ecurie DROP FOREIGN KEY FK_B51A9B7E6BF4B111');
        $this->addSql('ALTER TABLE infraction DROP FOREIGN KEY FK_C1A458F5F510AAE9');
        $this->addSql('ALTER TABLE infraction DROP FOREIGN KEY FK_C1A458F557F92A74');
        $this->addSql('ALTER TABLE pilote DROP FOREIGN KEY FK_6A3254DD57F92A74');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ecurie');
        $this->addSql('DROP TABLE infraction');
        $this->addSql('DROP TABLE moteur');
        $this->addSql('DROP TABLE pilote');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
