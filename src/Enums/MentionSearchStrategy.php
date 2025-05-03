<?php

namespace Awcodes\Richie\Enums;

enum MentionSearchStrategy: string
{
    case StartsWith = 'starts_with';

    case Tokenized = 'tokenized';
}
