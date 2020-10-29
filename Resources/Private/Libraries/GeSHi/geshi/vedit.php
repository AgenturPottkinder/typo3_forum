<?php
/*************************************************************************************
 * vedit.php
 * --------
 * Author: Pauli Lindgren (pauli0212@yahoo.com)
 * Copyright: (c) 2009 Pauli Lindgren (http://koti.mbnet.fi/pkl/)
 * Release Version: 1.0.8.11
 * Date Started: 2009/12/16
 *
 * Vedit macro language language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2009/12/16 (1.0.8.11)
 *  -  First Release
 *
 * TODO (updated 2009/12/16)
 * -------------------------
 * - Add keyword groups 2, 3 and 4.
 *
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
    'LANG_NAME' => 'Vedit macro language',
    'COMMENT_SINGLE' => [1 => '//'],
    'COMMENT_MULTI' => [],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ['"', '\''],
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => [
        1 => [
            'break', 'breakout', 'break_out', 'continue', 'do', 'else', 'for',
            'goto', 'if', 'repeat', 'return', 'while'
            ]
        ],
    'SYMBOLS' => [
        1 => [
            '(', ')', '{', '}', '[', ']', '+', '-', '*', '/', '%',
            '=', '<', '>', '!', '^', '&', '|', '?', ':', ';', ','
            ]
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => false
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #b1b100;'
            ],
        'COMMENTS' => [
            1 => 'color: #666666; font-style: italic;',
            'MULTI' => 'color: #666666; font-style: italic;'
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;'
            ],
        'BRACKETS' => [
            0 => 'color: #009900;'
            ],
        'STRINGS' => [
            0 => 'color: #0000ff;'
            ],
        'NUMBERS' => [
            0 => 'color: #cc66cc;',
            ],
        'METHODS' => [
            0 => 'color: #004000;'
            ],
        'SYMBOLS' => [
            1 => 'color: #339933;'
            ],
        'REGEXPS' => [],
        'SCRIPT' => []
        ],
    'URLS' => [1 => ''],
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => [],
    'REGEXPS' => [],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [],
    'HIGHLIGHT_STRICT_BLOCK' => []
];
