<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410124401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY commande_ibfk_1');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_3');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_post id_post INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCD1AA708F FOREIGN KEY (id_post) REFERENCES post (id_post)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY post_ibfk_1');
        $this->addSql('ALTER TABLE post CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES catégorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27C9486A13 ON produit (id_categorie)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY reponse_ibfk_1');
        $this->addSql('ALTER TABLE reponse CHANGE id_reclamation id_reclamation INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation)');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_3');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_2');
        $this->addSql('ALTER TABLE vote CHANGE id_post id_post INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085646B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564D1AA708F FOREIGN KEY (id_post) REFERENCES post (id_post)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC6B3CA4B');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCD1AA708F');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT NOT NULL, CHANGE id_post id_post INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_3 FOREIGN KEY (id_post) REFERENCES post (id_post) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6B3CA4B');
        $this->addSql('ALTER TABLE post CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT post_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('DROP INDEX IDX_29A5EC27C9486A13 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7D672A9F3');
        $this->addSql('ALTER TABLE reponse CHANGE id_reclamation id_reclamation INT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT reponse_ibfk_1 FOREIGN KEY (id_reclamation) REFERENCES reclamation (id_reclamation) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085646B3CA4B');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564D1AA708F');
        $this->addSql('ALTER TABLE vote CHANGE id_user id_user INT NOT NULL, CHANGE id_post id_post INT NOT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT vote_ibfk_3 FOREIGN KEY (id_post) REFERENCES post (id_post) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT vote_ibfk_2 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
