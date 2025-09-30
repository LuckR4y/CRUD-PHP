<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Fisica.php';
require_once __DIR__ . '/../models/Pessoa.php';

class FisicaController extends Controller
{
    public function index()
    {
        try {
            $pdo = Database::get();
            $sql = "SELECT f.id, f.sexo, f.genero, f.raca, p.nome FROM fisica f
                 LEFT JOIN pessoa p ON p.id = f.id ORDER BY f.id DESC";
            $rows = $pdo->query($sql)->fetchAll();
            if (!$rows) {
                $rows = $pdo->query("SELECT id, sexo, genero, raca FROM fisica ORDER BY id DESC")->fetchAll();
            }
            $this->json($rows);
        } catch (Throwable $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show()
    {
        try {
            $id  = (int)($_GET['id'] ?? 0);
            $pdo = Database::get();
            $sql = "SELECT f.id, f.sexo, f.genero, f.raca, p.nome FROM fisica f
                 LEFT JOIN pessoa p ON p.id = f.id WHERE f.id = ?";
            $st = $pdo->prepare($sql);
            $st->execute([$id]);
            $r = $st->fetch();
            $this->json($r ?: ['error' => 'not found'], $r ? 200 : 404);
        } catch (Throwable $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        $d = $this->input();
        $pdo = Database::get();
        $pdo->beginTransaction();
        try {
            $pid = (new Pessoa())->create($d);
            (new Fisica())->create([
                'id' => $pid,
                'sexo' => $d['sexo'] ?? 'M',
                'genero' => $d['genero'] ?? 'Masculino',
                'raca' => $d['raca'] ?? 'Não Informar'
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
            (new Fisica())->updateById($id, [
                'sexo' => $d['sexo'] ?? null,
                'genero' => $d['genero'] ?? null,
                'raca' => $d['raca'] ?? null
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
            (new Fisica())->deleteById($id);
            (new Pessoa())->deleteById($id);
            $pdo->commit();
            $this->json(['deleted' => true]);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
