<?php

class Fatura extends Model
{
    protected string $table = 'fatura';
    protected array $fillable = ['id','numDoc', 'valor', 'vencimento','cancelamento', 'tipo', 'gerente_id'];
}
