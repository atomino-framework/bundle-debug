<?php namespace Atomino\Bundle\Debug\ChannelFormatter;

use Codedungeon\PHPCliColors\Color;
use Atomino\Bundle\Debug\ChannelFormatterInterface;

class CliRequestChannelFormatter implements ChannelFormatterInterface {
	public function format(string $channel, mixed $payload, string $datetime): string {
		$text = Color::BLACK . Color::BG_LIGHT_MAGENTA . Color::BOLD . " " . $channel . " " . Color::RESET . ' ';
		$text.= Color::LIGHT_MAGENTA_ALT.join(' ',$payload);
		return $text;
	}
}
