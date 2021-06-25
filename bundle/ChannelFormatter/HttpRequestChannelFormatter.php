<?php namespace Atomino\Bundle\Debug\ChannelFormatter;

use Codedungeon\PHPCliColors\Color;
use Atomino\Bundle\Debug\ChannelFormatterInterface;
use Symfony\Component\HttpFoundation\Request;

class HttpRequestChannelFormatter implements ChannelFormatterInterface {
	/**
	 * @param string $channel
	 * @param Request $payload
	 * @param string $datetime
	 * @return string
	 */
	public function format(string $channel, mixed $payload, string $datetime): string {
		$text = Color::BLACK . Color::BG_LIGHT_MAGENTA . Color::BOLD . " " . $channel . " " . Color::RESET ;
		$text.= Color::LIGHT_WHITE_ALT.Color::BG_MAGENTA.' '.$payload->getMethod().' '.Color::RESET.' ';
		$text.= Color::MAGENTA.$payload->getHost().' ';
		$text.= Color::LIGHT_MAGENTA_ALT.$payload->getPathInfo();
		$text.= $payload->getQueryString() ? Color::MAGENTA.' ?'.urldecode($payload->getQueryString()) : '';
		return $text;
	}
}
