<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Funcionario.php';
require_once __DIR__ . '/../models/Pessoa.php';

class FuncionarioController extends Controller
{
    public function index()
    {
        $pdo = Database::get();
        $sql = "SELECT f.id, f.admissao, f.salario, p.nome, p.telefone, p.celular FROM funcionario f
            LEFT JOIN pessoa p ON p.id = f.id ORDER BY f.id DESC";
        $this->json($pdo->query($sql)->fetchAll());
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $pdo = Database::get();
        $sql = "SELECT f.id, f.admissao, f.salario, p.nome, p.telefone, p.celular FROM funcionario f
            LEFT JOIN pessoa p ON p.id = f.id WHERE f.id = ?";
        $st = $pdo->prepare($sql);
        $st->execute([$id]);
        $r = $st->fetch();
        $this->json($r ?: ['error' => 'not found'], $r ? 200 : 404);
    }

    public function create()
    {
        $d = $this->input();
        $pdo = Database::get();
        $pdo->beginTransaction();
        try {
            $pid = (new Pessoa())->create($d);
            (new Funcionario())->create([
                'id' => $pid,
                'admissao' => $d['admissao'] ?? null,
                'salario' => $d['salario']  ?? null
            ]);
            $pdo->commit();
            $this->json(['id' => $pid], 201);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $d = $this->input();
        $pdo = Database::get();
        $pdo->beginTransaction();
        try {
            (new Pessoa())->updateById($id, $d);
            (new Funcionario())->updateById($id, [
                'admissao' => $d['admissao'] ?? null,
                'salario' => $d['salario']  ?? null
            ]);
            $pdo->commit();
            $this->json(['updated' => true]);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $pdo = Database::get();
        $pdo->beginTransaction();
        try {
            (new Funcionario())->deleteById($id);
            (new Pessoa())->deleteById($id);
            $pdo->commit();
            $this->json(['deleted' => true]);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
