<?php

require_once __DIR__ . '/../core/Model.php';

class Gerente extends Model
{
    protected string $table = 'gerente';
    protected array  $fillable = ['id'];
}
