<?php

require_once __DIR__ . '/../core/Model.php';

class Funcionario extends Model
{
    protected string $table = 'funcionario';
    protected array  $fillable = ['id', 'admissao', 'salario'];
}
