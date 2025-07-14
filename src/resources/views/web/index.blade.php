@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Consulta de Clientes</h4>
            <a href="{{ route('clientes.create') }}" class="btn btn-success">Criar Novo Cliente</a>
        </div>

        <form id="formConsulta" class="mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <label for="cpfFiltro" class="form-label">CPF</label>
                    <input type="text" name="cpf" id="cpfFiltro" class="form-control" placeholder="000.000.000-00">
                </div>
                <div class="col-md-5">
                    <label for="nomeFiltro" class="form-label">Nome do cliente</label>
                    <input type="text" name="nome" id="nomeFiltro" class="form-control" placeholder="Nome do cliente">
                </div>
                <div class="col-md-4">
                    <label for="dataNascimentoFiltro" class="form-label">Data Nascimento</label>
                    <input type="date" name="data_nascimento" id="dataNascimentoFiltro" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label d-block">Sexo</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="sexoFiltroMasculino" value="masculino">
                        <label class="form-check-label" for="sexoFiltroMasculino">Masculino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="sexoFiltroFeminino" value="feminino">
                        <label class="form-check-label" for="sexoFiltroFeminino">Feminino</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="estadoFiltro" class="form-label">Estado</label>
                    <select name="estado" id="estadoFiltro" class="form-select"></select>
                </div>
                <div class="col-md-2">
                    <label for="cidadeFiltro" class="form-label">Cidade</label>
                    <select name="cidade_id" id="cidadeFiltro" class="form-select"></select>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                    <button type="reset" class="btn btn-secondary" id="btnLimpar">Limpar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width: 15%;">Ações</th>
                        <th style="width: 25%;">Nome</th>
                        <th style="width: 15%;">CPF</th>
                        <th style="width: 15%;">Data Nasc.</th>
                        <th style="width: 10%;">Estado</th>
                        <th style="width: 20%;">Cidade</th>
                        <th style="width: 10%;">Sexo</th>
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
                            <label for="editCpf" class="form-label">CPF:</label>
                            <input type="text" name="cpf" id="editCpf" class="form-control" placeholder="000.000.000-00">
                        </div>
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Nome:</label>
                            <input type="text" name="nome" id="editNome" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editDataNascimento" class="form-label">Data Nascimento:</label>
                            <input type="date" name="data_nascimento" id="editDataNascimento" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Sexo:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="editSexoMasculino"
                                    value="masculino">
                                <label class="form-check-label" for="editSexoMasculino">Masculino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="sexo" id="editSexoFeminino"
                                    value="feminino">
                                <label class="form-check-label" for="editSexoFeminino">Feminino</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editEndereco" class="form-label">Endereço:</label>
                            <input type="text" name="endereco" id="editEndereco" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editEstado" class="form-label">Estado:</label>
                            <select name="estado" id="editEstado" class="form-select"></select>
                        </div>
                        <div class="mb-3">
                            <label for="editCidade" class="form-label">Cidade:</label>
                            <select name="cidade_id" id="editCidade" class="form-select"></select>
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

        const cpfFiltroInput = document.getElementById('cpfFiltro');
        const dataNascimentoFiltroInput = document.getElementById('dataNascimentoFiltro');
        const sexoFiltroSelect = document.getElementById('sexoFiltro');
        const enderecoFiltroInput = document.getElementById('enderecoFiltro');
        const estadoFiltroSelect = document.getElementById('estadoFiltro');
        const cidadeFiltroSelect = document.getElementById('cidadeFiltro');

        const editCpfInput = document.getElementById('editCpf');
        const editNomeInput = document.getElementById('editNome');
        const editDataNascimentoInput = document.getElementById('editDataNascimento');
        const editSexoMasculinoRadio = document.getElementById('editSexoMasculino');
        const editSexoFemininoRadio = document.getElementById('editSexoFeminino');
        const editEnderecoInput = document.getElementById('editEndereco');
        const editEstadoSelect = document.getElementById('editEstado');
        const editCidadeSelect = document.getElementById('editCidade');

        const estados = ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"];

        document.addEventListener('DOMContentLoaded', () => {
            carregarCidades(cidadeFiltroSelect);
            carregarCidades(editCidadeSelect);

            carregarEstados(estadoFiltroSelect);
            carregarEstados(editEstadoSelect);

            if (cpfFiltroInput) {
                cpfFiltroInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    e.target.value = value;
                });
            }

            if (editCpfInput) {
                editCpfInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    e.target.value = value;
                });
            }

            formConsulta.addEventListener('submit', e => {
                e.preventDefault();
                buscarClientes(1);
            });

            formEdicao.addEventListener('submit', salvarEdicao);

            btnLimpar.addEventListener('click', () => {
                formConsulta.reset();
                tabela.innerHTML = '';
                paginacao.innerHTML = '';
                if (cpfFiltroInput) cpfFiltroInput.value = '';
                buscarClientes(1);
            });

            buscarClientes(1);
        });

        function carregarEstados(selectElement) {
            selectElement.innerHTML = '<option value="">Selecione</option>';
            estados.forEach(estado => {
                const opt = document.createElement('option');
                opt.value = estado;
                opt.textContent = estado;
                selectElement.appendChild(opt);
            });
        }

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
                    tabela.innerHTML = '<tr><td colspan="7" class="text-center">Nenhum cliente encontrado.</td></tr>';
                } else {
                    for (const cliente of data) {
                        const { data: representantes } = await fetchRepresentantes(cliente.id);

                        const dataNascimentoFormatada = cliente.data_nascimento ? new Date(cliente.data_nascimento).toLocaleDateString('pt-BR') : '';

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                                            <td>
                                                <button class="btn btn-sm btn-success" data-id="${cliente.id}">Editar</button>
                                                <button class="btn btn-sm btn-danger" data-id="${cliente.id}">Excluir</button>
                                            </td>
                                            <td>${cliente.nome}</td>
                                            <td>${cliente.cpf || ''}</td>
                                            <td>${dataNascimentoFormatada}</td>
                                            <td>${cliente.cidade?.estado?.sigla || ''}</td>
                                            <td>${cliente.cidade.nome}</td>
                                            <td>${cliente.sexo ? (cliente.sexo === 'masculino' ? 'M' : 'F') : ''}</td>
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
                    const errorBody = await repsRes.text();
                    console.error(`Erro HTTP ao buscar representantes para o cliente ${clienteId}: ${repsRes.status} - ${errorBody}`);
                    return { data: [] };
                }
                return await repsRes.json();
            } catch (error) {
                console.error(`Erro geral ao buscar representantes para o cliente ${clienteId}:`, error);
                return { data: [] };
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
                const responseData = await res.json();
                const cliente = responseData.data;

                // Preencher campos do modal
                formEdicao.id.value = cliente.id;
                editCpfInput.value = cliente.cpf || '';
                editNomeInput.value = cliente.nome || '';

                if (cliente.data_nascimento) {
                    const date = new Date(cliente.data_nascimento);
                    editDataNascimentoInput.value = date.toISOString().split('T')[0];
                } else {
                    editDataNascimentoInput.value = '';
                }

                // Sexo
                if (cliente.sexo === 'masculino') {
                    editSexoMasculinoRadio.checked = true;
                } else if (cliente.sexo === 'feminino') {
                    editSexoFemininoRadio.checked = true;
                } else {
                    editSexoMasculinoRadio.checked = false;
                    editSexoFemininoRadio.checked = false;
                }

                editEnderecoInput.value = cliente.endereco || '';
                editEstadoSelect.value = cliente.estado || '';
                editCidadeSelect.value = cliente.cidade_id || '';

                modalEditar.show();
            } catch (error) {
                console.error('Erro ao abrir modal de edição:', error);
                alert('Não foi possível carregar os dados do cliente para edição.');
            }
        }

        async function salvarEdicao(e) {
            e.preventDefault();
            const form = e.target;
            const dados = Object.fromEntries(new FormData(form));
            const id = dados.id;

            dados.sexo = form.querySelector('input[name="sexo"]:checked')?.value || null;

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
                    if (res.status === 422 && errorData.errors) {
                        let errorMessages = 'Erro de validação:\n';
                        for (const field in errorData.errors) {
                            errorMessages += `- ${errorData.errors[field].join(', ')}\n`;
                        }
                        alert(errorMessages);
                    } else {
                        alert(`Erro ao atualizar cliente: ${errorData.message || res.statusText}`);
                    }
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