<?php namespace Atomino\Bundle\Debug;

use Atomino\Core\Application;
use Codedungeon\PHPCliColors\Color;
use Monolog\Formatter\NormalizerFormatter;


class CliDebugFormatter extends NormalizerFormatter {

	/**
	 * CliChannelFormatter constructor.
	 * @param ChannelFormatterInterface[] $formatters
	 */
	public function __construct(private array $formatters) {
		parent::__construct();
	}

	private function defaultFormatter(string $channel, mixed $payload, string $datetime): string {
		return Color::WHITE.Color::BG_DARK_GRAY." unknown channel ".Color::LIGHT_WHITE_ALT.Color::BG_BLACK.Color::BOLD.' '.$channel.Color::RESET;
	}

	public function format(array $record): string {
		return
			Color::GRAY.Application::instance()->id." ".Color::RESET.
			(array_key_exists($record['channel'], $this->formatters)
			? $this->formatters[$record['channel']]->format($record['channel'], $record["context"]["payload"], $record['datetime']).Color::RESET
			: $this->defaultFormatter($record['channel'], $record["context"]["payload"], $record['datetime']).Color::RESET);
	}

}