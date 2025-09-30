<?php

require_once __DIR__ . '/../core/Model.php';

class Fisica extends Model
{
    protected string $table = 'fisica';
    protected array  $fillable = ['id','sexo','genero','raca'];
}
