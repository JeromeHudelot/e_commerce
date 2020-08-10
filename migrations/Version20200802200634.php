<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200802200634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DDD464ABC');
        $this->addSql('DROP INDEX IDX_6EEAA67DDD464ABC ON commande');
        $this->addSql('ALTER TABLE commande DROP commande_product_id');
        $this->addSql('ALTER TABLE commande_product CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADDD464ABC');
        $this->addSql('DROP INDEX IDX_D34A04ADDD464ABC ON product');
        $this->addSql('ALTER TABLE product DROP commande_product_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande ADD commande_product_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DDD464ABC FOREIGN KEY (commande_product_id) REFERENCES commande_product (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DDD464ABC ON commande (commande_product_id)');
        $this->addSql('ALTER TABLE commande_product CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD commande_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDD464ABC FOREIGN KEY (commande_product_id) REFERENCES commande_product (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADDD464ABC ON product (commande_product_id)');
    }
}
