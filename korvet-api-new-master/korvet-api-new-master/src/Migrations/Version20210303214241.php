<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303214241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $nums = [
            '20200102093020',
            '20200108080550',
            '20200109155508',
            '20200124082744',
            '20200115095001',
            '20200124082744',
            '20200124104105',
            '20200211105949',
            '20200212094755',
            '20200306125941',
            '20200314135028',
            '20200317194042',
            '20200324185208',
            '20200328170750',
            '20200406145658',
            '20200407213043',
            '20200408150125',
            '20200410123410',
            '20200415102441',
            '20200423153509',
            '20200423214320',
            '20200424090758',
            '20200607213649',
            '20201023152526',
            '20201023161553',
            '20201024193132',
            '20201029183259',
            '20201103142330',
            '20201108092131',
            '20201118080414',
            '20201120095838',
            '20201125100340',
            '20210123124156',
            '20210125061156',
            '20210125062642',
            '20210126100438',
            '20210127070825',
            '20210127092508',
            '20210127101731',
            '20210127114856',
            '20210128123400',
            '20210203180451',
            '20210204071732',
            '20210209195553',
            '20210211175359',
            '20210211190632',
            '20210214163657',
            '20210215192513',
            '20210219084118',
            '20210301175837',
            '20210302113818',
            '20210302130414',
            '20210302131007',
            '20210302154313',
            '20210302161156',
            '20210303071345',
            '20210303184343',
            '20210303214241',
            '20211002100340',
            '20211202155825',
            '20211202162134',
        ];

        $versions = '';

        foreach ($nums as $i => $n) {
            $versions .= "'$n'";

            if ($i < count($nums) - 1) $versions .= ',';
        }

        $this->addSql("DELETE FROM migration_versions WHERE version NOT IN($versions)");
    }

    public function down(Schema $schema): void
    {
    }
}
