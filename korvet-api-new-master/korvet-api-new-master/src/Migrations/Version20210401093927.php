<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Reference\MeasurementUnits;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401093927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='шт') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='шт.')");
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='пипет') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='пипетка')");
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='доз.') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='доза')");
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='доз.') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='доз')");
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='упак') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='уп.')");
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='упак') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='упак.')");
        $this->addSql("UPDATE product.product  SET measurement_units_id=(SELECT  id FROM reference.reference_measurement_units  WHERE name='амп') WHERE measurement_units_id=(SELECT id FROM reference.reference_measurement_units WHERE name='ампул')");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='шт.'");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='пипетка'");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='доза'");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='доз'");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='уп.'");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='упак.'");
        $this->addSql("DELETE  FROM reference.reference_measurement_units WHERE name='ампул'");



    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');



    }
}
