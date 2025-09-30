<?php

require_once __DIR__ . '/Database.php';

abstract class Model extends Database
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC";
        $q = $this->pdo->query($sql);
        return $q->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $q = $this->pdo->prepare($sql);
        $q->execute([$id]);
        $r = $q->fetch();
        return $r ?: null;
    }

    public function create(array $data): int
    {
        $cols = [];
        $vals = [];
        $qs = [];
        foreach ($this->fillable as $f) {
            if (array_key_exists($f, $data)) {
                $cols[] = $f;
                $vals[] = $data[$f];
                $qs[] = '?';
            }
        }
        if (!$cols) {
            throw new InvalidArgumentException("Nenhum campo permitido em \$fillable para inserir em {$this->table}");
        }
        $sql = "INSERT INTO {$this->table} (" . implode(',', $cols) . ") VALUES (" . implode(',', $qs) . ")";
        $st  = $this->pdo->prepare($sql);
        $st->execute($vals);
        if (in_array($this->primaryKey, $this->fillable, true) && isset($data[$this->primaryKey])) {
            return (int)$data[$this->primaryKey];
        }
        return (int)$this->pdo->lastInsertId();
    }

    public function updateById(int $id, array $data): bool
    {
        $set = [];
        $vals = [];
        foreach ($this->fillable as $f) {
            if (array_key_exists($f, $data)) {
                $set[] = "{$f} = ?";
                $vals[] = $data[$f];
            }
        }
        if (!$set) {
            return false;
        }
        $vals[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(',', $set) . " WHERE {$this->primaryKey} = ?";
        $st = $this->pdo->prepare($sql);
        return $st->execute($vals);
    }

    public function deleteById(int $id): bool
    {
        $st = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $st->execute([$id]);
    }
}
