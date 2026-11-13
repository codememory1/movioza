<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Messenger\Serializer;

use JsonException;
use Movioza\Shared\Infrastructure\Messenger\DebeziumMessageRegistry;
use OutOfBoundsException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

final class DebeziumMessageSerializer extends Serializer
{
    public function __construct(
        private readonly DebeziumMessageRegistry $messageRegistry,
        ?SymfonySerializerInterface $serializer = null,
        string $format = 'json',
        array $context = []
    ) {
        parent::__construct($serializer, $format, $context);
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        try {
            $body = json_decode(
                $encodedEnvelope['body'],
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new MessageDecodingFailedException(
                'The Debezium message body must contain valid JSON.',
                previous: $e
            );
        }

        $source = is_array($body) ? ($body['source'] ?? null) : null;

        if (!is_array($source)) {
            throw new MessageDecodingFailedException('The Debezium message must contain a "source" object.');
        }

        foreach (['schema', 'table'] as $field) {
            $value = $source[$field] ?? null;

            if (!is_string($value) || trim($value) === '') {
                throw new MessageDecodingFailedException(sprintf(
                    'The Debezium message field "source.%s" must be a non-empty string.',
                    $field
                ));
            }
        }

        try {
            $messageClass = $this->messageRegistry->resolve($source['schema'], $source['table']);
        } catch (OutOfBoundsException $e) {
            throw new MessageDecodingFailedException($e->getMessage(), previous: $e);
        }

        $encodedEnvelope['headers']['type'] = $messageClass;

        return parent::decode($encodedEnvelope);
    }
}
