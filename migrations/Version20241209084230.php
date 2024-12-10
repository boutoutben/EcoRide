<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209084230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, carpool_id INT DEFAULT NULL, user_id INT DEFAULT NULL, mark_id INT DEFAULT NULL, license_plate VARCHAR(20) NOT NULL, first_registration DATETIME DEFAULT NULL, model VARCHAR(100) NOT NULL, color VARCHAR(100) NOT NULL, energie VARCHAR(100) NOT NULL, nb_passenger INT NOT NULL, INDEX IDX_773DE69D9A6F0DAE (carpool_id), INDEX IDX_773DE69DA76ED395 (user_id), INDEX IDX_773DE69D4290F12B (mark_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, start_place VARCHAR(50) NOT NULL, end_place VARCHAR(50) NOT NULL, place_left INT NOT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, is_ecologique TINYINT(1) NOT NULL, is_great TINYINT(1) NOT NULL, is_start TINYINT(1) NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_E95D90CCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool_participation (id INT AUTO_INCREMENT NOT NULL, carpool_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6FB6E529A6F0DAE (carpool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mark (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, opinion VARCHAR(500) NOT NULL, grade INT NOT NULL, is_valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, carpool_participation_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(50) DEFAULT NULL, surname VARCHAR(50) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, nb_credit INT NOT NULL, img VARCHAR(255) DEFAULT NULL, user_type VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, preference JSON NOT NULL, INDEX IDX_8D93D649D8685255 (carpool_participation_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D9A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D4290F12B FOREIGN KEY (mark_id) REFERENCES mark (id)');
        $this->addSql('ALTER TABLE carpool ADD CONSTRAINT FK_E95D90CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE carpool_participation ADD CONSTRAINT FK_6FB6E529A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D8685255 FOREIGN KEY (carpool_participation_id) REFERENCES carpool_participation (id)');
        $this->addSql("INSERT INTO mark(label) VALUES('Toyota'),('Volkswagen'),('Ford'),('Hando'),('Chevrolet'),('BMW'),('MERCEDES_Benz'),('Audi'),('Nissan'),('Peugeot'),('Renault'),('Ferrari'),('Ferrari'),('Porsche'),('Lamborghini'),('Tesla')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D9A6F0DAE');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D4290F12B');
        $this->addSql('ALTER TABLE carpool DROP FOREIGN KEY FK_E95D90CCA76ED395');
        $this->addSql('ALTER TABLE carpool_participation DROP FOREIGN KEY FK_6FB6E529A6F0DAE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D8685255');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE carpool');
        $this->addSql('DROP TABLE carpool_participation');
        $this->addSql('DROP TABLE mark');
        $this->addSql('DROP TABLE opinion');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE user');
        $this->addSql("DELETE FROM mark");
    }
}
