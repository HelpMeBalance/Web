<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223083105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formulaire (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE formulaire_question (formulaire_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_A7BCB6F55053569B (formulaire_id), INDEX IDX_A7BCB6F51E27F6BF (question_id), PRIMARY KEY(formulaire_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE formulaire_reponse (formulaire_id INT NOT NULL, reponse_id INT NOT NULL, INDEX IDX_1542391C5053569B (formulaire_id), INDEX IDX_1542391CCF18BB82 (reponse_id), PRIMARY KEY(formulaire_id, reponse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE formulaire_question ADD CONSTRAINT FK_A7BCB6F55053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_question ADD CONSTRAINT FK_A7BCB6F51E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_reponse ADD CONSTRAINT FK_1542391C5053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_reponse ADD CONSTRAINT FK_1542391CCF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE formulaire');
        $this->addSql('DROP TABLE formulaire_question');
        $this->addSql('DROP TABLE formulaire_reponse');
        $this->addSql('ALTER TABLE formulaire_question DROP FOREIGN KEY FK_A7BCB6F55053569B');
        $this->addSql('ALTER TABLE formulaire_question DROP FOREIGN KEY FK_A7BCB6F51E27F6BF');
        $this->addSql('ALTER TABLE formulaire_reponse DROP FOREIGN KEY FK_1542391C5053569B');
        $this->addSql('ALTER TABLE formulaire_reponse DROP FOREIGN KEY FK_1542391CCF18BB82');
    }
}
