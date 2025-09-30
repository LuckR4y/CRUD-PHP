<?php

class Juridica extends Model
{
    protected string $table = 'juridica';
    protected array $fillable = ['id', 'cnpj', 'inscEstadual','inscMunicipal','abertura', 'nomeFantasia', 'cnae'];
}
