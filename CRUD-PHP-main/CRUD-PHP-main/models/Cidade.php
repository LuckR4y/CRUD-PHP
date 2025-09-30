<?php

class Cidade extends Model
{
    protected string $table = 'cidade';
    protected array $fillable = ['id','nome', 'uf_id'];
}
