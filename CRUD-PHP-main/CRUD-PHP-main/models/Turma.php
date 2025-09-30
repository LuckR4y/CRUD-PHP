<?php

class Turma extends Model
{
    protected string $table = 'turma';
    protected array $fillable = ['id','horario', 'cargaHoraria', 'curso_id'];
}
