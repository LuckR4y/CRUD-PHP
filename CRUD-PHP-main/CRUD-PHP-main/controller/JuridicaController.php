<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Juridica.php';
require_once __DIR__ . '/../models/Pessoa.php';

class JuridicaController extends Controller
{
    public function index()
    {
        $sql = "SELECT j.id, j.cnpj, j.inscEstadual, j.inscMunicipal, j.abertura, j.nomeFantasia, j.cnae, p.nome, p.telefone, p.celular
                FROM juridica j JOIN pessoa p ON p.id = j.id ORDER BY j.id DESC";
        $pdo = Database::get();
        $this->json($pdo->query($sql)->fetchAll());
    }

    public function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $sql = "SELECT j.id, j.cnpj, j.inscEstadual, j.inscMunicipal, j.abertura, j.nomeFantasia, j.cnae, p.nome, p.telefone, p.celular
                FROM juridica j JOIN pessoa p ON p.id = j.id WHERE j.id = ?";
        $pdo = Database::get();
        $st  = $pdo->prepare($sql);
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
            (new Juridica())->create([
                'id' => $pid,
                'cnpj' => $d['cnpj'] ?? null,
                'inscEstadual' => $d['inscEstadual'] ?? null,
                'inscMunicipal' => $d['inscMunicipal'] ?? null,
                'abertura' => $d['abertura'] ?? null,
                'nomeFantasia' => $d['nomeFantasia'] ?? null,
                'cnae' => $d['cnae'] ?? null,
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
            (new Juridica())->updateById($id, [
                'cnpj' => $d['cnpj'] ?? null,
                'inscEstadual' => $d['inscEstadual'] ?? null,
                'inscMunicipal' => $d['inscMunicipal'] ?? null,
                'abertura' => $d['abertura'] ?? null,
                'nomeFantasia' => $d['nomeFantasia'] ?? null,
                'cnae' => $d['cnae'] ?? null,
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
            (new Juridica())->deleteById($id);
            (new Pessoa())->deleteById($id);
            $pdo->commit();
            $this->json(['deleted' => true]);
        } catch (Throwable $e) {
            $pdo->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
