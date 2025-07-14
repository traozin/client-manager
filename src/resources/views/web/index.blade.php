@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-3">Consulta de Clientes</h4>

        <form id="formConsulta">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="nome" class="form-control" placeholder="Nome do cliente">
                </div>
                <div class="col-md-4 mb-2">
                    <select name="cidade_id" id="cidadeFiltro" class="form-control"></select>
                </div>
                <div class="col-md-4 mb-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                    <button type="reset" class="btn btn-secondary" id="btnLimpar">Limpar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Ações</th>
                        <th>Nome</th>
                        <th>Cidade</th>
                        <th>Representantes</th>
                    </tr>
                </thead>
                <tbody id="tabelaClientes"></tbody>
            </table>
            <nav>
                <ul class="pagination" id="paginacao"></ul>
            </nav>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEdicao">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="mb-3">
                            <label>Nome:</label>
                            <input type="text" name="nome" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Cidade:</label>
                            <select name="cidade_id" class="form-control" id="cidadeEdit"></select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tabela = document.getElementById('tabelaClientes');
        const paginacao = document.getElementById('paginacao');
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));
        const formConsulta = document.getElementById('formConsulta');
        const formEdicao = document.getElementById('formEdicao');
        const btnLimpar = document.getElementById('btnLimpar');
        const cidadeFiltroSelect = document.getElementById('cidadeFiltro');
        const cidadeEditSelect = document.getElementById('cidadeEdit');

        document.addEventListener('DOMContentLoaded', () => {
            carregarCidades(cidadeFiltroSelect);
            carregarCidades(cidadeEditSelect);

            formConsulta.addEventListener('submit', e => {
                e.preventDefault();
                buscarClientes(1);
            });

            formEdicao.addEventListener('submit', salvarEdicao);

            btnLimpar.addEventListener('click', () => {
                formConsulta.reset();
                tabela.innerHTML = '';
                paginacao.innerHTML = '';
                buscarClientes(1);
            });

            buscarClientes(1);
        });

        async function carregarCidades(selectElement) {
            try {
                const res = await fetch('/api/v1/cidades');
                if (!res.ok) {
                    throw new Error(`Erro HTTP: ${res.status}`);
                }
                const responseData = await res.json();
                const cidades = responseData.data;

                selectElement.innerHTML = '<option value="">Selecione</option>';
                cidades.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.nome;
                    selectElement.appendChild(opt);
                });
            } catch (error) {
                console.error('Erro ao carregar cidades:', error);
                alert('Não foi possível carregar as cidades. Tente novamente mais tarde.');
            }
        }

        async function buscarClientes(pagina = 1) {
            const params = new URLSearchParams(new FormData(formConsulta));
            params.append('page', pagina);

            try {
                const res = await fetch(`/api/v1/clientes?${params}`);
                if (!res.ok) {
                    const errorBody = await res.text();
                    throw new Error(`Erro HTTP: ${res.status} - ${errorBody}`);
                }
                const responseData = await res.json();
                const clientesPaginados = responseData.data;

                const data = clientesPaginados.data;
                const links = clientesPaginados.links;

                tabela.innerHTML = '';
                if (data.length === 0) {
                    tabela.innerHTML = '<tr><td colspan="4" class="text-center">Nenhum cliente encontrado.</td></tr>';
                } else {
                    for (const cliente of data) {
                        const { data: representantes } = await fetchRepresentantes(cliente.id);

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>
                            <button class="btn btn-sm btn-success" data-id="${cliente.id}">Editar</button>
                            <button class="btn btn-sm btn-danger" data-id="${cliente.id}">Excluir</button>
                        </td>
                        <td>${cliente.nome}</td>
                        <td>${cliente.cidade.nome}</td>
                        <td>${representantes.map(r => r.nome).join(', ')}</td>
                    `;
                        tabela.appendChild(tr);

                        tr.querySelector('.btn-success').addEventListener('click', () => abrirModal(cliente.id));
                        tr.querySelector('.btn-danger').addEventListener('click', () => excluirCliente(cliente.id));
                    }
                }

                renderizarPaginacao(links);

            } catch (error) {
                console.error('Erro ao buscar clientes:', error);
                alert('Não foi possível buscar os clientes. Tente novamente mais tarde.');
            }
        }

        async function fetchRepresentantes(clienteId) {
            try {
                const repsRes = await fetch(`/api/v1/clientes/${clienteId}/representantes`);
                if (!repsRes.ok) {
                    throw new Error(`Erro HTTP ao buscar representantes: ${repsRes.status}`);
                }
                return await repsRes.json();
            } catch (error) {
                console.error(`Erro ao buscar representantes para o cliente ${clienteId}:`, error);
                return [];
            }
        }

        function renderizarPaginacao(links) {
            paginacao.innerHTML = '';
            links.forEach(link => {
                if (link.url) {
                    const li = document.createElement('li');
                    li.className = `page-item ${link.active ? 'active' : ''}`;
                    const page = new URL(link.url).searchParams.get('page');
                    li.innerHTML = `<a class="page-link" href="#">${link.label}</a>`;
                    li.querySelector('.page-link').addEventListener('click', (e) => {
                        e.preventDefault();
                        buscarClientes(page);
                    });
                    paginacao.appendChild(li);
                }
            });
        }

        async function abrirModal(id) {
            try {
                const res = await fetch(`/api/v1/clientes/${id}`);
                if (!res.ok) {
                    throw new Error(`Erro HTTP: ${res.status}`);
                }
                const cliente = await res.json();

                formEdicao.id.value = cliente.id;
                formEdicao.nome.value = cliente.nome;
                formEdicao.cidade_id.value = cliente.cidade_id;

                modalEditar.show();
            } catch (error) {
                console.error('Erro ao abrir modal de edição:', error);
                alert('Não foi possível carregar os dados do cliente para edição.');
            }
        }

        async function salvarEdicao(e) {
            e.preventDefault();
            const dados = Object.fromEntries(new FormData(formEdicao));
            const id = dados.id;

            try {
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
                    const errorData = await res.json();
                    alert(`Erro ao atualizar cliente: ${errorData.message || res.statusText}`);
                }
            } catch (error) {
                console.error('Erro ao salvar edição:', error);
                alert('Ocorreu um erro ao tentar salvar as alterações do cliente.');
            }
        }

        async function excluirCliente(id) {
            if (confirm('Deseja realmente excluir este cliente?')) {
                try {
                    const res = await fetch(`/api/v1/clientes/${id}`, { method: 'DELETE' });
                    if (res.ok) {
                        alert('Cliente excluído com sucesso.');
                        buscarClientes();
                    } else {
                        const errorData = await res.json();
                        alert(`Erro ao excluir cliente: ${errorData.message || res.statusText}`);
                    }
                } catch (error) {
                    console.error('Erro ao excluir cliente:', error);
                    alert('Ocorreu um erro ao tentar excluir o cliente.');
                }
            }
        }
    </script>
@endpush