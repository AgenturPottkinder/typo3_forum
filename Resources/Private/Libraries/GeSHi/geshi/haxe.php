<?php
/*************************************************************************************
 * haxe.php
 * --------
 * Author: Andy Li (andy@onthewings.net)
 *         John Liao (colorhook@gmail.com)
 * Copyright: (c) 2012 onthewings (http://www.onthewings.net/)
 *                2010 colorhook (http://colorhook.com/)
 * Release Version: 1.0.8.11
 * Date Started: 2010/10/05
 *
 * Haxe language file for GeSHi.
 * Haxe version: 2.10
 *************************************************************************************
 *
 *   This file is part of GeSHi.
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
    'LANG_NAME' => 'Haxe',
    'COMMENT_SINGLE' => [1 => '//'],
    'COMMENT_MULTI' => ['/*' => '*/'],
    'COMMENT_REGEXP' => [
        //Import and Package directives (Basic Support only)
        2 => '/(?:(?<=import[\\n\\s])|(?<=using[\\n\\s])|(?<=package[\\n\\s]))[\\n\\s]*([a-zA-Z0-9_]+\\.)*([a-zA-Z0-9_]+|\*)(?=[\n\s;])/i',
        // Haxe comments
        3 => '#/\*\*(?![\*\/]).*\*/#sU',
        ],
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => ["'", '"'],
    'ESCAPE_CHAR' => '\\',
    'KEYWORDS' => [
        1 => [
            //http://haxe.org/ref/keywords
            'break', 'callback', 'case', 'cast', 'catch', 'class', 'continue', 'default', 'do', 'dynamic',
            'else', 'enum', 'extends', 'extern', /*'false',*/ 'for', 'function', 'here', 'if',
            'implements', 'import', 'in', 'inline', 'interface', 'never', 'new', /*'null',*/ 'override',
            'package', 'private', 'public', 'return', 'static', 'super', 'switch', 'this', 'throw',
            'trace', /*'true',*/ 'try', 'typedef', 'untyped', 'using', 'var', 'while',
            'macro', '$type',
            ],
        2 => [
            //primitive values
            'null', 'false', 'true',
            ],
        3 => [
            //global types
            'Array', 'ArrayAccess', /*'Bool',*/ 'Class', 'Date', 'DateTools', 'Dynamic',
            'EReg', 'Enum', 'EnumValue', /*'Float',*/ 'Hash', /*'Int',*/ 'IntHash', 'IntIter',
            'Iterable', 'Iterator', 'Lambda', 'List', 'Math', 'Null', 'Reflect', 'Std',
            /*'String',*/ 'StringBuf', 'StringTools', 'Sys', 'Type', /*'UInt',*/ 'ValueType',
            /*'Void',*/ 'Xml', 'XmlType',
            ],
        4 => [
            //primitive types
            'Void', 'Bool', 'Int', 'Float', 'UInt', 'String',
            ],
        5 => [
            //compiler switches
            '#if', '#elseif', '#else', '#end', '#error',
            ],
        ],
    'SYMBOLS' => [
        //http://haxe.org/manual/operators
        '++', '--',
        '%',
        '*', '/',
        '+', '-',
        '<<', '>>', '>>>',
        '|', '&', '^',
        '==', '!=', '>', '>=', '<', '<=',
        '...',
        '&&',
        '||',
        '?', ':',
        '=', '+=', '-=', '/=', '*=', '<<=', '>>=', '>>>=', '|=', '&=', '^=',
        '(', ')', '[', ']', '{', '}', ';',
        ],
    'CASE_SENSITIVE' => [
        GESHI_COMMENTS => false,
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true,
        ],
    'STYLES' => [
        'KEYWORDS' => [
            1 => 'color: #6699cc; font-weight: bold;',
            2 => 'color: #000066; font-weight: bold;',
            3 => 'color: #03F; ',
            4 => 'color: #000033; font-weight: bold;',
            5 => 'color: #330000; font-weight: bold;',
            ],
        'COMMENTS' => [
            1 => 'color: #666666; font-style: italic;',
            2 => 'color: #006699;',
            3 => 'color: #008000; font-style: italic; font-weight: bold;',
            3 => 'color: #008000; font-style: italic; font-weight: bold;',
            'MULTI' => 'color: #666666; font-style: italic;',
            ],
        'ESCAPE_CHAR' => [
            0 => 'color: #000099; font-weight: bold;',
            ],
        'BRACKETS' => [
            0 => 'color: #000000;',
            ],
        'STRINGS' => [
            0 => 'color: #FF0000;',
            ],
        'NUMBERS' => [
            0 => 'color: #cc66cc;',
            ],
        'METHODS' => [
            1 => 'color: #006633;',
            2 => 'color: #006633;',
            ],
        'SYMBOLS' => [
            0 => 'color: #339933;',
            ],
        'SCRIPT' => [
            ],
        'REGEXPS' => [
            ]
        ],
    'URLS' => [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        ],
    'OOLANG' => true,
    'OBJECT_SPLITTERS' => [
        1 => '.',
        ],
    'REGEXPS' => [
        ],
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => [
        ],
    'HIGHLIGHT_STRICT_BLOCK' => [
        ],
];
