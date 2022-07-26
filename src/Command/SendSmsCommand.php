<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

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

    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $receiverPhoneNumber = $input->getArgument(self::ARGUMENT_RECEIVER);
        $message = $input->getArgument(self::ARGUMENT_MESSAGE);

        $notification = new Notification($message, ['sms/twilio']);

        $recipient = new Recipient(
            '',
            $receiverPhoneNumber
        );

        $this->notifier->send($notification, $recipient);

        return Command::SUCCESS;
    }
}
