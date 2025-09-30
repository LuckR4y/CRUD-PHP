<?php

class Professor extends Model
{
    protected string $table = 'professor';
    protected array $fillable = ['id', 'inicio', 'formacao'];
}
