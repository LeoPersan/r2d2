<?php

namespace Leopersan\R2d2\Types;

interface InterfaceType
{
    public function __construct(string $name);
    public function getName(): string;
    public function getType(): string;
    public function getCast(): string;
    public function getUse(): string;
    public function getField(): string;
    public function getLabel(): string;
    public function getPath(): string;
    public function getRule(): string;
    public function getUseRequest(): string;
    public function isArquivo(): bool;
}