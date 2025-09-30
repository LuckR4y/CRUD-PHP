<?php

class Pessoa extends Model
{
    protected string $table = 'pessoa';
    protected array $fillable = ['id','nome', 'telefone', 'celular'];
}
