<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Professor.php';
require_once __DIR__ . '/../models/Pessoa.php';


class ProfessorController extends Controller
{
    public function index()
    {
        $sql = "SELECT pr.id, pr.inicio, pr.formacao, p.nome, p.telefone, p.celular FROM professor pr JOIN pessoa p
        ON p.id=pr.id ORDER BY pr.id DESC";
        $m = new Professor();
        $this->json($m->pdo->query($sql)->fetchAll());
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $sql = "SELECT pr.id, pr.inicio, pr.formacao, p.nome, p.telefone, p.celular FROM professor pr JOIN pessoa p
        ON p.id=pr.id WHERE pr.id=?";
        $m = new Professor();
        $st = $m->pdo->prepare($sql);
        $st->execute([$id]);
        $r = $st->fetch();
        $this->json($r ?: ['error' => 'not found'], $r ? 200 : 404);
    }

    public function create()
    {
        $d = $this->input();
        $m = new Professor();
        $m->pdo->beginTransaction();
        try {
            $pid = (new Pessoa())->create($d);
            $m->create(['id' => $pid,'inicio' => $d['inicio'] ?? null,'formacao' => $d['formacao'] ?? null]);
            $m->pdo->commit();
            $this->json(['id' => $pid], 201);
        } catch (Throwable $e) {
            $m->pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $d = $this->input();
        $m = new Professor();
        $m->pdo->beginTransaction();
        try {
            (new Pessoa())->updateById($id, $d);
            $m->updateById($id, ['inicio' => $d['inicio'] ?? null,'formacao' => $d['formacao'] ?? null]);
            $m->pdo->commit();
            $this->json(['updated' => true]);
        } catch (Throwable $e) {
            $m->pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $ok = (new Pessoa())->deleteById($id);
        $this->json(['deleted' => $ok]);
    }
}
