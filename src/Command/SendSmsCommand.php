<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

/**
 * Send a SMS via the twilio service.
 *
 * @author Jonah Böther <mail@jbtcd.me>
 */
#[AsCommand(
    name: 'app:send-sms',
    description: 'Send a SMS via the twilio service.'
)]
class SendSmsCommand extends Command
{
    private const ARGUMENT_RECEIVER = 'receiver';
    private const ARGUMENT_MESSAGE = 'message';

    private TexterInterface $texter;

    public function __construct(TexterInterface $texter)
    {
        $this->texter = $texter;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            self::ARGUMENT_RECEIVER,
            InputArgument::REQUIRED,
            'The phone number which receives the message'
        );

        $this->addArgument(
            self::ARGUMENT_MESSAGE,
            InputArgument::OPTIONAL,
            'The message that be send via sms',
            'This is an example message!'
        );

        $this->setHelp(<<<EOF
The <info>%command.name%</info> command send a SMS to a given phone number.

You must provide the phone number;

  <info>php %command.full_name% phone-number</info>

You can also define the message which will be sent:

  <info>php %command.full_name% phone-number 'The SMS text'</info>

EOF
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $receiverPhoneNumber = $input->getArgument(self::ARGUMENT_RECEIVER);
        $message = $input->getArgument(self::ARGUMENT_MESSAGE);

        $smsMessage = new SmsMessage(
            $receiverPhoneNumber,
            $message
        );

        $this->texter->send($smsMessage);

        return Command::SUCCESS;
    }
}
