<?php namespace Atomino\Bundle\Debug\ChannelFormatter;

use Atomino\Bundle\Debug\ChannelFormatterInterface;
use Codedungeon\PHPCliColors\Color;

class TraceChannelFormatter implements ChannelFormatterInterface {
	public function format(string $channel, mixed $payload, string $datetime): string {
		$text = Color::LIGHT_YELLOW . Color::BG_LIGHT_RED . Color::BOLD . " " . $channel . " " . Color::RESET . ' ';
		foreach ($payload['trace'] as $trace) {
			$text .= Color::LIGHT_YELLOW . ($trace["file"]??'unknown') . COLOR::YELLOW . ' @ ' . ($trace['line']??'unknown') . "\n";
			$text .= Color::LIGHT_RED_ALT . Color::BOLD . '↘ ' . (array_key_exists('class', $trace) ? $trace['class'] . ' ' : 'unknown ') . (array_key_exists('type', $trace) ? $trace['type'] . ' ' : 'unknown ') . Color::UN_BOLD . $trace['function'] . "\n";
		}
		return $text;
	}
}