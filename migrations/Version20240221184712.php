<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221184712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, categorie_id INT NOT NULL, INDEX IDX_23A0E66BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE article_panier (article_id INT NOT NULL, panier_id INT NOT NULL, INDEX IDX_4E0B9A727294869C (article_id), INDEX IDX_4E0B9A72F77D927C (panier_id), PRIMARY KEY(article_id, panier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE categorie_produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE formulaire (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE formulaire_question (formulaire_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_A7BCB6F55053569B (formulaire_id), INDEX IDX_A7BCB6F51E27F6BF (question_id), PRIMARY KEY(formulaire_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE formulaire_reponse (formulaire_id INT NOT NULL, reponse_id INT NOT NULL, INDEX IDX_1542391C5053569B (formulaire_id), INDEX IDX_1542391CCF18BB82 (reponse_id), PRIMARY KEY(formulaire_id, reponse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_produit (id)');
        $this->addSql('ALTER TABLE article_panier ADD CONSTRAINT FK_4E0B9A727294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_panier ADD CONSTRAINT FK_4E0B9A72F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_question ADD CONSTRAINT FK_A7BCB6F55053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_question ADD CONSTRAINT FK_A7BCB6F51E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_reponse ADD CONSTRAINT FK_1542391C5053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_reponse ADD CONSTRAINT FK_1542391CCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD totale INT NOT NULL, DROP total, CHANGE panier_id panier_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier DROP id_panier');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779BCF5E72D');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779365BF48');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE article_panier DROP FOREIGN KEY FK_4E0B9A727294869C');
        $this->addSql('ALTER TABLE article_panier DROP FOREIGN KEY FK_4E0B9A72F77D927C');
        $this->addSql('ALTER TABLE formulaire_question DROP FOREIGN KEY FK_A7BCB6F55053569B');
        $this->addSql('ALTER TABLE formulaire_question DROP FOREIGN KEY FK_A7BCB6F51E27F6BF');
        $this->addSql('ALTER TABLE formulaire_reponse DROP FOREIGN KEY FK_1542391C5053569B');
        $this->addSql('ALTER TABLE formulaire_reponse DROP FOREIGN KEY FK_1542391CCF18BB82');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_panier');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('DROP TABLE formulaire');
        $this->addSql('DROP TABLE formulaire_question');
        $this->addSql('DROP TABLE formulaire_reponse');
        $this->addSql('ALTER TABLE commande ADD total DOUBLE PRECISION NOT NULL, DROP totale, CHANGE panier_id panier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC38B217A7');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier ADD id_panier INT NOT NULL');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779BCF5E72D');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779365BF48');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }
}
