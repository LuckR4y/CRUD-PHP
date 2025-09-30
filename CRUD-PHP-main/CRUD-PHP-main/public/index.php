<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    foreach (['core','models','controller','controllers'] as $dir) {
        $f = __DIR__ . '/../' . $dir . '/' . $class . '.php';
        if (file_exists($f)) {
            require_once $f;
            return;
        }
    }
});

if (!isset($_GET['entity'])) {
    ?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8"/>
  <title>Trabalho Parcial</title>
  <style>
    :root {
      --fg:#111;
      --muted:#6b7280;
      --line:#e5e7eb;
      --bg:#fafafa;
      --pri:#7c3aed;  
      --pri-600:#6d28d9; 
    }
    *{ box-sizing:border-box }
    body {
      font-family: system-ui, Arial; color:var(--fg); background:var(--bg);
      max-width: 1120px; margin: 32px auto; padding:0 16px;
    }
    h1 { margin:0; font-size: 32px; }
    .bar { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
    .muted { color:var(--muted); font-size:14px; }

    select, input, button {
      padding:10px 12px; border:1px solid var(--line); border-radius:10px; background:#fff; outline:none;
    }
    select:focus, input:focus { border-color: var(--pri); box-shadow: 0 0 0 3px rgba(124,58,237,.12); }

    button {
      background:var(--pri); color:#fff; border:none; font-weight:600; cursor:pointer;
    }
    button:hover { background:var(--pri-600); }

    button.secondary {
      background:#fff; color:var(--fg); border:1px solid var(--line);
    }
    button.secondary:hover { background:#f9fafb; }

    button.blue { background:#2563eb; color:#fff; }
    button.blue:hover { background:#1d4ed8; }

    button.danger { background:#ef4444; color:#fff; }
    button.danger:hover { background:#dc2626; }

    table { width:100%; border-collapse: collapse; margin-top:16px; background:#fff; border-radius:12px; overflow:hidden; }
    th, td { border-bottom:1px solid var(--line); padding:10px 12px; text-align:left; }
    th { background:#f8fafc; font-weight:700; }
    tbody tr:nth-child(odd){ background:#fcfcfc; }
    .actions button { margin-right: 6px; }
    form {
      background:#fff; border:1px solid var(--line); border-radius:12px; padding:16px; margin-top:14px;
      display:grid; grid-template-columns: repeat(3, minmax(220px,1fr)); gap:12px;
    }
    .full { grid-column: 1 / -1; display:flex; gap:8px; }
    label.small { font-size:12px; color:var(--muted); display:block; margin-bottom:4px; }
  </style>
</head>
<body>
  <div class="bar">
    <h1>Atividade Parcial CRUD</h1>
    <span class="muted">Arthur Vital Fontana - 839832</span>
  </div>

  <div class="bar" style="margin-top:12px">
    <label for="entity" class="small">Entidade</label>
    <select id="entity">
      <option value="pessoa">Pessoa</option>
      <option value="aluno">Aluno</option>
      <option value="professor">Professor</option>
      <option value="funcionario">Funcionario</option>
      <option value="fisica">Fisica</option>
      <option value="juridica">Juridica</option>
      <option value="curso">Curso</option>
      <option value="disciplina">Disciplina</option>
      <option value="turma">Turma</option>
      <option value="uf">UF</option>
      <option value="cidade">Cidade</option>
      <option value="bairro">Bairro</option>
      <option value="endereco">Endereco</option>
      <option value="gerente">Gerente</option>
      <option value="fatura">Fatura</option>
      <option value="pagamento">Pagamento</option>
    </select>

    <button id="btnReload">Recarregar Lista</button>
    <span id="status" class="muted"></span>
  </div>

  <h3 style="margin:18px 0 8px">Novo / Editar</h3>
  <form id="form">
    <input type="hidden" name="id" id="id">
    <div class="full">
      <button type="submit">Salvar</button>
      <button type="button" id="btnLimpar" class="secondary">Limpar</button>
    </div>
  </form>

  <h3 style="margin:18px 0 8px">Lista</h3>
  <table id="grid">
    <thead><tr id="thead-row"></tr></thead>
    <tbody id="tbody"></tbody>
  </table>

<script>

const fmt = {
  currency: v => v==null? '' : new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'}).format(v),
  date: v => v ? new Date(v).toLocaleDateString('pt-BR') : '',
  bool: v => (String(v)==='1' || String(v).toLowerCase()==='true') ? 'Sim' : 'Não'
};
const entitySel = document.querySelector('#entity');
const theadRow = document.querySelector('#thead-row');
const tbody = document.querySelector('#tbody');
const form = document.querySelector('#form');
const statusEl = document.querySelector('#status');
const btnReload = document.querySelector('#btnReload');
const btnLimpar = document.querySelector('#btnLimpar');

function setStatus(msg){ statusEl.textContent = msg||''; }
function apiUrl(entity, action, id=null){
  let u = `?entity=${encodeURIComponent(entity)}&action=${encodeURIComponent(action)}`;
  if (id!=null) u += `&id=${encodeURIComponent(id)}`;
  return u;
}
async function apiGet(entity, action='index', id=null){
  const res = await fetch(apiUrl(entity, action, id));
  const raw = await res.text();
  if (!res.ok) throw new Error(raw || (res.status+' '+res.statusText));
  const cleaned = (raw||'').replace(/^\uFEFF/,'').trim();
  return cleaned ? JSON.parse(cleaned) : [];
}
function escapeHtml(s){ return String(s??'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }
window.addEventListener('error', e => setStatus('Erro JS: ' + e.message));


const META = {
  pessoa: {
    columns: [{key:'id',label:'ID'},{key:'nome',label:'Nome'},{key:'telefone',label:'Telefone'},{key:'celular',label:'Celular'}],
    fields: [
      {label:'Nome', name:'nome', type:'text', required:true},
      {label:'Telefone', name:'telefone', type:'text'},
      {label:'Celular', name:'celular', type:'text'}
    ]
  },
  aluno: {
    columns: [{key:'id',label:'ID'},{key:'nome',label:'Nome'},{key:'matricula',label:'Matrícula'},{key:'turma_id',label:'Turma ID'},{key:'telefone',label:'Telefone'},{key:'celular',label:'Celular'}],
    fields: [
      {label:'Nome (Pessoa)', name:'nome', type:'text', required:true},
      {label:'Telefone', name:'telefone', type:'text'},
      {label:'Celular', name:'celular', type:'text'},
      {label:'Matrícula', name:'matricula', type:'number', required:true},
      {label:'Turma', name:'turma_id', type:'select', fk:{entity:'turma', value:'id', label:'horario'}}
    ]
  },
  professor: {
    columns: [{key:'id',label:'ID'},{key:'nome',label:'Nome'},{key:'inicio',label:'Início',fmt:'date'},{key:'formacao',label:'Formação'},{key:'telefone',label:'Telefone'},{key:'celular',label:'Celular'}],
    fields: [
      {label:'Nome (Pessoa)', name:'nome', type:'text', required:true},
      {label:'Telefone', name:'telefone', type:'text'},
      {label:'Celular', name:'celular', type:'text'},
      {label:'Início', name:'inicio', type:'date', required:true},
      {label:'Formação', name:'formacao', type:'text', required:true}
    ]
  },
  funcionario: {
    columns: [{key:'id',label:'ID'},{key:'nome',label:'Nome'},{key:'admissao',label:'Admissão',fmt:'date'},{key:'salario',label:'Salário',fmt:'currency'},{key:'telefone',label:'Telefone'},{key:'celular',label:'Celular'}],
    fields: [
      {label:'Nome (Pessoa)', name:'nome', type:'text', required:true},
      {label:'Telefone', name:'telefone', type:'text'},
      {label:'Celular', name:'celular', type:'text'},
      {label:'Admissão', name:'admissao', type:'date', required:true},
      {label:'Salário', name:'salario', type:'number', step:'0.01', required:true}
    ]
  },
  fisica: {
    columns: [{key:'id',label:'ID'},{key:'nome',label:'Nome'},{key:'sexo',label:'Sexo'},{key:'genero',label:'Gênero'},{key:'raca',label:'Raça'}],
    fields: [
      {label:'Nome (Pessoa)', name:'nome', type:'text', required:true},
      {label:'Sexo',   name:'sexo',   type:'select', options:[['M','Masculino'],['F','Feminino']]},
      {label:'Gênero', name:'genero', type:'select', options:[
        ['Masculino','Masculino'],['Feminino','Feminino'],['Outro','Outro'],['Não Informar','Não Informar']
      ]},
      {label:'Raça',   name:'raca',   type:'select', options:[
        ['Branco','Branco'],['Negro','Negro'],['Pardo','Pardo'],['Indigena','Indígena'],['Amarelo','Amarelo'],['Não Informar','Não Informar']
      ]}
    ]
  },
  juridica: {
    columns: [
      {key:'id',label:'ID'},{key:'nome',label:'Razão Social'},{key:'cnpj',label:'CNPJ'},
      {key:'nomeFantasia',label:'Nome Fantasia'},{key:'inscEstadual',label:'Inscr. Estadual'},
      {key:'inscMunicipal',label:'Inscr. Municipal'},{key:'abertura',label:'Abertura',fmt:'date'},{key:'cnae',label:'CNAE'}
    ],
    fields: [
      {label:'Nome (Pessoa)', name:'nome', type:'text', required:true},
      {label:'CNPJ', name:'cnpj', type:'text'},
      {label:'Nome Fantasia', name:'nomeFantasia', type:'text'},
      {label:'Inscrição Estadual', name:'inscEstadual', type:'text'},
      {label:'Inscrição Municipal', name:'inscMunicipal', type:'text'},
      {label:'Abertura', name:'abertura', type:'date'},
      {label:'CNAE', name:'cnae', type:'number'}
    ]
  },
  curso: { columns: ['id','nome'], fields: [{label:'Nome', name:'nome', type:'text', required:true}] },
  disciplina: {
    columns: ['id','nome','cargaHoraria'],
    fields: [{label:'Nome', name:'nome', type:'text', required:true},{label:'Carga Horária', name:'cargaHoraria', type:'number', required:true}]
  },
  turma: {
    columns: ['id','horario','cargaHoraria','curso_id'],
    fields: [
      {label:'Horário (AAAA-MM-DD HH:MM:SS)', name:'horario', type:'text', required:true},
      {label:'Carga Horária', name:'cargaHoraria', type:'number', required:true},
      {label:'Curso', name:'curso_id', type:'select', fk:{entity:'curso', value:'id', label:'nome'}}
    ]
  },
  uf: {
    columns: ['id','nome','pais'],
    fields: [{label:'UF (sigla)', name:'nome', type:'text', required:true},{label:'País', name:'pais', type:'text', required:true}]
  },
  cidade: {
    columns: ['id','nome','uf_id'],
    fields: [{label:'Nome', name:'nome', type:'text', required:true},{label:'UF', name:'uf_id', type:'select', fk:{entity:'uf', value:'id', label:'nome'}}]
  },
  bairro: {
    columns: ['id','nome','cidade_id'],
    fields: [{label:'Nome', name:'nome', type:'text', required:true},{label:'Cidade', name:'cidade_id', type:'select', fk:{entity:'cidade', value:'id', label:'nome'}}]
  },
  endereco: {
    columns: ['id','logadouro','complemento','cep','bairro_id'],
    fields: [
      {label:'Logradouro', name:'logadouro', type:'text', required:true},
      {label:'Complemento', name:'complemento', type:'text'},
      {label:'CEP', name:'cep', type:'text', required:true},
      {label:'Bairro', name:'bairro_id', type:'select', fk:{entity:'bairro', value:'id', label:'nome'}}
    ]
  },
  gerente: {
    columns: ['id','nome','telefone','celular'],
    fields: [
      {label:'Nome (Pessoa)', name:'nome', type:'text', required:true},
      {label:'Telefone', name:'telefone', type:'text'},
      {label:'Celular', name:'celular', type:'text'}
    ]
  },
  fatura: {
    columns: [{key:'id',label:'ID'},{key:'numDoc',label:'Nº Doc'},{key:'valor',label:'Valor',fmt:'currency'},{key:'vencimento',label:'Vencimento',fmt:'date'},{key:'cancelado',label:'Cancelado',fmt:'bool'},{key:'tipo',label:'Tipo'},{key:'gerente_id',label:'Gerente ID'}],
    fields: [
      {label:'Num. Doc', name:'numDoc', type:'text', required:true},
      {label:'Valor', name:'valor', type:'number', step:'0.01', required:true},
      {label:'Vencimento', name:'vencimento', type:'date', required:true},
      {label:'Cancelado (0/1)', name:'cancelado', type:'number'},
      {label:'Tipo', name:'tipo', type:'text', required:true},
      {label:'Gerente', name:'gerente_id', type:'select', fk:{entity:'gerente', value:'id', label:'nome'}}
    ]
  },
  pagamento: {
    columns: [{key:'id',label:'ID'},{key:'valor',label:'Valor',fmt:'currency'},{key:'data',label:'Data',fmt:'date'},{key:'fatura_id',label:'Fatura ID'}],
    fields: [
      {label:'Valor', name:'valor', type:'number', step:'0.01', required:true},
      {label:'Data', name:'data', type:'date', required:true},
      {label:'Fatura', name:'fatura_id', type:'select', fk:{entity:'fatura', value:'id', label:'numDoc'}}
    ]
  }
};

function getCols(entity){
  const raw = (META[entity]?.columns) || [];
  return raw.map(c => (typeof c === 'string') ? ({ key: c, label: c }) : c);
}

const fkCache = {};

async function populateSelectFromFK(selectEl, fk){
  const ent = fk.entity;
  if (!fkCache[ent]) fkCache[ent] = await apiGet(ent, 'index');
  const rows = fkCache[ent] || [];
  selectEl.innerHTML = '<option value="">-- selecione --</option>';
  rows.forEach(r=>{
    const value = r[fk.value];
    const label = (fk.label && r[fk.label]!=null)
      ? `${r[fk.value]} - ${r[fk.label]}`
      : `${r[fk.value]}`;
    const op = new Option(label, value);
    selectEl.add(op);
  });
}

function addFieldInput(f){
  const wrap = document.createElement('div');
  const lab = document.createElement('label');
  lab.className = 'small';
  lab.textContent = f.label || f.name;
  wrap.appendChild(lab);

  let el;
  if (f.type === 'select') {
    el = document.createElement('select');
    el.className = 'dyn';
    el.name = f.name;
    el.id = f.name;
    el.innerHTML = '<option value="">-- selecione --</option>';
    if (Array.isArray(f.options)) {
      f.options.forEach(([val,txt]) => el.add(new Option(txt, val)));
    }
    if (f.fk) populateSelectFromFK(el, f.fk);
    if (f.required) el.required = true;
  } else {
    el = document.createElement('input');
    el.type = f.type || 'text';
    el.className = 'dyn';
    el.name = f.name;
    el.id = f.name;
    if (f.step) el.step = f.step;
    if (f.required) el.required = true;
    el.placeholder = f.placeholder || '';
  }

  wrap.appendChild(el);
  form.insertBefore(wrap, form.lastElementChild);
}

function buildForm(entity){
  [...form.querySelectorAll('.dyn')].forEach(el=>el.parentElement.remove());
  const meta = META[entity];
  if (!meta || !meta.fields) return;
  meta.fields.forEach(addFieldInput);
}

function buildHead(entity){
  const cols = getCols(entity);
  theadRow.innerHTML = cols.map(c=>`<th>${escapeHtml(c.label)}</th>`).join('') + '<th>Ações</th>';
}

async function loadList(){
  const entity = entitySel.value;
  setStatus('Carregando...');
  try{
    const data = await apiGet(entity,'index');
    setStatus('');
    tbody.innerHTML = '';
    const cols = getCols(entity);
    data.forEach(row=>{
      const tr = document.createElement('tr');
      let tds = '';
      cols.forEach(c=>{
        const f = c.fmt && fmt[c.fmt] ? fmt[c.fmt] : (x=>x??'');
        tds += `<td>${escapeHtml(f(row[c.key ?? c]))}</td>`;
      });
      tds += `<td class="actions">
                <button class="blue" onclick="editar('${entity}', ${row.id})">Editar</button>
                <button class="danger" onclick="excluir('${entity}', ${row.id})">Excluir</button>
              </td>`;
      tr.innerHTML = tds;
      tbody.appendChild(tr);
    });
  }catch(e){ setStatus('Erro: '+ e.message.slice(0,200)); }
}

async function editar(entity, id){
  try{
    const r = await apiGet(entity,'show',id);
    form.querySelector('#id').value = r.id ?? '';
    (META[entity]?.fields||[]).forEach(f=>{
      const el = form.querySelector('#'+f.name);
      if (el) el.value = (r[f.name] ?? '');
    });
    window.scrollTo({top:0,behavior:'smooth'});
  }catch(e){ setStatus('Erro show: '+e.message.slice(0,200)); }
}
window.editar = editar;

async function excluir(entity, id){
  if (!confirm(`Excluir #${id}?`)) return;
  try{
    const res = await fetch(apiUrl(entity,'delete',id), { method:'POST' });
    const raw = await res.text();
    if (!res.ok) throw new Error(raw);
    loadList();
  }catch(e){ setStatus('Erro ao excluir: '+e.message.slice(0,200)); }
}
window.excluir = excluir;

form.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const entity = entitySel.value;
  const payload = {};
  (META[entity]?.fields||[]).forEach(f=>{
    const el = form.querySelector('#'+f.name);
    if (el) payload[f.name] = el.value || null;
  });
  const id = form.querySelector('#id').value;
  try{
    let res;
    if (id) {
      res = await fetch(apiUrl(entity,'update',id), { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
    } else {
      res = await fetch(apiUrl(entity,'create'), { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
    }
    const raw = await res.text();
    if (!res.ok) throw new Error(raw);
    form.reset();
    setStatus('Salvo com sucesso');
    loadList();
  }catch(e){ setStatus('Erro salvar: '+e.message.slice(0,200)); }
});


btnReload.addEventListener('click', loadList);
btnLimpar.addEventListener('click', ()=>form.reset());
entitySel.addEventListener('change', ()=>{
  buildForm(entitySel.value);
  buildHead(entitySel.value);
  loadList();
});
buildForm(entitySel.value);
buildHead(entitySel.value);
loadList();
</script>
</body>
</html>
<?php
      exit;
}


$map = [
  'aluno' => 'AlunoController',
  'bairro' => 'BairroController',
  'cidade' => 'CidadeController',
  'curso' => 'CursoController',
  'disciplina' => 'DisciplinaController',
  'endereco' => 'EnderecoController',
  'fatura' => 'FaturaController',
  'fisica' => 'FisicaController',
  'funcionario' => 'FuncionarioController',
  'gerente' => 'GerenteController',
  'juridica' => 'JuridicaController',
  'pagamento' => 'PagamentoController',
  'pessoa' => 'PessoaController',
  'professor' => 'ProfessorController',
  'turma' => 'TurmaController',
  'uf' => 'UFController',
];

$entity = strtolower($_GET['entity'] ?? '');
$action = strtolower($_GET['action'] ?? 'index');

if (!isset($map[$entity])) {
    http_response_code(404);
    echo "Entidade inválida";
    exit;
}

$controllerName = $map[$entity];
$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "Ação inválida";
    exit;
}

$controller->$action();
