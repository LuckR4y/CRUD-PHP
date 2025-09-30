<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Gerente.php';
require_once __DIR__ . '/../models/Pessoa.php';

class GerenteController extends Controller
{
    public function index()
    {
        $pdo = Database::get();
        $sql = "SELECT g.id, p.nome, p.telefone, p.celular FROM gerente g
            LEFT JOIN pessoa p ON p.id = g.id ORDER BY g.id DESC";
        $this->json($pdo->query($sql)->fetchAll());
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $pdo = Database::get();
        $sql = "SELECT g.id, p.nome, p.telefone, p.celular FROM gerente g
            LEFT JOIN pessoa p ON p.id = g.id WHERE g.id = ?";
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
            (new Gerente())->create(['id' => $pid]);
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
            (new Gerente())->deleteById($id);
            (new Pessoa())->deleteById($id);
            $pdo->commit();
            $this->json(['deleted' => true]);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
