<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910232458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_919694F97294869C (article_id), INDEX IDX_919694F9BAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F97294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD illustration_id INT DEFAULT NULL, DROP tag');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E665926566C FOREIGN KEY (illustration_id) REFERENCES illustration (id)');
        $this->addSql('CREATE INDEX IDX_23A0E665926566C ON article (illustration_id)');
        $this->addSql('ALTER TABLE illustration DROP FOREIGN KEY FK_D67B9A427294869C');
        $this->addSql('DROP INDEX IDX_D67B9A427294869C ON illustration');
        $this->addSql('ALTER TABLE illustration DROP article_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F97294869C');
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F9BAD26311');
        $this->addSql('DROP TABLE article_tag');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E665926566C');
        $this->addSql('DROP INDEX IDX_23A0E665926566C ON article');
        $this->addSql('ALTER TABLE article ADD tag JSON DEFAULT NULL, DROP illustration_id');
        $this->addSql('ALTER TABLE illustration ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE illustration ADD CONSTRAINT FK_D67B9A427294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D67B9A427294869C ON illustration (article_id)');
    }
}
