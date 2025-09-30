<?php

class Endereco extends Model
{
    protected string $table = 'endereco';
    protected array $fillable = ['id','logadouro', 'complemento', 'cep','bairro_id'];
}
