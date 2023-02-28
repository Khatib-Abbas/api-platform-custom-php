<?php

namespace App\Enum;

enum SearchFilterMethodSearch: string
{
    case SEARCH_FILTER_METHOD_SEARCH_PARTIAL  = 'partial';
    case SEARCH_FILTER_METHOD_SEARCH_START  = 'start';
    case SEARCH_FILTER_METHOD_SEARCH_END = 'end';
}
