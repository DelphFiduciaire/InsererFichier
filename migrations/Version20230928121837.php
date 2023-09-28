<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928121837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee (id INT AUTO_INCREMENT NOT NULL, annee_bilan INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichier_bilan ADD id_annee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fichier_bilan ADD CONSTRAINT FK_C386FB7C4E52965 FOREIGN KEY (id_annee_id) REFERENCES annee (id)');
        $this->addSql('CREATE INDEX IDX_C386FB7C4E52965 ON fichier_bilan (id_annee_id)');
        $this->addSql('ALTER TABLE fichier_nom_bilan DROP annee_bilan');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_bilan DROP FOREIGN KEY FK_C386FB7C4E52965');
        $this->addSql('DROP TABLE annee');
        $this->addSql('DROP INDEX IDX_C386FB7C4E52965 ON fichier_bilan');
        $this->addSql('ALTER TABLE fichier_bilan DROP id_annee_id');
        $this->addSql('ALTER TABLE fichier_nom_bilan ADD annee_bilan INT NOT NULL');
    }
}
