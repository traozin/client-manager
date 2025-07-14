@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-4">Cadastro de Cliente</h4>

            <form id="formCadastro">
                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                        @error('cpf') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                        @error('nome') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="data_nascimento" class="form-label">Data Nascimento</label>
                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                        @error('data_nascimento') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label d-block">Sexo</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" id="sexoMasculino" value="masculino">
                            <label class="form-check-label" for="sexoMasculino">Masculino</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sexo" id="sexoFeminino" value="feminino">
                            <label class="form-check-label" for="sexoFeminino">Feminino</label>
                        </div>
                        @error('sexo') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco">
                        @error('endereco') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Selecione</option>
                        </select>
                        @error('estado') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="cidade_id" class="form-label">Cidade</label>
                        <select class="form-select" id="cidade_id" name="cidade_id" required disabled>
                            <option value="">Selecione</option>
                        </select>
                        @error('cidade_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="reset" class="btn btn-secondary">Limpar</button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const formCadastro = document.getElementById('formCadastro');

            const estados = ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"];

            const estadoSelect = document.getElementById('estado');
            const cidadeSelect = document.getElementById('cidade_id');
            const cpfInput = document.getElementById('cpf');

            preencherEstados();

            estadoSelect.addEventListener('change', () => {
                const sigla = estadoSelect.value;
                if (!sigla) {
                    cidadeSelect.innerHTML = '<option value="">Selecione</option>';
                    cidadeSelect.disabled = true;
                    return;
                }

                carregarCidadesPorEstado(sigla);
            });

            if (cpfInput) {
                cpfInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    e.target.value = value;
                });
            }

            function preencherEstados() {
                estados.forEach(estado => {
                    const opt = document.createElement('option');
                    opt.value = estado;
                    opt.textContent = estado;
                    estadoSelect.appendChild(opt);
                });
            }

            async function carregarCidadesPorEstado(sigla) {
                try {
                    const res = await fetch(`/api/v1/cidades?estado=${sigla}`);
                    if (!res.ok) throw new Error(`Erro HTTP: ${res.status}`);
                    const response = await res.json();
                    const cidades = response.data;

                    cidadeSelect.innerHTML = '<option value="">Selecione</option>';
                    cidades.forEach(cidade => {
                        const opt = document.createElement('option');
                        opt.value = cidade.id;
                        opt.textContent = cidade.nome;
                        cidadeSelect.appendChild(opt);
                    });

                    cidadeSelect.disabled = false;
                } catch (error) {
                    console.error('Erro ao carregar cidades:', error);
                    alert('Erro ao carregar cidades para o estado selecionado.');
                    cidadeSelect.disabled = true;
                }
            }

            formCadastro.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(formCadastro);
                const data = Object.fromEntries(formData);

                try {
                    const res = await fetch('{{ route('clientes.store') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    if (res.ok) {
                        alert('Cliente cadastrado com sucesso!');
                        window.location.href = '/';
                    } else {
                        const errorData = await res.json();
                        if (res.status === 422 && errorData.errors) {
                            let msg = 'Erro de validação:\n';
                            for (const campo in errorData.errors) {
                                msg += `- ${errorData.errors[campo].join(', ')}\n`;
                            }
                            alert(msg);
                        } else {
                            alert(`Erro ao cadastrar cliente: ${errorData.message || res.statusText}`);
                        }
                    }
                } catch (error) {
                    console.error('Erro ao cadastrar cliente:', error);
                    alert('Ocorreu um erro ao tentar salvar o cliente.');
                }
            });
        });
    </script>
@endpush