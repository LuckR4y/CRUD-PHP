<?php

class Disciplina extends Model
{
    protected string $table = 'disciplina';
    protected array $fillable = ['id','nome','cargaHoraria'];
}
