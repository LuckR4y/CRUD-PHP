<?php

class Bairro extends Model
{
    protected string $table = 'bairro';
    protected array $fillable = ['id','nome', 'cidade_id'];
}
