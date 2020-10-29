<?php
/*************************************************************************************
 * coffeescript.php
 * ----------
 * Author: Trevor Burnham (trevorburnham@gmail.com)
 * Copyright: (c) 2010 Trevor Burnham (http://iterative.ly)
 * Release Version: 1.0.8.11
 * Date Started: 2010/06/08
 *
 * CoffeeScript language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2010/06/08 (1.0.8.9)
 *  -  First Release
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
    'LANG_NAME' => 'CoffeeScript',
    'COMMENT_SINGLE' => [1 => '#'],
    'COMMENT_MULTI' => ['###' => '###'],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    //Longest quotemarks ALWAYS first
    'QUOTEMARKS' => ['"""', "'''", '"', "'"],
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => [

        /*
        ** Set 1: control keywords
        */
        1 => [
            'break', 'by', 'catch', 'continue', 'else', 'finally', 'for', 'in', 'of', 'if',
            'return', 'switch', 'then', 'throw', 'try', 'unless', 'when', 'while', 'until'
            ],

        /*
        ** Set 2: logic keywords
        */
        2 => [
            'and', 'or', 'is', 'isnt', 'not'
            ],

        /*
        ** Set 3: other keywords
        */
        3 => [
            'instanceof', 'new', 'delete', 'typeof',
            'class', 'super', 'this', 'extends'
            ],

        /*
        ** Set 4: constants
        */
        4 => [
            'true', 'false', 'on', 'off', 'yes', 'no',
            'Infinity', 'NaN', 'undefined', 'null'
            ]
        ],
    'SYMBOLS' => [
            '(', ')', '[', ']', '{', '}', '*', '&', '|', '%', '!', ',', ';', '<', '>', '?', '`',
            '+', '-', '*', '/', '->', '=>', '<<', '>>', '@', ':', '^'
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true,
        4 => true
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #ff7700;font-weight:bold;',
            2 => 'color: #008000;',
            3 => 'color: #dc143c;',
            4 => 'color: #0000cd;'
            ],
        'COMMENTS' => [
            1 => 'color: #808080; font-style: italic;',
            'MULTI' => 'color: #808080; font-style: italic;'
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;'
            ],
        'BRACKETS' => [
            0 => 'color: black;'
            ],
        'STRINGS' => [
            0 => 'color: #483d8b;'
            ],
        'NUMBERS' => [
            0 => 'color: #ff4500;'
            ],
        'METHODS' => [
            1 => 'color: black;'
            ],
        'SYMBOLS' => [
            0 => 'color: #66cc66;'
            ],
        'REGEXPS' => [
            ],
        'SCRIPT' => [
            ]
        ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
        4 => ''
        ],
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => [
        1 => '.'
        ],
    'REGEXPS' => [
        ],
    'STRICT_MODE_APPLIES' => GESHI_MAYBE,
    'SCRIPT_DELIMITERS' => [
        0 => [
            '<script type="text/coffeescript">' => '</script>'
            ]
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        0 => true
        ]
];
