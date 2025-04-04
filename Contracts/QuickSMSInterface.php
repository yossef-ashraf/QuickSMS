<?php

namespace QuickSMS\Contracts;

interface QuickSMSInterface
{
    public function send(array $params): array;
    public function validate(array $params): bool;
}