<?php


namespace App\Packages\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

class TsQuery extends FunctionNode
{
    public $rowName;

    public $value;

    /**
     * @param Parser $parser
     * @throws QueryException
     */
    public function parse(Parser $parser)
    {

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->rowName = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);
        $this->value = $parser->StringExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        return '(' . $this->rowName->dispatch($sqlWalker)
            . ' @@ plainto_tsquery( \'russian\','
            .  $this->value->dispatch($sqlWalker) . '))';
    }
}
