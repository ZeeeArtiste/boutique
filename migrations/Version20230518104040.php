<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518104040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags_product DROP FOREIGN KEY FK_F5F6EFBC4584665A');
        $this->addSql('ALTER TABLE tags_product DROP FOREIGN KEY FK_F5F6EFBC8D7B4FB4');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_product');
        $this->addSql('ALTER TABLE product DROP tags, DROP tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tags_product (tags_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_F5F6EFBC8D7B4FB4 (tags_id), INDEX IDX_F5F6EFBC4584665A (product_id), PRIMARY KEY(tags_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tags_product ADD CONSTRAINT FK_F5F6EFBC4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_product ADD CONSTRAINT FK_F5F6EFBC8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD tags LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD tag VARCHAR(255) DEFAULT NULL');
    }
}
