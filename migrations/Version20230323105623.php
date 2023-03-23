<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323105623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_book DROP FOREIGN KEY FK_48C89914D4C006C8');
        $this->addSql('DROP INDEX IDX_48C89914D4C006C8 ON borrow_book');
        $this->addSql('ALTER TABLE borrow_book CHANGE borrow_id book_id INT NOT NULL');
        $this->addSql('ALTER TABLE borrow_book ADD CONSTRAINT FK_48C8991416A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_48C8991416A2B381 ON borrow_book (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_book DROP FOREIGN KEY FK_48C8991416A2B381');
        $this->addSql('DROP INDEX IDX_48C8991416A2B381 ON borrow_book');
        $this->addSql('ALTER TABLE borrow_book CHANGE book_id borrow_id INT NOT NULL');
        $this->addSql('ALTER TABLE borrow_book ADD CONSTRAINT FK_48C89914D4C006C8 FOREIGN KEY (borrow_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_48C89914D4C006C8 ON borrow_book (borrow_id)');
    }
}
