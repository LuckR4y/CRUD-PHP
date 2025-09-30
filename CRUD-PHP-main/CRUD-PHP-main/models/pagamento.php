<?php

class Pagamento extends Model
{
    protected string $table = 'pagamento';
    protected array $fillable = ['id','valor', 'data','fatura_id'];
}
