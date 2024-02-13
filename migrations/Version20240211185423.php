<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211185423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD prename VARCHAR(255) NOT NULL, ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, ADD is_verified TINYINT(1) DEFAULT 0 NOT NULL, DROP role, DROP mdp, DROP nom, DROP prenom, CHANGE email email VARCHAR(180) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE tel phone INT NOT NULL, CHANGE date_naissance birthday DATE NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL, ADD mdp VARCHAR(255) NOT NULL, ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, DROP name, DROP prename, DROP roles, DROP password, DROP is_verified, CHANGE email email VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE phone tel INT NOT NULL, CHANGE birthday date_naissance DATE NOT NULL');
    }
}
