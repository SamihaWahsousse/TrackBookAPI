<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323104116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_book ADD borrow_id INT NOT NULL');
        $this->addSql('ALTER TABLE borrow_book ADD CONSTRAINT FK_48C89914D4C006C8 FOREIGN KEY (borrow_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_48C89914D4C006C8 ON borrow_book (borrow_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_book DROP FOREIGN KEY FK_48C89914D4C006C8');
        $this->addSql('DROP INDEX IDX_48C89914D4C006C8 ON borrow_book');
        $this->addSql('ALTER TABLE borrow_book DROP borrow_id');
    }
}
