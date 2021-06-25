<?php namespace Atomino\Bundle\Debug;

interface ChannelFormatterInterface {
	public function format(string $channel, mixed $payload, string $datetime): string;
}