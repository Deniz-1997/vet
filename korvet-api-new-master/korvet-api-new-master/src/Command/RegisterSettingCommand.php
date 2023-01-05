<?php
namespace App\Command;


use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class RegisterSettingCommand extends Command
{
    protected static $defaultName = 'app:setting:save';

    /** @var SettingsRepository */
    private SettingsRepository $settingsRepository;

    /**
     * RegisterSettingCommand constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;

        parent::__construct();
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Add new setting or update exists setting')
            ->addArgument('setting', InputArgument::REQUIRED, 'Setting code')
            ->addArgument('value', InputArgument::REQUIRED, 'value');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $setting = $input->getArgument('setting');
        $value = $input->getArgument('value');

        if ($settings = $this->settingsRepository->findOneBy(['key' => $setting])) {
            $output->writeln(sprintf('Настройка %s уже существует [%s, %s]', $setting, $settings->getId(), $settings->getValue()));
            
            if (!$helper->ask($input, $output, new ConfirmationQuestion('Обновить существующую настройку? [Y/N]', true))) {
                return Command::SUCCESS;
            }

            $settings->setValue($value);
        } else {
            $output->writeln('Создаю новую настройку...');

            $settings = new Settings();
            $settings->setKey($setting);
            $settings->setValue($value);
        }

        $this->settingsRepository->save($settings);
        return Command::SUCCESS;
    }
}
