<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/UF.php';

class UFController extends Controller
{
    public function index()
    {
        $this->json((new UF())->all());
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $r = (new UF())->find($id);
        $this->json($r ?: ['error' => 'not found'], $r ? 200 : 404);
    }

    public function create()
    {
        $id = (new UF())->create($this->input());
        $this->json(['id' => $id], 201);
    }

    public function update()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $ok = (new UF())->updateById($id, $this->input());
        $this->json(['updated' => $ok]);
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $ok = (new UF())->deleteById($id);
        $this->json(['deleted' => $ok]);
    }
}
