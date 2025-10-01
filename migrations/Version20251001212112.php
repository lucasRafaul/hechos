<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001212112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clima (id INT AUTO_INCREMENT NOT NULL, descripcion VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detalle_siniestro (id INT AUTO_INCREMENT NOT NULL, id_siniestro_id INT DEFAULT NULL, rol VARCHAR(255) NOT NULL, estado_alcoholico VARCHAR(255) NOT NULL, porcentaje_alcohol VARCHAR(255) NOT NULL, observaciones VARCHAR(255) NOT NULL, INDEX IDX_8E39D83AB3D828FE (id_siniestro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detalle_siniestro_persona (detalle_siniestro_id INT NOT NULL, persona_id INT NOT NULL, INDEX IDX_842F0DE7595962E7 (detalle_siniestro_id), INDEX IDX_842F0DE7F5F88DB9 (persona_id), PRIMARY KEY(detalle_siniestro_id, persona_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, dni VARCHAR(255) NOT NULL, fecha_nacimiento DATE NOT NULL, genero VARCHAR(255) NOT NULL, estado_civil VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE siniestro (id INT AUTO_INCREMENT NOT NULL, fecha DATE NOT NULL, ubicacion VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_siniestro ADD CONSTRAINT FK_8E39D83AB3D828FE FOREIGN KEY (id_siniestro_id) REFERENCES siniestro (id)');
        $this->addSql('ALTER TABLE detalle_siniestro_persona ADD CONSTRAINT FK_842F0DE7595962E7 FOREIGN KEY (detalle_siniestro_id) REFERENCES detalle_siniestro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detalle_siniestro_persona ADD CONSTRAINT FK_842F0DE7F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_siniestro DROP FOREIGN KEY FK_8E39D83AB3D828FE');
        $this->addSql('ALTER TABLE detalle_siniestro_persona DROP FOREIGN KEY FK_842F0DE7595962E7');
        $this->addSql('ALTER TABLE detalle_siniestro_persona DROP FOREIGN KEY FK_842F0DE7F5F88DB9');
        $this->addSql('DROP TABLE clima');
        $this->addSql('DROP TABLE detalle_siniestro');
        $this->addSql('DROP TABLE detalle_siniestro_persona');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE siniestro');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
