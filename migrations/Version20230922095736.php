<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922095736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichier_bilan (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_info_client_id INT DEFAULT NULL, id_fichier_bilan_id INT DEFAULT NULL, nom_fihcier_bilan VARCHAR(255) NOT NULL, verif_bilan TINYINT(1) NOT NULL, INDEX IDX_C386FB7C79F37AE5 (id_user_id), INDEX IDX_C386FB7C89FD691A (id_info_client_id), INDEX IDX_C386FB7C2BB31F75 (id_fichier_bilan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fichier_nom_bilan (id INT AUTO_INCREMENT NOT NULL, fichier_bilan VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichier_bilan ADD CONSTRAINT FK_C386FB7C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fichier_bilan ADD CONSTRAINT FK_C386FB7C89FD691A FOREIGN KEY (id_info_client_id) REFERENCES info_client (id)');
        $this->addSql('ALTER TABLE fichier_bilan ADD CONSTRAINT FK_C386FB7C2BB31F75 FOREIGN KEY (id_fichier_bilan_id) REFERENCES fichier_nom_bilan (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_bilan DROP FOREIGN KEY FK_C386FB7C79F37AE5');
        $this->addSql('ALTER TABLE fichier_bilan DROP FOREIGN KEY FK_C386FB7C89FD691A');
        $this->addSql('ALTER TABLE fichier_bilan DROP FOREIGN KEY FK_C386FB7C2BB31F75');
        $this->addSql('DROP TABLE fichier_bilan');
        $this->addSql('DROP TABLE fichier_nom_bilan');
    }
}
