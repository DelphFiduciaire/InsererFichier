<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230928101129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_bilan DROP annee_bilan, CHANGE nom_fihcier_bilan nom_fichier_bilan VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fichier_nom_bilan ADD annee_bilan INT NOT NULL');
        $this->addSql('ALTER TABLE info_client DROP verif');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_bilan ADD annee_bilan INT NOT NULL, CHANGE nom_fichier_bilan nom_fihcier_bilan VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE fichier_nom_bilan DROP annee_bilan');
        $this->addSql('ALTER TABLE info_client ADD verif TINYINT(1) NOT NULL');
    }
}
