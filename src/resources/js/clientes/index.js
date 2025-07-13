const tabela = document.getElementById('tabelaClientes');
const paginacao = document.getElementById('paginacao');
const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));

document.addEventListener('DOMContentLoaded', () => {
    carregarCidades('cidadeFiltro');
    carregarCidades('cidadeEdit');

    document.getElementById('formConsulta').addEventListener('submit', e => {
        e.preventDefault();
        buscarClientes(1);
    });

    document.getElementById('formEdicao').addEventListener('submit', salvarEdicao);
    document.getElementById('btnLimpar').addEventListener('click', () => {
        tabela.innerHTML = '';
        paginacao.innerHTML = '';
    });

    buscarClientes(1);
});

async function carregarCidades(idSelect) {
    const res = await fetch('/api/v1/cidades');
    const cidades = await res.json();
    const select = document.getElementById(idSelect);
    select.innerHTML = '<option value="">Selecione</option>';
    cidades.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.nome;
        select.appendChild(opt);
    });
}

async function buscarClientes(pagina = 1) {
    const params = new URLSearchParams(new FormData(document.getElementById('formConsulta')));
    params.append('page', pagina);
    const res = await fetch(`/api/v1/clientes?${params}`);
    const { data, links } = await res.json();

    tabela.innerHTML = '';
    for (const cliente of data) {
        const reps = await fetch(`/api/v1/clientes/${cliente.id}/representantes`);
        const representantes = await reps.json();

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <button class="btn btn-sm btn-success" onclick="abrirModal(${cliente.id})">Editar</button>
                <button class="btn btn-sm btn-danger" onclick="excluirCliente(${cliente.id})">Excluir</button>
            </td>
            <td>${cliente.nome}</td>
            <td>${cliente.cidade.nome}</td>
            <td>${representantes.map(r => r.nome).join(', ')}</td>
        `;
        tabela.appendChild(tr);
    }

    paginacao.innerHTML = '';
    links.forEach(link => {
        if (link.url) {
            const li = document.createElement('li');
            li.className = `page-item ${link.active ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="buscarClientes(${new URL(link.url).searchParams.get('page')})">${link.label}</a>`;
            paginacao.appendChild(li);
        }
    });
}

async function abrirModal(id) {
    const res = await fetch(`/api/v1/clientes/${id}`);
    const cliente = await res.json();

    const form = document.getElementById('formEdicao');
    form.id.value = cliente.id;
    form.nome.value = cliente.nome;
    form.cidade_id.value = cliente.cidade_id;

    modalEditar.show();
}

async function salvarEdicao(e) {
    e.preventDefault();
    const form = e.target;
    const dados = Object.fromEntries(new FormData(form));
    const id = dados.id;

    const res = await fetch(`/api/v1/clientes/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    });

    if (res.ok) {
        alert('Cliente atualizado com sucesso!');
        modalEditar.hide();
        buscarClientes();
    } else {
        alert('Erro ao atualizar cliente.');
    }
}

async function excluirCliente(id) {
    if (confirm('Deseja excluir este cliente?')) {
        const res = await fetch(`/api/v1/clientes/${id}`, { method: 'DELETE' });
        if (res.ok) {
            alert('Cliente excluído.');
            buscarClientes();
        } else {
            alert('Erro ao excluir cliente.');
        }
    }
}
