<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../models/Pessoa.php';

class AlunoController extends Controller
{
    public function index()
    {
        return $this->list();
    }

    public function list()
    {
        $m = new Aluno();
        $this->json($m->allJoin());
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $res = (new Aluno())->findJoin($id);
        $this->json($res ?: ['error' => 'not found'], $res ? 200 : 404);
    }

    public function create()
    {
        $data = $this->input();
        $pdo = Database::get();
        $pdo->beginTransaction();
        try {
            $pessoaId = (new Pessoa())->create($data);
            (new Aluno())->create([
                'id' => $pessoaId,
                'matricula' => $data['matricula'] ?? null,
                'turma_id' => $data['turma_id']  ?? null
            ]);
            $pdo->commit();
            $this->json(['id' => $pessoaId], 201);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $data = $this->input();
        $pdo = Database::get();
        $pdo->beginTransaction();
        try {
            (new Pessoa())->updateById($id, $data);
            (new Aluno())->updateById($id, [
                'matricula' => $data['matricula'] ?? null,
                'turma_id' => $data['turma_id'] ?? null
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
            (new Aluno())->deleteById($id);
            (new Pessoa())->deleteById($id);
            $pdo->commit();
            $this->json(['deleted' => true]);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
