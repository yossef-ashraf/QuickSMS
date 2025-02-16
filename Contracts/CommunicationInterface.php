<?php

namespace Communication\Contracts;

interface CommunicationInterface
{
    public function send(array $params): array;
    public function validate(array $params): bool;
}