<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213235511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, anonyme TINYINT(1) NOT NULL, likes INT NOT NULL, valide TINYINT(1) NOT NULL, date_c DATETIME NOT NULL, date_m DATETIME NOT NULL, user_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BC38B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, com_ouvert TINYINT(1) NOT NULL, anonyme TINYINT(1) NOT NULL, likes INT NOT NULL, valide TINYINT(1) NOT NULL, date_c DATETIME NOT NULL, date_m DATETIME NOT NULL, vues INT NOT NULL, user_id INT NOT NULL, categorie_id INT NOT NULL, sous_categorie_id INT NOT NULL, INDEX IDX_AF3C6779A76ED395 (user_id), INDEX IDX_AF3C6779BCF5E72D (categorie_id), INDEX IDX_AF3C6779365BF48 (sous_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779BCF5E72D');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779365BF48');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE publication');
    }
}
