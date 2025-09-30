<?php

class Aluno extends Model
{
    protected string $table = 'aluno';
    protected array $fillable = ['id', 'matricula', 'turma_id'];

    public function allJoin(): array
    {
        $sql = "SELECT a.id, a.matricula, a.turma_id, p.nome, p.telefone, p.celular FROM aluno a JOIN pessoa p ON p.id=a.id
        ORDER BY a.id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function findJoin(int $id): ?array
    {
        $st = $this->pdo->prepare("SELECT a.id, a.matricula, a.turma_id, p.nome, p.telefone, p.celular FROM aluno a
        JOIN pessoa p ON p.id=a.id WHERE a.id=?");
        $st->execute([$id]);
        $r = $st->fetch();
        return $r ?: null;
    }
}
