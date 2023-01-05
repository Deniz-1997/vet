<?php

namespace App\Packages\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

/**
 * обертка для методов полнотекстового поиска в postgresql
 * @link https://www.postgresql.org/docs/11/textsearch-tables.html
*/
class FTS extends FunctionNode
{
    public $query;

    /**
     * @var array
     */
    public array $values = [];

    /**
     * @param Parser $parser
     * @throws QueryException
     */
    public function parse(Parser $parser)
    {

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->query = $parser->StringPrimary();
        $lexer = $parser->getLexer();
        while ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->values[] = $parser->StringExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        $params = [];
        foreach ($this->values as $value) {
            $params[] = 'coalesce(' . $value->dispatch($sqlWalker) . ', \' \')';
        }

        return 'to_tsvector(\'russian\', ' . implode(' || \' \' || ', $params) . ') @@ plainto_tsquery( \'russian\','
            .  $this->query->dispatch($sqlWalker) . ')';
    }
}
