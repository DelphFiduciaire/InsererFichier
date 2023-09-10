<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910183106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichier_demande (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_fichier_id INT DEFAULT NULL, id_info_client_id INT DEFAULT NULL, nom_fichier_demande VARCHAR(255) NOT NULL, INDEX IDX_FD072B9279F37AE5 (id_user_id), INDEX IDX_FD072B92AA1BDC29 (id_fichier_id), INDEX IDX_FD072B9289FD691A (id_info_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichier_demande ADD CONSTRAINT FK_FD072B9279F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fichier_demande ADD CONSTRAINT FK_FD072B92AA1BDC29 FOREIGN KEY (id_fichier_id) REFERENCES fichier (id)');
        $this->addSql('ALTER TABLE fichier_demande ADD CONSTRAINT FK_FD072B9289FD691A FOREIGN KEY (id_info_client_id) REFERENCES info_client (id)');
        $this->addSql('ALTER TABLE info_client ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE info_client ADD CONSTRAINT FK_A995B0379F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A995B0379F37AE5 ON info_client (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_demande DROP FOREIGN KEY FK_FD072B9279F37AE5');
        $this->addSql('ALTER TABLE fichier_demande DROP FOREIGN KEY FK_FD072B92AA1BDC29');
        $this->addSql('ALTER TABLE fichier_demande DROP FOREIGN KEY FK_FD072B9289FD691A');
        $this->addSql('DROP TABLE fichier_demande');
        $this->addSql('ALTER TABLE info_client DROP FOREIGN KEY FK_A995B0379F37AE5');
        $this->addSql('DROP INDEX IDX_A995B0379F37AE5 ON info_client');
        $this->addSql('ALTER TABLE info_client DROP id_user_id');
    }
}
