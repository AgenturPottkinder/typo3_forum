<?php
/*************************************************************************************
 * parasail.php
 * -------
 * Author: T. Taft (taft@adacore.com)
 * Copyright: (c) 2012 AdaCore (http://adacore.com/)
 * Release Version: 1.0.8.11
 * Date Started: 2012/08/02
 *
 * ParaSail language file for GeSHi.
 *
 * Words are from SciTe configuration file
 *
 * CHANGES
 * -------
 * 2012/08/02 (1.0.0)
 *   -  First Release
 *
 * TODO (updated 2012/08/02)
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
    'LANG_NAME' => 'ParaSail',
    'COMMENT_SINGLE' => [1 => '//'],
    'COMMENT_MULTI' => ['{' => '}'],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ['"'],
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => [
        1 => [
            'all', 'block', 'case', 'continue', 'each',
            'else', 'elsif', 'exit', 'for',
            'forward', 'if', 'loop', 'return', 'reverse', 'some',
            'then', 'until', 'while', 'with'
            ],
        2 => [
            'abs', 'and', 'in', 'mod', 'not', 'null', 'or', 'rem', 'xor'
            ],
        3 => [
            'abstract', 'class',
            'concurrent', 'const',
            'end', 'extends', 'exports',
            'func', 'global', 'implements', 'import',
            'interface', 'is', 'lambda', 'locked',
            'new', 'of', 'op', 'optional',
            'private', 'queued', 'ref',
            'separate', 'type', 'var',
            ]
        ],
    'SYMBOLS' => [
        '(', ')', '[', ']', '<', '>'
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #00007f;',
            2 => 'color: #0000ff;',
            3 => 'color: #46aa03; font-weight:bold;',
            ],
        'BRACKETS' => [
            0 => 'color: #66cc66;'
            ],
        'COMMENTS' => [
            1 => 'color: #adadad; font-style: italic;',
            'MULTI' => 'color: #808080; font-style: italic;'
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;'
            ],
        'BRACKETS' => [
            0 => 'color: #66cc66;'
            ],
        'STRINGS' => [
            0 => 'color: #7f007f;'
            ],
        'NUMBERS' => [
            0 => 'color: #ff0000;'
            ],
        'METHODS' => [
            1 => 'color: #202020;'
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
        3 => ''
        ],
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => [
        1 => '.'
        ],
    'REGEXPS' => [
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        ]
];
