<?php
/*************************************************************************************
 * dcpu16.php
 * -------
 * Author: Benny Baumann (BenBE@omorphia.de)
 * Copyright: (c) 2007-2012 Benny Baumann (http://geshi.org/)
 * Release Version: 1.0.8.11
 * Date Started: 2012/04/12
 *
 * DCPU/16 Assembly language file for GeSHi.
 * Syntax definition based on http://0x10c.com/doc/dcpu-16.txt
 *
 * CHANGES
 * -------
 * 2012/04/12 (1.0.0)
 *   -  First Release
 *
 * TODO (updated 2012/04/12)
 * -------------------------
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = [
    'LANG_NAME' => 'DCPU-16 Assembly',
    'COMMENT_SINGLE' => [1 => ';'],
    'COMMENT_MULTI' => [],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '',
    'NUMBERS' => GESHI_NUMBER_INT_BASIC | GESHI_NUMBER_HEX_PREFIX,
    'KEYWORDS' => [
        /*CPU*/
        1 => [
            'set', 'add', 'sub', 'mul', 'div', 'mod', 'shl', 'shr', 'and', 'bor', 'xor',
            'ife', 'ifn', 'ifg', 'ifb',
            'jsr'
            ],
        /*registers*/
        2 => [
            'a', 'b', 'c', 'x', 'y', 'z', 'i', 'j',
            'pc', 'sp', 'o',
            'pop', 'peek', 'push' //Special cases with DCPU-16
            ],
        ],
    'SYMBOLS' => [
        '[', ']', '+', '-', ','
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #000088; font-weight:bold;',
            2 => 'color: #0000ff;'
            ],
        'COMMENTS' => [
            1 => 'color: #adadad; font-style: italic;',
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;'
            ],
        'BRACKETS' => [
            0 => 'color: #000088;'
            ],
        'STRINGS' => [
            0 => 'color: #7f007f;'
            ],
        'NUMBERS' => [
            0 => 'color: #880000;'
            ],
        'METHODS' => [
            ],
        'SYMBOLS' => [
            0 => 'color: #008000;'
            ],
        'REGEXPS' => [
            2 => 'color: #993333;'
            ],
        'SCRIPT' => [
            ]
        ],
    'URLS' => [
        1 => 'http://0x10c.com/doc/dcpu-16.txt',
        2 => ''
        ],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [
        ],
    'REGEXPS' => [
        //Hex numbers
        //0 => '0[0-9a-fA-F]{1,32}[hH]',
        //Binary numbers
        //1 => '\%[01]{1,64}|[01]{1,64}[bB]?(?![^<]*>)',
        //Labels
        2 => '^:[_a-zA-Z][_a-zA-Z0-9]?(?=\s|$)'
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        ],
    'TAB_WIDTH' => 4,
    'PARSER_CONTROL' => [
        'KEYWORDS' => [
            'DISALLOWED_BEFORE' => "(?<![a-zA-Z0-9\$_\|\#\/])",
            'DISALLOWED_AFTER' => "(?![a-zA-Z0-9_\|%\\-])"
            ]
        ]
];
