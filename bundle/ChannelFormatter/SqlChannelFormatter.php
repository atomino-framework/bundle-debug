<?php namespace Atomino\Bundle\Debug\ChannelFormatter;

use Codedungeon\PHPCliColors\Color;
use Atomino\Bundle\Debug\ChannelFormatterInterface;

class SqlChannelFormatter implements ChannelFormatterInterface {
	public function format(string $channel, mixed $payload, string $datetime): string {
		$text = Color::BLACK . Color::BG_LIGHT_YELLOW . Color::BOLD . " " . $channel . " " . Color::RESET . ' ';
		$text.= $payload;
		return $text;
	}
}
