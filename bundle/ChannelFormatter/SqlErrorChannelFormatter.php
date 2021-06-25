<?php namespace Atomino\Bundle\Debug\ChannelFormatter;

use Atomino\Bundle\Debug\ChannelFormatterInterface;
use Codedungeon\PHPCliColors\Color;

class SqlErrorChannelFormatter implements ChannelFormatterInterface {
	public function format(string $channel, mixed $payload, string $datetime): string {
		$text = Color::LIGHT_YELLOW . Color::BG_LIGHT_RED . Color::BOLD . " " . $channel . " " . Color::RESET . ' ';
		$text.= Color::LIGHT_RED_ALT.$payload["error"]."\n";
		$text.= Color::WHITE."sql: ".$payload["sql"];
		return $text;
	}
}
